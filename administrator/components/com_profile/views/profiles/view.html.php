<style type="text/css">
.icon-48-profile       {background-image: url(../administrator/components/com_profile/images/logo.bmp); }
</style>
<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );
class ProfilesViewProfiles extends JView
{
	function display($tpl = null)
	{	
		JToolBarHelper::title(   JText::_( 'Профили' ), 'profile' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper:: publishList (); 
		JToolBarHelper:: unpublishList ();
		$items		= & $this->get( 'Data');
		$this->assignRef('items',		$items);
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		global $option;
		$apl = JFactory::getApplication();
		$filter_order = $apl->getUserStateFromRequest(
		$option.'filter_order', 'filter_order', 'published');
		$filter_order_Dir = JString::strtoupper($apl->getUserStateFromRequest(
		$option.'filter_order_Dir','filter_order_Dir', 'ASC'));
		$lists = array();
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		$this->assignRef('lists', $lists);
		parent::display($tpl);
	}
}