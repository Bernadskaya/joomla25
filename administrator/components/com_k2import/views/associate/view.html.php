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



class K2importViewAssociate extends JView
{

	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'K2 Import Tool' ). ' - ' . JText::_( 'associate the fields' ), 'generic.png' );
	

       // $data =& $this->get( 'Data');
 		$model =& $this->getModel();
		

		$file        = JRequest::getVar( 'file', '', 'post', 'string' );

		$file=JFile::makeSafe($file);
		
		//do we use a ZIP archive?
		$modus        = JRequest::getVar( 'modus', '', 'post', 'string' );
		$csv_count_rows_to_do = JRequest::getVar( 'csv_count_rows_to_do', '10', 'post', 'int' );
		$max_execution_time = JRequest::getVar( 'max_execution_time', '', 'post', 'int' );
		$start_row_num = JRequest::getVar( 'start_row_num', '1', 'post', 'int' );
		
		$k2category        = JRequest::getVar( 'k2category', '', 'post', 'string' );
		$in_charset        = substr(JRequest::getVar( 'in_charset', '', 'post' ),0,20);				
		$out_charset        = substr(JRequest::getVar( 'out_charset', '', 'post' ),0,20);		
		$overwrite        = JRequest::getVar( 'overwrite', 'NO', 'post','string' );	
		$ignore_level        = JRequest::getVar( 'ignore_level', 'NO', 'post','string' );
		$should_we_import_the_id        = JRequest::getVar( 'should_we_import_the_id', 'NO', 'post','string' );
		
		if ($ignore_level!='YES')
			$ignore_level=='NO';	
			
		if ($should_we_import_the_id!='YES')
			$should_we_import_the_id=='NO';			
			
			
		if ($k2category=='take_from_csv')
			$k2extrafieldgroup        = JRequest::getVar( 'k2extrafieldgroup', '', 'post', 'int' );	
		else 	
			$k2extrafieldgroup='';
		
		if ($modus=='archive')
		{
			$data = $model->getHeader('k2_import' . DS .$file,$in_charset,$out_charset);
		}
		else
		{
			$data = $model->getHeader($file,$in_charset,$out_charset);
		}
		
		

		
		$k2fields = $model->getK2fields($k2category,$k2extrafieldgroup);	
		$this->assignRef( 'csv_headers', $data );
		$this->assignRef( 'k2fields', $k2fields );
		$this->assignRef( 'file', $file );		
		$this->assignRef( 'modus', $modus );		
		$this->assignRef( 'csv_count_rows_to_do', $csv_count_rows_to_do );
		$this->assignRef( 'max_execution_time', $max_execution_time );
		
		$this->assignRef( 'k2category', $k2category );
		$this->assignRef( 'in_charset', $in_charset );
		$this->assignRef( 'out_charset', $out_charset );
		$this->assignRef( 'overwrite', $overwrite );
		$this->assignRef( 'ignore_level', $ignore_level );
		$this->assignRef( 'should_we_import_the_id', $should_we_import_the_id );
		
		$this->assignRef( 'k2extrafieldgroup', $k2extrafieldgroup );
		$this->assignRef( 'start_row_num', $start_row_num );

		
		$document = & JFactory::getDocument();
		$document->addStyleSheet('components/com_k2import/css/k2import.css');
		$document->addScript('components/com_k2import/js/k2import.js');

		if(version_compare( JVERSION, '1.6.0', 'ge' )) {
			$document->addScript('components/com_k2import/js/k2import_1_6.js');
		} else {
			$document->addScript('components/com_k2import/js/k2import_1_5.js');
		
		}
		
		$attachment_js="window.addEvent('domready', function(){";
  

		$add_attachments=0;
		foreach ($data as $column_header)
		{
			if (preg_match("/attachment\d+$/", strtolower($column_header))>0)
			{
				$add_attachments++;
				
			}
			
		}

		$attachment_js.="
	for (i=1;i<".$add_attachments.";i++)
	{
	add_more_attachments();
	}
	
	});";
		$document->addScriptDeclaration($attachment_js);
		parent::display($tpl);
	}
}