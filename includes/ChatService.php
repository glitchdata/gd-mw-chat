<?php

namespace MediaWiki\Extension\Chatbot;

use Config;
use MediaWiki\Http\HttpRequestFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * ChatService is a small facade over the configured chatbot backend.
 */
class ChatService {
    private HttpRequestFactory $httpFactory;
    private Config $config;
    private LoggerInterface $logger;

    public function __construct(
        HttpRequestFactory $httpFactory,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->httpFactory = $httpFactory;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Send a prompt to the chatbot backend and return the reply.
     *
     * @param string $message User message content
     * @param array $history Optional message history: list of [role => string, content => string]
     * @param array $options Optional overrides: systemPrompt, maxTokens
     * @return array { reply: string, meta: array }
     */
    public function respond( string $message, array $history = [], array $options = [] ): array {
        $endpoint = trim( (string)$this->config->get( 'ChatbotEndpointUrl' ) );
        $systemPrompt = $options['systemPrompt'] ?? $this->config->get( 'ChatbotSystemPrompt' );
        $maxTokens = (int)( $options['maxTokens'] ?? $this->config->get( 'ChatbotMaxTokens' ) );

        if ( $endpoint === '' ) {
            return [
                'reply' => $this->buildStubReply( $message ),
                'meta' => [ 'source' => 'stub', 'timestamp' => ConvertibleTimestamp::now()->getTimestamp( TS_ISO_8601 ) ]
            ];
        }

        $payload = [
            'system' => $systemPrompt,
            'messages' => $this->normalizeMessages( $history, $message ),
            'max_tokens' => $maxTokens,
        ];

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $token = (string)$this->config->get( 'ChatbotApiToken' );
        if ( $token !== '' ) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $req = $this->httpFactory->create( $endpoint, [
            'method' => 'POST',
            'timeout' => 20,
            'headers' => $headers,
            'postData' => json_encode( $payload )
        ], __METHOD__ );

        $status = $req->execute();
        if ( !$status->isOK() ) {
            $message = $status->getMessage() ? $status->getMessage()->text() : 'unknown error';
            $this->logger->warning( 'Chatbot backend request failed', [ 'status' => $message ] );
            return [
                'reply' => 'The chatbot backend is unavailable right now.',
                'meta' => [ 'source' => 'error', 'status' => $message ]
            ];
        }

        $body = $req->getContent();
        $decoded = json_decode( $body, true );
        if ( !is_array( $decoded ) || !isset( $decoded['reply'] ) ) {
            $this->logger->warning( 'Chatbot backend returned unexpected payload', [ 'body' => $body ] );
            return [
                'reply' => 'Received an unexpected response from the chatbot backend.',
                'meta' => [ 'source' => 'error' ]
            ];
        }

        return [
            'reply' => (string)$decoded['reply'],
            'meta' => $decoded['meta'] ?? []
        ];
    }

    /**
     * Normalize messages into a system/user/assistant sequence.
     *
     * @param array $history
     * @param string $message
     * @return array
     */
    private function normalizeMessages( array $history, string $message ): array {
        $messages = [];
        foreach ( $history as $item ) {
            if ( !isset( $item['role'], $item['content'] ) ) {
                continue;
            }
            $messages[] = [
                'role' => (string)$item['role'],
                'content' => (string)$item['content']
            ];
        }
        $messages[] = [
            'role' => 'user',
            'content' => $message
        ];
        return $messages;
    }

    private function buildStubReply( string $message ): string {
        return 'Echo: ' . $message;
    }
}
