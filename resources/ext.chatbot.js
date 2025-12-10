( function ( mw, $ ) {
    const api = new mw.Api();

    function appendMessage( type, text ) {
        const log = $( '.chatbot-output__log' );
        const bubble = $( '<div>' ).addClass( 'chatbot-msg chatbot-msg--' + type ).text( text );
        log.append( bubble );
        log.scrollTop( log.prop( 'scrollHeight' ) );
    }

    function sendPrompt() {
        const textarea = $( '#chatbot-input' );
        const message = textarea.val().trim();
        if ( !message ) {
            return;
        }

        appendMessage( 'user', message );
        textarea.val( '' );

        const button = $( '.chatbot-send' );
        button.prop( 'disabled', true ).text( mw.msg( 'chatbot-loading' ) );

        api.post( {
            action: 'chatbot',
            message
        } ).done( function ( data ) {
            const reply = data && data.chatbot && data.chatbot.reply ? data.chatbot.reply : mw.msg( 'chatbot-error' );
            appendMessage( 'assistant', reply );
        } ).fail( function () {
            appendMessage( 'assistant', mw.msg( 'chatbot-error' ) );
        } ).always( function () {
            button.prop( 'disabled', false ).text( mw.msg( 'chatbot-send-button' ) );
        } );
    }

    $( function () {
        $( '.chatbot-send' ).on( 'click', sendPrompt );
    } );
}( mediaWiki, jQuery ) );
