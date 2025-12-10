<?php

namespace MediaWiki\Extension\Chatbot;

use Html;
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
        return '<div class="chatbot-container">'
            . '<label for="chatbot-input">' . $promptLabel . '</label>'
            . '<textarea id="chatbot-input" placeholder="' . $placeholder . '" rows="4"></textarea>'
            . '<button class="chatbot-send" type="button">' . $sendLabel . '</button>'
            . '<div class="chatbot-output">'
            .   '<div class="chatbot-output__label">' . $outputLabel . '</div>'
            .   '<div class="chatbot-output__log"></div>'
            . '</div>'
            . '</div>';
    }
}
