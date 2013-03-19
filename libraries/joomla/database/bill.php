<?php

class TableBill extends JTable
{

	var $id = null;
	
	var $tcreate = null;
	
	var $tstart = null;
	
	var $tend = null;
	
	var $tupdate = null;
	
	var $adid= null;
	
	var $profileid = null;
	
	var $userid = null;
	
    var $price= null;
	
	var $status = null;
	
 	var $md5= null;
	
	var $ordering = null;
	
	var $published = null;

	
	function TableBill(& $db) {
		parent::__construct('#__bill', 'id', $db);
	}
}