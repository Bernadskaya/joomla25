<?php

defined('_JEXEC') or die('Restricted access');

if(!function_exists('mb_ucfirst')) {
    function mb_ucfirst($str, $enc = 'utf-8') { 
    		return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc); 
    }
}


?>

<link rel="stylesheet" type="text/css" href="components/com_bill/css/bill.css">


<div class="text"> 
<h2>Мои счета</h2>
<?php if(count($this->rows)):?>

<table class="bill" >
<thead>
<tr>
<th>№</th>
<th>Место</th>
<th>Сумма</th>
<th>Украшение</th>
<th>Статус</th>
</tr>
</thead>
<?php foreach ($this->rows as $row ) {

$place=$this->profiles[$row->id]->name;

switch($row->status)
	{
		case 0: $status='<a href="http://merchant.robokassa.ru/Index.aspx?MrchLogin=Zazki&OutSum='.$this->profiles[$row->id]->price.'&InvId='.$row->id.'&Desc='.strip_tags($this->profiles[$row->id]->desc).'&SignatureValue='.$row->MD5.'&Culture=ru&shp_adid='.$row->adid.'&shp_profileid='.$this->profiles[$row->id]->id.'">Ожидает оплаты</a>'; break;
		case 1: $status='<font color=green>Оплачено</font>'; break;
		case 2: $status='Бонус'; break;
		default: $status='Ошибка'; break;
	}

if($row->status) $place.='<br/>'.$row->tstart.'<br/>'.$row->tend;

 ?>

<?php 
echo '<tr>
<td>'.$row->id.'</td>
<td>'.$place.'</td>
<td>'.$row->price.' р.</td>
<td><a href="/ukrashenia/item/'.$row->adid.'-'.$this->titles[$row->id]->alias.'">'.mb_ucfirst($this->titles[$row->id]->title).'</a></td>
<td>'.$status.'</td>
</tr>';
}
?> 
</table>
<?php else: ?>
<p>У вас пока нет ни одного счета.</p>
<?php endif; ?>
<p align="center">
<?php $url = JURI::root () . 'index.php?option=com_pay&view=pay';
echo '<a href="'. $url.'">Рекламировать еще!</a>'
?>
</p>
</div>