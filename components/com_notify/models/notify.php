<?php

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.model');


class NotifyModelNotify extends JModel
{

 function mb_ucfirst($str, $enc = 'utf-8') { 
    		return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc); 
    
	}

	
	function getResult($_POST)
	{
	global $mainframe;
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__bill WHERE id='.$_POST['InvId'];
	$db->setQuery($query);
	$bill = $db->loadObject();
	$profile=$this->getProfile($bill->profileid);
	if($_POST['SignatureValue']!=strtoupper(md5($bill->price.':'.$bill->id.':2012zazki:shp_adid='.$bill->adid.':shp_profileid='.$bill->profileid))) return false;
	$user = & JFactory::getUser($bill->userid);
	$tend=date('Y-m-d H:i:s',$profile->term*86400+time());
	$query = "UPDATE #__bill SET status=1, tend='".$tend."', tstart=now(), tupdate=now() WHERE id=".$bill->id;
	$db->setQuery($query);
	$db->Query();
	$item=$this->getItem($bill->adid);
	$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
	$fromname 		= $mainframe->getCfg( 'fromname' );
	$recipient = $user->email;
	$body   = '<p>'.$user->name.', поздравляем!</p>'
			.'<p>Платеж успешно выполнен, Ваше украшение '.$this->mb_ucfirst($item->title).' по адресу <a href="'.$_SERVER['HTTP_HOST'].'/component/k2/item/'.$item->id.'-'.$item->title.'">'.$_SERVER['HTTP_HOST'].'/component/k2/item/'.$item->id.'-'.$item->title.'</a> размещено на '.$profile->name.' до '.$tend.'.</p>'
			.'<p>Теперь украшение '.$this->mb_ucfirst($item->title).' увидит больше покупателей и оно быстрее найдет своего владельца!</p>'
			.'<p><a href="'.$_SERVER['HTTP_HOST'].'/component/profile">Продать быстрее еще одно украшение</a></p>';
	$subject=' Оплата счета №'.$_POST['InvId'].' прошла успешно';
	JUtility::sendMail($mailfrom, $fromname, $recipient, $subject, $body, $mode=1, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null);
	
	return $bill->id;	
	
	}
	
	function getItem($id)
	{
	$db = JFactory::getDBO();
	$query = 'SELECT COUNT(*) FROM #__bill WHERE adid='.$id.' and status=1';
	$db->setQuery($query);
	$result=$db->loadResult();
	if($result==0) return false;
	$query = 'SELECT * FROM #__k2_items WHERE id='.$id;
	$db->setQuery($query);
	$item = $db->loadObject();
	return $item;	
	
	}
	
	function getProfile($id)
	{
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__profile WHERE id='.$id;
	$db->setQuery($query);
	$profile = $db->loadObject();
	return $profile;	
	
	}
	
	function getNotify()
	{
	global $mainframe;
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__bill WHERE status!=0 and notifed=0 and (unix_timestamp(tend)-unix_timestamp(now()))<=86400 and tend>now()';
	$db->setQuery($query);
	$notyfeds = $db->loadObjectList();
	foreach($notyfeds as $notyfed)
		{
			$user = & JFactory::getUser($notyfed->userid);
			$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
			$fromname 		= $mainframe->getCfg( 'fromname' );
			$profile=$this->getProfile($notyfed->profileid);
			$item=$this->getItem($notyfed->adid);
			$recipient = $user->email;
			$body   = '<p>Доброго времени суток, '.$user->name.'!</p>'
							.'<p>К сожалению, анонс украшения '.$this->mb_ucfirst($item->title).' через 12 часов покинет место в '.$profile->name.'.</p>'
							.'<p>Не забудьте заказать новое размещение.</p>'
							.'<p>Администрация сервиса по продаже авторских украшений Цацки-Пецки.ру</p>';
			$subject='Размещение по счету '.$notyfed->id.' подходит к концу';
			JUtility::sendMail($mailfrom, $fromname, $recipient, $subject, $body, $mode=1, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null);
			$query = 'UPDATE #__bill SET notifed=1 WHERE id='.$notyfed->id;
			$db->setQuery($query);
			$db->Query();
		}
	return;	
	
	}
	
}
?>