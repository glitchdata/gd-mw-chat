<?php

use MediaWiki\MediaWikiServices;

// This file exists to allow wfLoadExtension( 'Chatbot' ).
// The bulk of the implementation lives in extension.json and the includes/ directory.

// phpcs:ignore MediaWiki.Files.ClassMatchesFilename.NotMatch
class Chatbot {
    /**
     * Convenience helper to access the chatbot service.
     */
    public static function getChatService(): MediaWiki\Extension\Chatbot\ChatService {
        return MediaWikiServices::getInstance()->getService( 'ChatbotService' );
    }
}
