<?php

namespace MediaWiki\Extension\Chatbot;

use SpecialPage;
use MediaWiki\Extension\Chatbot\ChatbotWidget;

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
        return ChatbotWidget::render();
    }
}
