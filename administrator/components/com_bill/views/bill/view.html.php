<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );
class BillsViewBill extends JView
{
	function display($tpl = null)
	{
		$hello		=& $this->get('Data');
		$isNew		= ($hello->id < 1);
		$profiles   =& $this->get('Profiles');
		$users   =& $this->get('Users');
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Счет' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel('cancel', 'Close' );
		}
		$this->assignRef('hello',		$hello);
		$this->assignRef('profiles',		$profiles);
		$this->assignRef('users',		$users);
		parent::display($tpl);
	}
}