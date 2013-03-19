/**
 * @version		$Id: k2.mootools.js 478 2010-06-16 16:11:42Z joomlaworks $
 * @package		K2Import
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * 				modified by Artur Neumann http://www.individual-it.net for the use with K2Import * 
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks, a business unit of Nuevvo Webware Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

window.addEvent('domready', function(){

	
	// File browser

	
	$$('a.importFile').addEvent('click', function(e){
		e = new Event(e).stop();
		parent.$$('input[name=existing_file]').setProperty('value', this.getProperty('href'));
		parent.SqueezeBox.close();
	});
	
	
});
