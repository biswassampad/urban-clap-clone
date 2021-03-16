/**
 * Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

/* exported initSample */

if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
	CKEDITOR.tools.enableHtml5Elements( document );

// The trick to keep the editor in the sample quite small
// unless user specified own height.
CKEDITOR.config.height = 300;
CKEDITOR.config.width = 'auto';
CKEDITOR.config.extraPlugins = 'imageuploader,layoutmanager,basewidget,bootstrapTabs,embedbase,embed';
CKEDITOR.config.layoutmanager_loadbootstrap  = true;
CKEDITOR.on('instanceReady', function(ev) {
    loadBootstrap(ev);
});

    function loadBootstrap(event) {
        
        if (event.name == 'mode' && event.editor.mode == 'source')
            return; // Skip loading jQuery and Bootstrap when switching to source mode.
    
        var jQueryScriptTag = document.createElement('script');
        var bootstrapScriptTag = document.createElement('script');
    
        jQueryScriptTag.src = 'https://code.jquery.com/jquery-1.11.3.min.js';
        bootstrapScriptTag.src = 'assets/global/plugins/bootstrap/js/bootstrap.min_3.6.js';
    
        var editorHead = event.editor.document.$.head;
    
        editorHead.appendChild(jQueryScriptTag);
        jQueryScriptTag.onload = function() {
          editorHead.appendChild(bootstrapScriptTag);
        };
    }


var initSample = ( function() {
	var wysiwygareaAvailable = isWysiwygareaAvailable(),
		isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );

	return function() {
		var editorElement = CKEDITOR.document.getById( 'page_media' );

		// :(((
		if ( isBBCodeBuiltIn ) {
			editorElement.setHtml("");
		}

		// Depending on the wysiwygare plugin availability initialize classic or inline editor.
		if ( wysiwygareaAvailable ) {
			CKEDITOR.replace( 'page_media');
		} else {
			editorElement.setAttribute( 'contenteditable', 'true' );
			CKEDITOR.inline( 'page_media' );

			// TODO we can consider displaying some info box that
			// without wysiwygarea the classic editor may not work.
		}
	};

	function isWysiwygareaAvailable() {
		// If in development mode, then the wysiwygarea must be available.
		// Split REV into two strings so builder does not replace it :D.
		if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
			return true;
		}

		return !!CKEDITOR.plugins.get( 'wysiwygarea' );
	}
} )();

