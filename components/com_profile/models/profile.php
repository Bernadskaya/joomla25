<?php
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.model');
class ProfileModelProfile extends JModel
{
function getProfile()
	{
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__profile WHERE published=1 ORDER BY ordering ASC';
	$db->setQuery($query);
	$row = $db->loadObjectlist();
return $row;		
	}	
}
?>