<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model');

class BillsModelBills extends JModel
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

	 	function getUsers()
	{

//		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__users '.
					'  ';
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
	
		function getProfiles()
	{

//		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__profile '.
					'  ';
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
$query = "SELECT * FROM ". $db->nameQuote('#__bill')
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

function save()
	{
	global $app;
	
		
		$id = JRequest::getVar('id');
		$equal = $this->getEqual($_POST['userid'],$_POST['adid']);
		if($equal==0) 
			{
			$msg = JText::_( 'Выбранное объявление не соответствует выбранному пользователю!' );
			$link = 'index.php?option=com_bill&controller=bill&task=edit&cid[]='.$id;
			$this->setRedirect($link, $msg,'error');
			return;
			}
		$model = $this->getModel('bill');
		$profile=$this->getProfileData($_POST['profileid']);
		$item=$this->getItem($_POST['adid']);
		$one=explode(' ',$_POST['tstart']);
		$date=explode('-',$one[0]);
		$time=explode(':',$one[1]);
		$timestart=mktime ( $time[0] , $time[1] , $time[2] , $date[1] , $date[2] , $date[0]  );
		if($timestart<time()&&!JRequest::getVar('id')) 
			{
				$timestart=time();
				$_POST['tstart']=date('Y-m-d H:i:s',$timestart);
			}
		$timeend=$timestart+86400*$profile->term;
		$_POST['tend']=date('Y-m-d H:i:s',$timeend);
		$_POST['tupdate']=date('Y-m-d H:i:s',time());
		if($_POST['status']!=2) $_POST['price']=$profile->price;
			else $_POST['price']=0;
		if ($model->store($post)) {
			if($_POST['status']!=1&&!JRequest::getVar('id'))
			{
			$_user =& JFactory::getUser($_POST['userid']);
			$mailfrom 		= $app->getCfg( 'mailfrom' );
			$fromname 		= $app->getCfg( 'fromname' );
			$recipient = $_user->email;
			$MD5=MD5('Zazki:'.$profile->price.':'.$model->_db->insertid().':123456:shp_adid='.$item->id.':shp_profileid='.$profile->id);
			if($_POST['status']==2) 
				{
				// Ай на нэ на нэ
				$body   = '<p>'.$_user->name.', администрации Цацки-Пецки понравилось украшение "'.$this->mb_ucfirst($item->title).'"</p>'
							.'<p>С '.$_POST['tstart'].' по '.$_POST['tend'].' украшение "'.$this->mb_ucfirst($item->title).'" занимает позицию '.$profile->name.' бесплатно! Надеемся, это поможет ему найти своих хозяев.</p>'
							.'<p><a href="'.$_SERVER['HTTP_HOST'].'/component/profile">Продать быстрее другие украшения</a></p>'
							.'<p>Администрация сервиса по продаже авторских украшений Цацки-Пецки.ру</p>'
							.'<p>Получайте больше бонусов! Узнайте подробнее по адресу: <a href="'.$_SERVER['HTTP_HOST'].'/service/bonus">http://zazki-pezki.ru/service/bonus</a></p>';
				$subject='Бонус для украшения "'.$this->mb_ucfirst($item->title).'"';
				}
			if($_POST['status']==0)
				{
				$body   = '<p>'.$_user->name.'!</p>'
				.'<p>Для размещения украшения '.$this->mb_ucfirst($item->title).' на позиции '.$profile->name.' сформирован счет №'.$model->_db->insertid().', стоимость '.$profile->price.'. Как только счет будет оплачен, анонс украшения появится на выбранной позиции и покупатели будут видеть его чаще.</p>'
				.'<p><a href="https://merchant.roboxchange.com/Index.aspx?MrchLogin=Zazki&OutSum='.$profile->price.'&InvId='.$bill->id.'&Desc='.strip_tags($profile->desc).'&SignatureValue='.$MD5.'&Culture=ru&shp_adid='.$item->id.'&shp_profileid='.$profile->id.'">Оплатить счет</a></p>'
				.'<p>Отслеживать статус счета вы можете в разделе «Мои счета»</p>'
				.'<p>Администрация сервиса по продаже авторских украшений Цацки-Пецки.ру</p>';
				$subject='Сформирован счет  №'.$model->_db->insertid();
				}
			$this->setmd5($MD5,$model->_db->insertid());
			JUtility::sendMail($mailfrom, $fromname, $recipient, $subject, $body, $mode=1, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null);
			}
			$msg = JText::_( 'Счет сохранен' );
		} else {
			$msg = JText::_( 'Ошибка сохранения счета' );
		}

//путь
		$link = 'index.php?option=com_bill';
		$this->setRedirect($link, $msg);
	}

	function getProfileData($id)
	{
			$db = & JFactory::getDBO();
			$query = ' SELECT * FROM #__profile '.
					'  WHERE id = '.$id;
			$db->setQuery( $query );
			$data = $db->loadObject();
		return $data;
	}
	
	
	function getEqual($userid,$adid)
	{
			$db = & JFactory::getDBO();
			$query = ' SELECT COUNT(*) FROM #__k2_items '.
					'  WHERE id = '.$adid.' and created_by = '.$userid.'';
			$db->setQuery( $query );
			$count = $db->loadResult();
		return $count;
	}
	
	function setmd5($MD5,$id)
	{
			$db = & JFactory::getDBO();
			$query = 'UPDATE #__bill SET MD5='.$MD5.' WHERE id='.$id.'';
			$db->setQuery($query);
			$db->query();
		return $db->query();
	}
	
	function getItem($id)
	{

	$row = array();
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__k2_items WHERE id='.$id;
	$db->setQuery($query);
	$row = $db->loadObject();
	return $row;	
	
	}
	

	function apply()
	{
	global $app;
	
		$id = JRequest::getVar('id');
		$equal = $this->getEqual($_POST['userid'],$_POST['adid']);
		if($equal==0) 
			{
			$msg = JText::_( 'Выбранное объявление не соответствует выбранному пользователю!' );
			$link = 'index.php?option=com_bill&controller=bill&task=edit&cid[]='.$id;
			$this->setRedirect($link, $msg,'error');
			return;
			}
		$model = $this->getModel('bill');
		$profile=$this->getProfileData($_POST['profileid']);
		$item=$this->getItem($_POST['adid']);
		$one=explode(' ',$_POST['tstart']);
		$date=explode('-',$one[0]);
		$time=explode(':',$one[1]);
		$timestart=mktime ( $time[0] , $time[1] , $time[2] , $date[1] , $date[2] , $date[0]  );
		if($timestart<time()&&!JRequest::getVar('id')) 
			{
				$timestart=time();
				$_POST['tstart']=date('Y-m-d H:i:s',$timestart);
			}
		$timeend=$timestart+86400*$profile->term;
		$_POST['tend']=date('Y-m-d H:i:s',$timeend);
		$_POST['tupdate']=date('Y-m-d H:i:s',time());
		if($_POST['status']!=2) $_POST['price']=$profile->price;
			else $_POST['price']=0;
		if ($model->store($post)) {
		
			if($_POST['status']!=1&&!JRequest::getVar('id'))
			{
			$_user =& JFactory::getUser($_POST['userid']);
			$mailfrom 		= $app->getCfg( 'mailfrom' );
			$fromname 		= $app->getCfg( 'fromname' );
			$recipient = $_user->email;
			$MD5=MD5('Zazki:'.$profile->price.':'.$model->_db->insertid().':123456:shp_adid='.$item->id.':shp_profileid='.$profile->id);
			if($_POST['status']==2) 
				{
				// Ай на нэ на нэ
				$body   = '<p>'.$_user->name.', администрации Цацки-Пецки понравилось украшение "'.$this->mb_ucfirst($item->title).'"</p>'
							.'<p>С '.$_POST['tstart'].' по '.$_POST['tend'].' украшение "'.$this->mb_ucfirst($item->title).'" занимает позицию '.$profile->name.' бесплатно! Надеемся, это поможет ему найти своих хозяев.</p>'
							.'<p><a href="'.$_SERVER['HTTP_HOST'].'/component/profile">Продать быстрее другие украшения</a></p>'
							.'<p>Администрация сервиса по продаже авторских украшений Цацки-Пецки.ру</p>';
				$subject='Бонус для украшения "'.$this->mb_ucfirst($item->title).'"';
				}
			if($_POST['status']==0)
				{
				$body   = '<p>'.$_user->name.'!</p>'
				.'<p>Для размещения украшения '.$item->title.' на позиции '.$profile->name.' сформирован счет №'.$model->_db->insertid().', стоимость '.$profile->price.'. Как только счет будет оплачен, анонс украшения появится на выбранной позиции и покупатели будут видеть его чаще.</p>'
				.'<p><a href="https://merchant.roboxchange.com/Index.aspx?MrchLogin=Zazki&OutSum='.$profile->price.'&InvId='.$bill->id.'&Desc='.strip_tags($profile->desc).'&SignatureValue='.$MD5.'&Culture=ru&shp_adid='.$item->id.'&shp_profileid='.$profile->id.'">Оплатить счет</a></p>'
				.'<p>Отслеживать статус счета вы можете в разделе «Мои счета»</p>'
				.'<p>Администрация сервиса по продаже авторских украшений Цацки-Пецки.ру</p>';
				$subject='Сформирован счет  №'.$model->_db->insertid();
				}
			$this->setmd5($MD5,$model->_db->insertid());
			JUtility::sendMail($mailfrom, $fromname, $recipient, $subject, $body, $mode=1, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null);
			}
			$msg = JText::_( 'Счет сохранен' );
		} else {
			$msg = JText::_( 'Ошибка сохранения счета' );
		}

//путь

if(!$id)
	$id = $model->_db->insertid();
          $link = 'index.php?option=com_bill&controller=bill&task=edit&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}	

	function remove()
	{
		$model = $this->getModel('bill');
		if(!$model->delete()) {
			$msg = JText::_( 'Ошибка! Один или более счетов не удалены' );
		} else {
			$msg = JText::_( 'Счет(а) удален(ы)' );
		}

		$this->setRedirect( 'index.php?option=com_bill', $msg );
	}

//Закрыть страницу
	function cancel()
	{
		$msg = JText::_( 'Операция отменена' );
		$this->setRedirect( 'index.php?option=com_bill', $msg );
	}
