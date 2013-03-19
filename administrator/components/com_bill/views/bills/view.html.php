<style type="text/css">
.icon-48-bill       {background-image: url(../administrator/components/com_bill/images/logo.bmp); }
</style>
<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );
class BillsViewBills extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Счета' ), 'Bill' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper:: publishList (); 
		JToolBarHelper:: unpublishList ();
		$act=Array();
		$items		= & $this->get( 'Data');
		$users		= & $this->get( 'users');
		foreach($users as $u) $act[$u->id]=$u->name;
		$users=$act;
		$profiles		= & $this->get( 'profiles');
		foreach($profiles as $p) $act[$p->id]=$p->name;
		$profiles=$act;
		$this->assignRef('items',		$items);
		$this->assignRef('users',		$users);
		$this->assignRef('profiles',		$profiles);
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