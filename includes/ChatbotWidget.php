<?php

namespace MediaWiki\Extension\Chatbot;

class ChatbotWidget {
    /**
     * Render the floating chatbot widget markup.
     */
    public static function render(): string {
        $title = htmlspecialchars( wfMessage( 'chatbot-special-title' )->text(), ENT_QUOTES );
        $promptLabel = htmlspecialchars( wfMessage( 'chatbot-prompt-label' )->text(), ENT_QUOTES );
        $placeholder = htmlspecialchars( wfMessage( 'chatbot-prompt-placeholder' )->text(), ENT_QUOTES );
        $sendLabel = htmlspecialchars( wfMessage( 'chatbot-send-button' )->text(), ENT_QUOTES );
        $outputLabel = htmlspecialchars( wfMessage( 'chatbot-output-label' )->text(), ENT_QUOTES );

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
