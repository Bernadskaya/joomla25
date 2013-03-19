<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class BillsModelBill extends JModel
{

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getUsers()
	{

//		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__users '.
					'  WHERE block = 0';
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObjectList();
//		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->name = null;
			$this->_data->published = null;
		}
		return $this->_data;
	}
	
		function &getProfiles()
	{

//		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__profile '.
					'  WHERE published = 1';
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObjectList();
//		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->name = null;
			$this->_data->published = null;
		}
		return $this->_data;
	}



	function &getData()
	{

		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__bill '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->name = null;
			$this->_data->published = null;
		}
		return $this->_data;
	}

		function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();
		$groupings = array();

		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			$groupings[] = $row->catid;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('catid = '.(int) $group);
		}

		return true;
	}


	function store()
	{	
		$row =& $this->getTable();
		$data = JRequest::get( 'post' );
		//Редактор joomla запись данных в бд
		//$data['desc']=JRequest::getVar( 'desc', '', 'post', 'string', JREQUEST_ALLOWHTML );

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}

	function move($direction)
	{
		$row =& $this->getTable();
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->move( $direction, ' catid = '.(int) $row->catid.' AND published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}
	
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

}