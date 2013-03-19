<?php
defined('_JEXEC') or die('Restricted access');
$_user = & JFactory::getUser();
if ($_user->guest) 
	$action= JURI::root () . 'index.php?option=com_users&view=registration';
		else
			$action= JURI::root () . 'index.php?option=com_pay&view=pay';
isset($_REQUEST['adid']) ? $adid=$_REQUEST['adid'] : $adid=0;
			?>
<link rel="stylesheet" type="text/css" href="components/com_profile/css/profile.css">
<div class="text"> 
<h3>Рекламировать украшение</h3>
<p>Выберите рекламное место, на котором хотите видеть свое объявление:</p>
<?php foreach ($this->rows as $row ) { ?>
<?php 
if($row->image!='') $img='
							<p>Превью:</p>
							<p align="center">
							<a class="modal" href="'.$row->image.'" title="Нажмите для предпросмотра изображений">
							<img src="'.$row->image.'" width="300">
							</a>
							</p>';
	else $img='';
echo '
<br>
<h5>'.$row->name.'</h5>
<p>'.$row->desc.'</p>
<p><b>Стоимость:</b> '.$row->price.' р.</p>
<p><b>Срок размещения:</b> '.$row->term.' д.</p>
'.$img.'
<form method="POST" action="'.$action.'">
<input type="hidden" name="profileid" value="'.$row->id.'">
<input type="hidden" name="adid" value="'.$adid.'">
<input type="hidden" name="price" value="'.$row->price.'">
<center><input type="submit" name="pay" class="button ubLogout" value="Рекламировать"></center>
<input type="hidden" name="return" value="'.base64_encode('/component/pay').'">
</form>
';
}
?>
<h3 align="center">Рекламируйте украшения бесплатно - получайте бонусы! </br><a href=/service/bonus class="underline">Подробнее узнавайте здесь</a> </h3>
</div> 