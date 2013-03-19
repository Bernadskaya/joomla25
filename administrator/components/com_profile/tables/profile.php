<?php
class TableProfile extends JTable
{

	var $id = null;

	var $name = null;
	
	var $desc = null;
	
	var $price = null;
	
	var $image = null;
	
	var $term = null;
	
    var $published = null;
	
 	var $ordering = null;
	
	var $ct = null;
	
	var $ut = null;

	function TableProfile(& $db) {
		parent::__construct('#__profile', 'id', $db);
	}
}