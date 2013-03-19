<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class ProfilesControllerProfile extends ProfilesController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'publish'    , 	'publish' );
		$this->registerTask( 'unpublish'  , 	'publish' );
	}

	function edit()
	{
		JRequest::setVar( 'view', 'profile' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	function save()
	{
		$model = $this->getModel('profile');
		$_POST['ut']=date('Y-m-d H:i:s',time());
		if(isset($_FILES['image'])&&$_FILES['image']['error']==0)
			{
				if($_POST['old_image']!='')
					{
						unlink($_SERVER['DOCUMENT_ROOT'].$_POST['old_image']);
					}
			copy($_FILES['image']['tmp_name'] , $_SERVER['DOCUMENT_ROOT'].'/administrator/components/com_profile/images/'.$_POST['id'].'_'.$_FILES['image']['name']);
			$_POST['image']='/administrator/components/com_profile/images/'.$_POST['id'].'_'.$_FILES['image']['name'];
			}
		if ($model->store($post)) {
			$msg = JText::_( 'Профиль сохранен' );
		} else {
			$msg = JText::_( 'Ошибка сохранения профиля' );
		}
		$link = 'index.php?option=com_profile';
		$this->setRedirect($link, $msg);
	}

	function apply()
	{
		$model = $this->getModel('profile');
		$_POST['ut']=date('Y-m-d H:i:s',time());
		if(isset($_FILES['image'])&&$_FILES['image']['error']==0)
			{
				if($_POST['old_image']!='')
					{
						unlink($_SERVER['DOCUMENT_ROOT'].$_POST['old_image']);
					}
			copy($_FILES['image']['tmp_name'] , $_SERVER['DOCUMENT_ROOT'].'/administrator/components/com_profile/images/'.$_POST['id'].'_'.$_FILES['image']['name']);
			$_POST['image']='/administrator/components/com_profile/images/'.$_POST['id'].'_'.$_FILES['image']['name'];
			}
		if ($model->store($post)) {
			$msg = JText::_( 'Профиль сохранен' );
		} else {
			$msg = JText::_( 'Ошибка сохранения профиля' );
		}

$id = JRequest::getVar('id');
if(!$id)
	$id = $model->_db->insertid();
          $link = 'index.php?option=com_profile&controller=profile&task=edit&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}	

	function remove()
	{
		$model = $this->getModel('profile');
		if(!$model->delete()) {
			$msg = JText::_( 'Ошибка! Один или несколько профилей могли быть не удалены' );
		} else {
			$msg = JText::_( 'Профиль(и) удален(ы)' );
		}
		$this->setRedirect( 'index.php?option=com_profile', $msg );
	}

	function cancel()
	{
		$msg = JText::_( 'Операция отменена' );
		$this->setRedirect( 'index.php?option=com_profile', $msg );
	}

	function saveorder()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('profile');
		$model->saveorder($cid, $order);

		$msg = JText::_( 'Новый порядок сохранен' );
		$this->setRedirect( 'index.php?option=com_profile', $msg );
	}
	
	function orderup()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('profile');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_profile');
	}

	function orderdown()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('profile');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_profile');
	}
	
	
function publish()
    {
	JRequest::checkToken() or jexit( 'Invalid Token' );
    $data = JRequest::getVar('cid',  0, '', 'array');
    if (empty($data)) {
	$message = JText::_('NO DATA');
           $this->setRedirect( 'index.php?option=com_profile', $msg );
        }
    $model = $this->getModel('profile');
    $table = $model->getTable();
    	if ($this->_task == 'publish')
    	{
    		if ($table->publish($data))
    		{
        	$msg = JText::_('Профиль(и)').' '.JText::_('опубликован(ы)');
    		}
    		else
    		{
        	$msg = JText::_('Профиль не опубликован');
        	$msg .= ' ['.$table->getError().']';
    		}
     		}
    	if ($this->_task == 'unpublish')
    	{
    		if ($table->publish($data,0))
    		{
    			if ($data>1)
        	$msg = JText::_('Профиль(и)').' '.JText::_('снят(ы) с публикации');
    		}
    		else
    		{
        	$msg = JText::_('Профиль(и) не снят(ы) с публикации');
        	$msg .= ' ['.$table->getError().']';
    		}
    	}
    $this->setRedirect( 'index.php?option=com_profile', $msg );
 	}
}