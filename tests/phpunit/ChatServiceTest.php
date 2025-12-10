<?php

use MediaWiki\Extension\Chatbot\ChatService;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Test\MwIntegrationTestCase;
use Psr\Log\NullLogger;
use TestConfig;

/**
 * @group Chatbot
 */
class ChatServiceTest extends MwIntegrationTestCase {
    public function testStubReplyWhenNoEndpoint(): void {
        $http = $this->createMock( HttpRequestFactory::class );
        $config = new TestConfig( [
            'ChatbotEndpointUrl' => '',
            'ChatbotSystemPrompt' => 'system',
            'ChatbotMaxTokens' => 16,
            'ChatbotApiToken' => ''
        ] );

        $service = new ChatService( $http, $config, new NullLogger() );
        $result = $service->respond( 'Hello' );

        $this->assertStringContainsString( 'Echo: Hello', $result['reply'] );
        $this->assertSame( 'stub', $result['meta']['source'] );
    }
}
