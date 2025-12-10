<?php

namespace MediaWiki\Extension\Chatbot;

use ApiBase;
use ApiMain;
use MediaWiki\MediaWikiServices;
use Wikimedia\ParamValidator\ParamValidator;

class ApiChatbot extends ApiBase {
    private ChatService $chatService;

    public function __construct( ApiMain $mainModule, string $moduleName, ChatService $chatService ) {
        parent::__construct( $mainModule, $moduleName );
        $this->chatService = $chatService;
    }

    public static function factory( ApiMain $mainModule, string $moduleName ): self {
        $services = MediaWikiServices::getInstance();
        return new self( $mainModule, $moduleName, $services->getService( 'ChatbotService' ) );
    }

    public function execute(): void {
        $params = $this->extractRequestParams();
        $message = (string)$params['message'];
        $history = $this->decodeHistory( $params['history'] ?? '' );

        $result = $this->chatService->respond( $message, $history );

        $this->getResult()->addValue( null, $this->getModuleName(), [
            'reply' => $result['reply'],
            'meta' => $result['meta']
        ] );
    }

    public function getAllowedParams(): array {
        return [
            'message' => [
                ParamValidator::PARAM_TYPE => 'string',
                ParamValidator::PARAM_REQUIRED => true
            ],
            'history' => [
                ParamValidator::PARAM_TYPE => 'string',
                ParamValidator::PARAM_REQUIRED => false,
                ParamValidator::PARAM_DEFAULT => ''
            ]
        ];
    }

    public function isReadMode(): bool {
        return false;
    }

    private function decodeHistory( string $history ): array {
        if ( $history === '' ) {
            return [];
        }
        $decoded = json_decode( $history, true );
        return is_array( $decoded ) ? $decoded : [];
    }
}
