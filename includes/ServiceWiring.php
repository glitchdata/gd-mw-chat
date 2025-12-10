<?php

use MediaWiki\Extension\Chatbot\ChatService;
use MediaWiki\MediaWikiServices;

return [
    'ChatbotService' => static function ( MediaWikiServices $services ): ChatService {
        return new ChatService(
            $services->getHttpRequestFactory(),
            $services->getMainConfig(),
            $services->getLoggerFactory()->get( 'Chatbot' )
        );
    },
];
