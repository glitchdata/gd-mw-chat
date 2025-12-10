<?php

namespace MediaWiki\Extension\Chatbot;

use SpecialPage;

class SpecialChatbot extends SpecialPage {
    public function __construct() {
        parent::__construct( 'Chatbot' );
    }

    public function execute( $subPage ): void {
        $this->setHeaders();
        $this->checkPermissions();

        $out = $this->getOutput();
        $out->addModules( 'ext.chatbot' );
        // setPageTitle expects a plain string, not a Message object.
        $out->setPageTitle( $this->msg( 'chatbot-special-title' )->text() );

        $out->addHTML( $this->buildScaffold() );
    }

    private function buildScaffold(): string {
        $promptLabel = htmlspecialchars( $this->msg( 'chatbot-prompt-label' )->text(), ENT_QUOTES );
        $placeholder = htmlspecialchars( $this->msg( 'chatbot-prompt-placeholder' )->text(), ENT_QUOTES );
        $sendLabel = htmlspecialchars( $this->msg( 'chatbot-send-button' )->text(), ENT_QUOTES );
        $outputLabel = htmlspecialchars( $this->msg( 'chatbot-output-label' )->text(), ENT_QUOTES );

        // Build minimal HTML without relying on Html helper to avoid class resolution issues.
            $title = htmlspecialchars( $this->msg( 'chatbot-special-title' )->text(), ENT_QUOTES );

            // Floating chat bubble with toggle.
            return '<div class="chatbot-widget" aria-live="polite">'
                . '<button class="chatbot-toggle" type="button" aria-expanded="false">' . $title . '</button>'
                . '<div class="chatbot-panel" aria-hidden="true">'
                .   '<div class="chatbot-panel__header">'
                .     '<div class="chatbot-title">' . $title . '</div>'
                .     '<button class="chatbot-close" type="button" aria-label="Close">x</button>'
                .   '</div>'
                .   '<div class="chatbot-output">'
                .     '<div class="chatbot-output__label">' . $outputLabel . '</div>'
                .     '<div class="chatbot-output__log"></div>'
                .   '</div>'
                .   '<div class="chatbot-inputbar">'
                .     '<label class="chatbot-input__label" for="chatbot-input">' . $promptLabel . '</label>'
                .     '<textarea class="chatbot-input" id="chatbot-input" placeholder="' . $placeholder . '" rows="3"></textarea>'
                .     '<button class="chatbot-send" type="button">' . $sendLabel . '</button>'
                .   '</div>'
                . '</div>'
                . '</div>';
    }
}
