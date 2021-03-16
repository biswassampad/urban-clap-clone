/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    
    //config.removePlugins = 'elementspath,save,showblocks,smiley,templates,iframe,pagebreak,preview,flash,language,print,newpage,find,selectall';
    
    config.toolbar = [
                      { name: 'document', items: [ 'Source' ] },
                      { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                      { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                      { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
                      { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Language', ] },
                      { name: 'links', items: [ 'Link', 'Unlink' ] },
                      { name: 'insert', items: [ 'Image', 'Embed' , 'Table', 'HorizontalRule', 'SpecialChar','BootstrapTabs', 'PageBreak', 'Iframe' ] },
                      { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                      { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                      { name: 'AddLayout', items: [ 'AddLayout' ] }
                      ];

    // hide dropdown menu items of spell checker
    CKEDITOR.config.scayt_uiTabs = '0,0,0';
    
    // show following styles only
    CKEDITOR.stylesSet.add( 'my_styles', [
                                          // Block-level styles
                                          { name: 'Italic Title', element: 'h2', styles: { 'font-style': 'Italic' } },
                                          { name: 'Subtitle' , element: 'h3', styles: { 'color': 'Gray' } },
                                          
                                          // Inline styles
                                          { name: 'Cited Work', element: 'cite' },
                                          { name: 'Inline Quotation', element: 'q' }
                                          ] );
    config.stylesSet = 'my_styles';
    
    //show following paragraph format only
    CKEDITOR.config.format_tags = 'p;h1;h2;h3;h4;h5;h6';


};

