<?php
defined('_JEXEC') or die('Restricted access');
 function mb_ucfirst($str, $enc = 'utf-8') { 
    		return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc); 
    
	}
?>
<link rel="stylesheet" type="text/css" href="components/com_notify/css/notify.css">

<?php if($this->action=='success'): ?>

<div class="text">
<p>Поздравляем!</p>
<p>Платеж успешно выполнен.</p>
<p>Теперь украшение <?php echo mb_ucfirst($this->item->title); ?> увидит больше покупателей и оно быстрее найдет своего владельца.</p>
<p>Подтверждение об оплате отправлено также на адрес <?php echo $this->user->email; ?>, указанный при регистрации</p>
<p><a href="/component/profile">Продать быстрее еще одно украшение</a></p>
</div>
<?php elseif($this->action=='fail'):  ?>
<div class="text">
<p>Поздравляем!</p>
<p>За Вами уже выехали.</p>
</div>
<?php  endif; ?>