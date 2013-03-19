<?php

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.model');


class PayModelPay extends JModel
{

function getPay()
	{
	$user = & JFactory::getUser();
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__k2_items WHERE published=1 and created_by='.$user->id.' and trash=0 ORDER BY created DESC';
	$db->setQuery($query);
	$row = $db->loadObjectlist();
return $row;	
	
	}
	
	
	function mb_ucfirst($str, $enc = 'utf-8') { 
    	return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc); }
	
	
	function getBill($item,$profile)
	{
	global $app;
	
        $user = & JFactory::getUser();
	$db = JFactory::getDBO();
	$query = 'INSERT INTO #__bill (tupdate,tstart,tend,adid,profileid,userid,price) VALUES(now(),0,0,'.$item->id.','.$profile->id.','.$user->id.','.$profile->price.')';
	$db->setQuery($query);
	$db->query();
	$MD5=MD5('Zazki:'.$profile->price.':'.$db->insertid().':zazki2012:shp_adid='.$item->id.':shp_profileid='.$profile->id);
	$bill_id=$db->insertid();
	$query = "UPDATE #__bill SET MD5='".$MD5."' WHERE id=".$bill_id."";
	$db->setQuery($query);
	$db->Query();
	$query = 'SELECT * FROM #__bill WHERE id='.$bill_id.'';
	$db->setQuery($query);
	$bill = $db->loadObject();
	$mailfrom 		= $app->getCfg( 'mailfrom' );
	$fromname 		= $app->getCfg( 'fromname' );
	$recipient = $user->email;
	$body   = '<p>'.$user->name.'!</p>'
				.'<p>Для размещения украшения '.$this->mb_ucfirst($item->title).' на позиции '.$profile->name.' сформирован счет №'.$bill->id.', стоимость '.$profile->price.'. Как только счет будет оплачен, анонс украшения появится на выбранной позиции и покупатели будут видеть его чаще.</p>'
				.'<p><a href="https://merchant.roboxchange.com/Index.aspx?MrchLogin=Zazki&OutSum='.$profile->price.'&InvId='.$bill->id.'&Desc='.strip_tags($profile->desc).'&SignatureValue='.$MD5.'&Culture=ru&shp_adid='.$item->id.'&shp_profileid='.$profile->id.'">Оплатить счет</a></p>'
				.'<p>Отслеживать статус счета вы можете в разделе «Мои счета»</p>'
				.'<p>Администрация сервиса по продаже авторских украшений Цацки-Пецки.ру</p>';
	$subject='Сформирован счет  №'.$bill->id;
	JUtility::sendMail($mailfrom, $fromname, $recipient, $subject, $body, $mode=1, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null);
	return $bill;	
	
	}
	
	function getProfile($id)
	{
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__profile WHERE id='.$id.'';
	$db->setQuery($query);
	$row = $db->loadObject();
	return $row;	
	
	}
	
	function getTitle($id)
	{
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__k2_items WHERE id='.$id.'';
	$db->setQuery($query);
	$row = $db->loadObject();
	return $row;	
	
	}
	

}
?>