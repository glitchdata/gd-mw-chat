( function ( mw, $ ) {
    const api = new mw.Api();
    let root, panel, toggle, closeBtn, textarea, sendButton, log;

    function appendMessage( type, text ) {
        if ( !log ) {
            return;
        }
        const bubble = $( '<div>' ).addClass( 'chatbot-msg chatbot-msg--' + type ).text( text );
        log.append( bubble );
        log.scrollTop( log.prop( 'scrollHeight' ) );
    }

    function setOpen( open ) {
        if ( !root || !panel || !toggle ) {
            return;
        }
        root.toggleClass( 'is-open', open );
        panel.attr( 'aria-hidden', open ? 'false' : 'true' );
        toggle.attr( 'aria-expanded', open ? 'true' : 'false' );
        if ( open && textarea ) {
            textarea.trigger( 'focus' );
        }
    }

    function sendPrompt() {
        if ( !textarea ) {
            return;
        }
        const message = textarea.val().trim();
        if ( !message ) {
            return;
        }

        appendMessage( 'user', message );
        textarea.val( '' );

        if ( sendButton ) {
            sendButton.prop( 'disabled', true ).text( mw.msg( 'chatbot-loading' ) );
        }

        api.post( {
            action: 'chatbot',
            message
        } ).done( function ( data ) {
            const reply = data && data.chatbot && data.chatbot.reply ? data.chatbot.reply : mw.msg( 'chatbot-error' );
            appendMessage( 'assistant', reply );
        } ).fail( function () {
            appendMessage( 'assistant', mw.msg( 'chatbot-error' ) );
        } ).always( function () {
            if ( sendButton ) {
                sendButton.prop( 'disabled', false ).text( mw.msg( 'chatbot-send-button' ) );
            }
        } );
    }

    $( function () {
        root = $( '.chatbot-widget' );
        if ( !root.length ) {
            return;
        }
        panel = root.find( '.chatbot-panel' );
        toggle = root.find( '.chatbot-toggle' );
        closeBtn = root.find( '.chatbot-close' );
        textarea = root.find( '.chatbot-input' );
        sendButton = root.find( '.chatbot-send' );
        log = root.find( '.chatbot-output__log' );

        toggle.on( 'click', function () {
            setOpen( !root.hasClass( 'is-open' ) );
        } );

        closeBtn.on( 'click', function () {
            setOpen( false );
        } );

        sendButton.on( 'click', sendPrompt );

        textarea.on( 'keydown', function ( e ) {
            if ( e.key === 'Enter' && !e.shiftKey ) {
                e.preventDefault();
                sendPrompt();
            }
        } );

        // Start closed; user opens with toggle.
        setOpen( false );
    } );
}( mediaWiki, jQuery ) );
