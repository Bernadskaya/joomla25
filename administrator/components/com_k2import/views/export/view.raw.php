<?php


// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );


class K2importViewExport extends JView
{

	function display($tpl = null)
	{
		
		$this->assignRef( 'max_attachments', $this->max_attachments );
		$this->assignRef( 'all_extrafields', $this->all_extrafields );
		$this->assignRef( 'items', $this->items );

		parent::display($tpl);
	}
}



