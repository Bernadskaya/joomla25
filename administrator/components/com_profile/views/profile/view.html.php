<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );
class ProfilesViewProfile extends JView
{
	function display($tpl = null)
	{
		$hello		=& $this->get('Data');
		$isNew		= ($hello->id < 1);
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Профиль' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {

			JToolBarHelper::cancel('cancel', 'Close' );
		}
		$this->assignRef('hello',		$hello);
		parent::display($tpl);
	}
}