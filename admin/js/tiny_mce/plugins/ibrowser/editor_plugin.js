/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright  2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.IBrowserPlugin', {
		init : function(ed, url) {

			// load common script
			tinymce.ScriptLoader.load(url + '/interface/common.js');
			
			// Register commands
			ed.addCommand('mceIBrowser', function() {
				ed.windowManager.open({
                                        file : url + '/ibrowser.php',
                                        width : 320 + ed.getLang('mceIBrowser.delta_width', 0),
                                        height : 120 + ed.getLang('mceIBrowser.delta_height', 0),
                                        inline : 1
                                }, {
                                        plugin_url : url, // Plugin absolute URL
                                        some_custom_arg : 'custom arg' // Custom argument
                                });
				/*
				var e = ed.selection.getNode();

				// Internal image object like a flash placeholder
				if (ed.dom.getAttrib(e, 'class').indexOf('mceItem') != -1)
					return;
					// NJW MOD - Fix for FF3 Compatibility
				var ib = iBrowser_GetIb();
				ib.isMSIE  = tinymce.isIE;
				ib.isGecko = tinymce.isGecko;
				ib.oEditor = ed; 
				ib.editor  = ed;
				ib.selectedElement = e;					
				ib.baseURL = url + '/ibrowser.php';
				iBrowser_open();
				*/
			});

			// Register buttons
			ed.addButton('ibrowser', {
				title : 'iBrowser',
				cmd : 	'mceIBrowser',
				image: 	url + '/interface/images/tinyMCE/ibrowser.gif'
			});
			
			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('ibrowser', n.nodeName == 'IMG');
			});
		},

		getInfo : function() {
			return {
				longname : 	'iBrowser',
				author : 	'net4visions.com',
				authorurl : 'http://net4visions.com',
				infourl : 	'http://net4visions.com',
				version : 	'1.3.9'
			};
		}
	});
	
	// Register plugin
	tinymce.PluginManager.add('ibrowser', tinymce.plugins.IBrowserPlugin);
})();	