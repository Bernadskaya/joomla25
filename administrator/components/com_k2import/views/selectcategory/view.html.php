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



class K2importViewSelectcategory extends JView
{
	/**
	 * K2importViewSelectcategory view display method
	 * The view for selecting the Main Category for the import and to configure the import
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'K2 Import Tool' ) . ' - ' . JText::_( 'configure the import' ), 'generic.png' );
		
		

       // $data =& $this->get( 'Data');
 		$model =& $this->getModel();
		


		$modus= JRequest::getVar( 'modus', '', 'get', 'string' );
		
		if ($modus=='archive')
		{
			$mainframe = JFactory::getApplication();
			$file = JFolder::files($mainframe->getCfg('tmp_path').DS.'k2_import', '.csv');
			$this->assignRef( 'file', $file );
			$this->assignRef( 'modus', $modus );			
		}
		else 
		{
			$file= JRequest::getVar( 'file', '', 'get', 'string' );
			$file=JFile::makeSafe($file);
			$this->assignRef( 'file', $file );
		}
			
	
			
		
		$k2categories = $model->getK2categories();
		$k2extrafieldgroups=$model->getK2extrafieldgroups();
		$this->assignRef( 'k2extrafieldgroups', $k2extrafieldgroups );	
		$this->assignRef( 'k2categories', $k2categories );
				
		$document = & JFactory::getDocument();
		$document->addStyleSheet('components/com_k2import/css/k2import.css');
		if(version_compare( JVERSION, '1.6.0', 'ge' )) {
			$document->addScript('components/com_k2import/js/k2import_1_6.js');
		} else {
			$document->addScript('components/com_k2import/js/k2import_1_5.js');
				
		}
		
	


		parent::display($tpl);
	}
}