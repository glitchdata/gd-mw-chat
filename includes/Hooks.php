<?php

namespace MediaWiki\Extension\Chatbot;

use OutputPage;
use Skin;

class Hooks {
    /**
     * Inject the chatbot widget on every page and load its module.
     */
    public static function onBeforePageDisplay( OutputPage $out, Skin $skin ): void {
        $title = $out->getTitle();
        // Avoid duplicating the widget when already on Special:Chatbot.
        if ( $title && $title->isSpecial( 'Chatbot' ) ) {
            return;
        }

        $out->addModules( [ 'ext.chatbot' ] );
        // Append widget container markup near the end of the page body.
        $out->addHTML( ChatbotWidget::render() );
    }
}
