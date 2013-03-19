<?php

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.model');


class BillModelBill extends JModel
{

function getBill()
	{

	$row = array();
	$db = JFactory::getDBO();
	$user = & JFactory::getUser();

	if ($user->get('guest') != '1')
		{
		$query = 'SELECT * FROM #__bill WHERE userid='.$user->id.' and published=1 ORDER BY tcreate DESC';
		$db->setQuery($query);
		$row = $db->loadObjectlist();
		}
return $row;	
	
	}
	
	function getTitle($id)
	{

	$row = array();
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__k2_items WHERE id='.$id;
	$db->setQuery($query);
	$row = $db->loadObject();
	return $row;	
	
	}
	function getProfile($id)
	{
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__profile WHERE id='.$id;
	$db->setQuery($query);
	$row = $db->loadObject();
	return $row;	
	}
	
}
?>