<?php 
defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<?php
$ordering = ($this->lists['order'] == 'a.ordering');
?>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"  />
<input type="hidden" name="filter_order_Dir" value="" />
<div id="editcell">
<table class="adminlist">
<thead>
<tr>
<th width="5">
<?php 
echo JHTML::_('grid.sort', 'Номер', 'id', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th width="20">
<input type="checkbox" name="toggle" value="" 
onclick="checkAll(<?php echo count( $this->items ); ?>);" />
</th>			
<th>
<?php 
echo JHTML::_('grid.sort', 'Профиль', 'Profile', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 
echo JHTML::_('grid.sort', 'Пользователь', 'User', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 
echo JHTML::_('grid.sort', 'ID статьи', 'Adid', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 
echo JHTML::_('grid.sort', 'Дата выставления', 'Tcreate', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 
echo JHTML::_('grid.sort', 'Дата начала', 'Tstart', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 
echo JHTML::_('grid.sort', 'Дата окончания', 'Tend', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 
echo JHTML::_('grid.sort', 'Цена размещения', 'Price', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 
echo JHTML::_('grid.sort', 'Статус', 'Status', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<!--<th width="8%" nowrap="nowrap">
<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering',
 $this->lists['order_Dir'], $this->lists['order'] ); ?>
<?php if ($ordering) echo JHTML::_('grid.order',  $this->items ); ?>
</th> -->
<th width="5%" align="center">
<?php 
echo JHTML::_('grid.sort', 'Published', 'Published', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
</tr>
</thead>
<?php
$k = 0;
for ($i=0, $n=count( $this->items ); $i < $n; $i++)	{
$row = &$this->items[$i];
$checked 	= JHTML::_('grid.id',   $i, $row->id );
$published 	= JHTML::_('grid.published', $row, $i );
$link = JRoute::_( 'index.php?option=com_bill&controller=bill&task=edit&cid[]='. $row->id );
$link2 = JRoute::_( 'index.php?option=com_users&view=user&task=edit&task=edit&cid[]='. $row->userid );
$link3 = JRoute::_( 'index.php?option=com_profile&controller=profile&task=edit&cid[]='. $row->profileid );
$link4 = JRoute::_( 'index.php?option=com_k2&view=item&cid='. $row->adid );
?>
<tr class="<?php echo "row$k"; ?>">
<td align="center">
<a href="<?php echo $link; ?>">
<?php 
//вывод id из базы
echo $row->id; ?>
</a>
</td>
<td>
<?php 
//Вывод чек бокса
echo $checked; ?>
</td>
<td align="center">
<a href="<?php echo $link3; ?>"><?php echo $this->profiles[$row->profileid]; ?></a>
</td>
<td align="center">
<a href="<?php echo $link2; ?>"><?php echo $this->users[$row->userid]; ?></a>
</td>
<td align="center">
<a href="<?php echo $link4; ?>"><?php echo $row->adid; ?></a>
</td>
<td align="center">
<?php echo $row->tcreate; ?>
</td>
<td align="center">
<?php echo $row->tstart; ?>
</td>
<td align="center">
<?php echo $row->tend; ?>
</td>
<td align="center">
<?php echo $row->price; ?>
</td>
<td align="center">
<?php 
switch($row->status)
	{
		case 0: echo JText::_( 'Ожидает оплаты' ); break;
		case 1: echo JText::_( 'Оплачено' ); break;
		case 2: echo JText::_( 'Бонус' ); break;
		default: echo JText::_( 'Error' ); break;
	}
?>
</td>
<!--
<td class="order">
<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" 
<?php echo $disabled ?> class="text_area" style="text-align: center" />
</td>
-->
<td align="center">
<?php
 echo $published;?>
</td>
</tr>
<?php
$k = 1 - $k;
}
?>
<tfoot>
<tr>
<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>
</tfoot>
</table>
</div>

<input type="hidden" name="option" value="com_bill" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="bill" />
<?php 
echo JHTML::_( 'form.token' ); ?>
</form>
