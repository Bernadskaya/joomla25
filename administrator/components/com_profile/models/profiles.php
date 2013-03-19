<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );


class ProfilesModelProfiles extends JModel
{

	var $_total = null;
    var $_pagination = null;

function __construct()
	{
		parent::__construct();
		global $app, $option;
 
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
	}

	 function getData() 
  {

        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit')); 
        }
        return $this->_data;
  }

    function getTotal()
  {

        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);    
        }
        return $this->_total;
  }

   function getPagination()
  {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
  }

  function _buildQuery()
     {
	 $db = $this->getDBO();
$query = "SELECT * FROM ". $db->nameQuote('#__profile')
. $this->_buildQueryOrderBy();
return $query;
     }

	 function _buildQueryOrderBy()
{
global $option, $app;
$orders = array('Name', 'ordering', 'Published', 'id');
$filter_order = $app->getUserStateFromRequest($option.'filter_order', 'filter_order', 'published');
 $filter_order_Dir = strtoupper($app->getUserStateFromRequest('dbconsultor.filter_order_Dir', 'filter_order_Dir', 'ASC'));
 if ($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
{
$filter_order_Dir = 'ASC';
}
if (!in_array($filter_order, $orders))
{
$filter_order = 'id';
 }
 return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
 }
}