<?php
defined( '_JEXEC' ) or die( 'Restricted access' );


class BillsControllerBill extends BillsController
{

	function __construct()
	{
		parent::__construct();


		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'publish'    , 	'publish' );
		$this->registerTask( 'unpublish'  , 	'publish' );
	}


	function mb_ucfirst($str, $enc = 'utf-8') { 
    		return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc); 
    
	}
	function edit()
	{
		JRequest::setVar( 'view', 'bill' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}


	
	function saveorder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('bill');
		$model->saveorder($cid, $order);

		$msg = JText::_( 'Новый порядок сохранен' );
		$this->setRedirect( 'index.php?option=com_bill', $msg );
	}
	

	function orderup()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('bill');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_bill');
	}

	function orderdown()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('bill');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_bill');
	}
	
	
//Функция публикации.
function publish()
    {
	JRequest::checkToken() or jexit( 'Invalid Token' );
    $data = JRequest::getVar('cid',  0, '', 'array');
    if (empty($data)) {
	$message = JText::_('NO DATA');
           $this->setRedirect( 'index.php?option=com_bill', $msg );
        }
    $model = $this->getModel('bill');
    $table = $model->getTable();
    	if ($this->_task == 'publish')
    	{
    		if ($table->publish($data))
    		{
        	$msg = JText::_('Счет(а)').' '.JText::_('опубликован(ы)');
    		}
    		else
    		{
        	$msg = JText::_('Счет(а) не опубликован(ы)');
        	$msg .= ' ['.$table->getError().']';
    		}
     		}
    	if ($this->_task == 'unpublish')
    	{
    		if ($table->publish($data,0))
    		{
    			if ($data>1)
        	$msg = JText::_('Счет(а)').' '.JText::_('снят(ы) с публикации');
    		}
    		else
    		{
        	$msg = JText::_('Счет(а) не снят(ы) с  публикации');
        	$msg .= ' ['.$table->getError().']';
    		}
    	}
    $this->setRedirect( 'index.php?option=com_bill', $msg );
 	}
}