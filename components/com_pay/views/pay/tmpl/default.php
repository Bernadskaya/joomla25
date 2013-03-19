<?php

defined('_JEXEC') or die('Restricted access');
 function mb_ucfirst($str, $enc = 'utf-8') { 
    		return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc); 
    
	}
?>
 <script type="text/javascript">
function check() {
input = document.getElementsByTagName('input');
for(i=0;i<(input).length;i++){
   if(input[i].getAttribute('type')=='radio'){
    if(input[i].checked) return true;
   }
}
alert('Выберите способ оплаты');
return false;
}
</script>
<link rel="stylesheet" type="text/css" href="components/com_pay/css/pay.css">

<?php if(isset($_POST['bill'])): ?>

<div class="text"> 
<h2>Оплата</h2>
<p>Стоимость размещения украшения <?php echo mb_ucfirst($this->item->title); ?> на позиции  <?php echo $this->profile->name; ?> : <?php echo $this->profile->price; ?> р.</p>
<p>Обратите внимание, что зачисленные средства не возвращаются. В случае спорных вопросов обращайтесь на info@zazki-pezki.ru </p>

<p>Для оплаты сформирован счет №<?php echo $this->bill->id; ?>. Отслеживать статус счета вы можете в разделе <?php
	       $url = JURI::root () . 'index.php?option=com_bill&view=bill';  echo '<a href="'. $url.'">Мои счета</a>' ?></p>
<p>Выберите удобный для вас способ оплаты (оплата происходит через систему платежей Robokassa.ru):</p>
<form method="POST" name="payform" id="payform" action="https://merchant.roboxchange.com/Index.aspx" onsubmit="return check();">
<table class="bill" with=100% style="margin-right: 30px"  >
<tr>
	       <TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="MegafonR"></P>
		</TD>
		<TD>
			<P><img src=/images/megafon.png margin-right="30px"></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="MtsR"></P>
		</TD>
		<TD>
			<P><img src=/images/mts.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>Мегафон</P>
		</TD>
		<TD>
			<P>МТС</P>
		</TD>
</TR>
</TABLE>
<TABLE with=100%>
<tr>
	       <TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="TerminalsPinpayR"></P>
		</TD>
		<TD>
			<P><img src=/images/pinpay.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="QiwiR"></P>
		</TD>
		<TD>
			<P><img src=/images/qiwi.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="TerminalsMElementR"></P>
		</TD>
		<TD>
			<P><img src=/images/mobil_element.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>Pinpay</P>
		</TD>
		<TD>
			<P>QIWI</P>
		</TD>
		<TD>
			<P>Мобил Элемент</P>
		</TD>
</TR>
<tr>
	       <TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="TerminalsNovoplatR"></P>
		</TD>
		<TD>
			<P><img src=/images/novoplat.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="TerminalsUnikassaR"></P>
		</TD>
		<TD>
			<P><img src=/images/unikassa.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="ElecsnetR"></P>
		</TD>
		<TD>
			<P><img src=/images/elexnet.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>Новоплат</P>
		</TD>
		<TD>
			<P>Уникасса</P>
		</TD>
		<TD>
			<P>Элекснет</P>
		</TD>
</TR>
<tr>
	       <TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="ContactR"></P>
		</TD>
		<TD>
			<P><img src=/images/contact.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="IFreeR"></P>
		</TD>
		<TD>
			<P><img src=/images/sms-logo.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="BANKOCEANCHECKR"></P>
		</TD>
		<TD>
			<P><img src=/images/iphone.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>Contact</P>
		</TD>
		<TD>
			<P>SMS</P>
		</TD>
		<TD>
			<P>iPhone</P>
		</TD>
</TR>
<tr>
	       <TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="QiwiR"></P>
		</TD>
		<TD>
			<P><img src=/images/qiwi.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="VTB24R"></P>
		</TD>
		<TD>
			<P><img src=/images/vtb24.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="SincCurrLabel" value="TerminalsPkbR"></P>
		</TD>
		<TD>
			<P><img src=/images/petrokommerc.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>QIWI Кошелек</P>
		</TD>
		<TD>
			<P>ВТБ24</P>
		</TD>
		<TD>
			<P>Петрокоммерц</P>
		</TD>
</TR>
<tr>
	       <TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="RapidaOceanEurosetR"></P>
		</TD>
		<TD>
			<P><img src=/images/evroset.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="AlfaBankR"></P>
		</TD>
		<TD>
			<P><img src=/images/alfaklik.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="RapidaOceanSvyaznoyR"></P>
		</TD>
		<TD>
			<P><img src=/images/svyaznoi.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>Евросеть</P>
		</TD>
		<TD>
			<P>Альфа-Клик</P>
		</TD>
		<TD>
			<P>Связной</P>
		</TD>
</TR>

<tr>
	       <TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="EasyPayB"></P>
		</TD>
		<TD>
			<P><img src=/images/easypay.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="MoneyMailR"></P>
		</TD>
		<TD>
			<P><img src=/images/moneymail.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="RuPayR"></P>
		</TD>
		<TD>
			<P><img src=/images/rbk.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>EasyPay</P>
		</TD>
		<TD>
			<P>MoneyMail</P>
		</TD>
		<TD>
			<P>RBK Money</P>
		</TD>
</TR>
<tr>
	       <TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="W1R"></P>
		</TD>
		<TD>
			<P><img src=/images/ed_koshelek.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="ZPaymentR"></P>
		</TD>
		<TD>
			<P><img src=/images/z-payment.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="LiqPayZ"></P>
		</TD>
		<TD>
			<P><img src=/images/liqpay.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>Единый Кошелек</P>
		</TD>
		<TD>
			<P>Z-Payment</P>
		</TD>
		<TD>
			<P>LiqPay</P>
		</TD>
</TR>
<tr>
	       <TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="WMRM"></P>
		</TD>
		<TD>
			<P><img src=/images/webmoney.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="MailRuR"></P>
		</TD>
		<TD>
			<P><img src=/images/moneymailru.png></P>
		</TD>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="PCR"></P>
		</TD>
		<TD>
			<P><img src=/images/yadengi.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>WMR</P>
		</TD>
		<TD>
			<P>Деньги@Mail.Ru</P>
		</TD>
		<TD>
			<P>Яндекс.Деньги</P>
		</TD>
</TR>
<tr>
		<TD ROWSPAN=2>
			<P><input type="radio" name="incCurrLabel" value="TeleMoneyR"></P>
		</TD>
		<TD>
			<P><img src=/images/telemoney.png></P>
		</TD>
</tr>
<TR VALIGN=TOP>
		<TD>
			<P>TeleMoney</P>
		</TD>
</TR>
</table>
<!--
<h5>Со счета телефона</h5>
Мегафон<input type="radio" name="incCurrLabel" value="MegafonR">
МТС<input type="radio" name="incCurrLabel" value="MtsR">
<h5>Банковской картой</h5>
RUR Банковская карта<input type="radio" name="incCurrLabel" value="BANKOCEANMR">
<p>Банковской картой через Platezh.ru</p>
RUR Океан Банк<input type="radio" name="incCurrLabel" value="OceanBankOceanR">
<p>Терминалами</p>
Pinpay<input type="radio" name="incCurrLabel" value="TerminalsPinpayR">
QIWI<input type="radio" name="incCurrLabel" value="QiwiR">
Мобил Элемент<input type="radio" name="incCurrLabel" value="TerminalsMElementR">
Новоплат<input type="radio" name="incCurrLabel" value="TerminalsNovoplatR">
Уникасса<input type="radio" name="incCurrLabel" value="TerminalsUnikassaR">
Элекснет<input type="radio" name="incCurrLabel" value="ElecsnetR">
<p>Переводом в системе Contact</p>
RUR Contact<input type="radio" name="incCurrLabel" value="ContactR">
<p>С помощью SMS</p>
RUR SMS<input type="radio" name="incCurrLabel" value="IFreeR">
<p>Через Iphone</p>
RUR iPhone<input type="radio" name="incCurrLabel" value="BANKOCEANCHECKR">
<p>Через QIWI кошелек</p>
QIWI Кошелек<input type="radio" name="incCurrLabel" value="QiwiR">
<p>Через банкомат</p>
ВТБ24<input type="radio" name="incCurrLabel" value="VTB24R">
Петрокоммерц<input type="radio" name="SincCurrLabel" value="TerminalsPkbR">
<p>Через Евросеть</p>
RUR Евросеть<input type="radio" name="incCurrLabel" value="RapidaOceanEurosetR">
<p>Через интернет-банк Альфа-Клик</p>
Альфа-Клик<input type="radio" name="incCurrLabel" value="AlfaBankR">
<p>Через Связной</p>
RUR Связной<input type="radio" name="incCurrLabel" value="RapidaOceanSvyaznoyR">
<p>Электронными деньгами</p>
EasyPay<input type="radio" name="incCurrLabel" value="EasyPayB">
QIWI Кошелек<input type="radio" name="incCurrLabel" value="QiwiR">
RUR MoneyMai<input type="radio" name="incCurrLabel" value="MoneyMailR">
RUR RBK Money<input type="radio" name="incCurrLabel" value="RuPayR">
RUR TeleMoney<input type="radio" name="incCurrLabel" value="TeleMoneyR">
RUR Z-Payment<input type="radio" name="incCurrLabel" value="ZPaymentR">
RUR Единый Кошелек<input type="radio" name="incCurrLabel" value="W1R">
USD LiqPay<input type="radio" name="incCurrLabel" value="LiqPayZ">
WMB<input type="radio" name="incCurrLabel" value="WMBM">
WME<input type="radio" name="incCurrLabel" value="WMEM">
WMG<input type="radio" name="incCurrLabel" value="WMGM">
WMR<input type="radio" name="incCurrLabel" value="WMRM">
WMU<input type="radio" name="incCurrLabel" value="WMUM">
WMZ<input type="radio" name="incCurrLabel" value="WMZM">
Деньги@Mail.Ru<input type="radio" name="incCurrLabel" value="MailRuR">
Яндекс.Деньги<input type="radio" name="incCurrLabel" value="PCR">-->

<input type="hidden" name="MrchLogin" value="Zazki">
<input type="hidden" name="OutSum" value="<?php echo $this->profile->price; ?>">
<input type="hidden" name="InvId" value="<?php echo $this->bill->id; ?>">
<input type="hidden" name="Desc" value="<?php echo strip_tags($this->profile->desc); ?>">
<input type="hidden" name="SignatureValue" value="<?php echo $this->bill->md5; ?>">
<input type="hidden" name="Culture" value="ru">
<input type="hidden" name="Encoding" value="utf-8">
<input type="hidden" name="shp_adid" value="<?php echo $this->item->id; ?>">
<input type="hidden" name="shp_profileid" value="<?php echo $this->profile->id; ?>">
<input class="button" type="submit" name="pay" value="Оплатить">
</form>
</div>
<?php else:  ?>
<div class="text"> 
<h2>Оплата</h2>
<?php if(count($this->rows)): ?>
<p>Выберите украшение, которое хотите рекламировать:</p>
<form method="POST" action="">
<select name="adid">
<?php foreach ($this->rows as $row ) { ?>

<?php 
$row->id==$_POST['adid'] ? $slt='selected' : $slt='';
echo '<option value="'.$row->id.'" '.$slt.'>
'.$row->title.'
</option>';
}

?> 
</select>

<input type="hidden" name="profileid" value="<?php echo $_REQUEST['profileid'] ?>">
<p align="center"><input type="submit" name="bill" value="Далее"></p>
</form>
<?php else: ?>
<p>К сожалению, у Вас пока нет ни одного украшения и Вам пока нечего рекламировать.</p>
<input type="hidden" name="profileid" id="profileid" value="<?php echo $_REQUEST['profileid'] ?>">
<a id="modal_anchor" class="modal" rel="{handler:'iframe',size:{x:750,y:650}}" href="/component/k2/item/add?tmpl=modal">Новое украшение</a>
<?php endif; ?>
</div>
<?php  endif; ?>