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
        $out->setPageTitle( $this->msg( 'chatbot-special-title' ) );

        $out->addHTML( $this->buildScaffold() );
    }

    private function buildScaffold(): string {
        $promptLabel = $this->msg( 'chatbot-prompt-label' )->escaped();
        $placeholder = $this->msg( 'chatbot-prompt-placeholder' )->escaped();
        $sendLabel = $this->msg( 'chatbot-send-button' )->escaped();
        $outputLabel = $this->msg( 'chatbot-output-label' )->escaped();

        return Html::rawElement( 'div', [ 'class' => 'chatbot-container' ],
            Html::element( 'label', [ 'for' => 'chatbot-input' ], $promptLabel ) .
            Html::element( 'textarea', [
                'id' => 'chatbot-input',
                'placeholder' => $placeholder,
                'rows' => 4
            ], '' ) .
            Html::element( 'button', [ 'class' => 'chatbot-send', 'type' => 'button' ], $sendLabel ) .
            Html::rawElement( 'div', [ 'class' => 'chatbot-output' ],
                Html::element( 'div', [ 'class' => 'chatbot-output__label' ], $outputLabel ) .
                Html::element( 'div', [ 'class' => 'chatbot-output__log' ], '' )
            )
        );
    }
}
