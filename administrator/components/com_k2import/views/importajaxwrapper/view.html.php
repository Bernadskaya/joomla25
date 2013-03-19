<?php
 
 /**
 * K2import View
 * 
 * @package    K2import
 * @link http://www.individual-it.net
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );



class K2importViewImportAjaxWrapper extends JView
{

	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'K2 Import Tool' ). ' - ' . JText::_( 'importing' ), 'generic.png' );
	
		
		parent::display($tpl);
	}
}