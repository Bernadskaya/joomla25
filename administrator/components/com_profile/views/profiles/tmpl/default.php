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

echo JHTML::_('grid.sort', 'id', 'id', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th width="20">
<input type="checkbox" name="toggle" value="" 
onclick="checkAll(<?php echo count( $this->items ); ?>);" />
</th>			
<th>
<?php 

echo JHTML::_('grid.sort', 'Название', 'Name', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 

echo JHTML::_('grid.sort', 'Срок размещения', 'Term', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 

echo JHTML::_('grid.sort', 'Цена', 'Price', 
$this->lists['order_Dir'], $this->lists['order'] );
?>
</th>
<th>
<?php 
//echo JHTML::_('grid.sort', 'Описание', 'Desc', 
//$this->lists['order_Dir'], $this->lists['order'] );
echo JText::_( 'Описание' );
?>
</th>
<th>
<?php 
//echo JHTML::_('grid.sort', 'Изображение', 'Image', 
//$this->lists['order_Dir'], $this->lists['order'] );
echo JText::_( 'Image' );
?>
</th>
<th width="8%" nowrap="nowrap">
<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering',
 $this->lists['order_Dir'], $this->lists['order'] ); ?>
<?php if ($ordering) echo JHTML::_('grid.order',  $this->items ); ?>
</th>
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

$link = JRoute::_( 'index.php?option=com_profile&controller=profile&task=edit&cid[]='. $row->id );
?>
<tr class="<?php echo "row$k"; ?>">
<td>
<?php 

echo $row->id; ?>
</td>
<td>
<?php 

echo $checked; ?>
</td>
<td align="center">

<a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
</td>
<td align="center">

<?php echo $row->term; ?>
</td>
<td align="center">

<?php echo $row->price; ?>
</td>
<td align="center">

<?php echo $row->desc; ?>
</td>
<td align="center">

<img src="<?php echo $row->image; ?>" width="50">
</td>
<td class="order">
<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" 
<?php echo $disabled ?> class="text_area" style="text-align: center" />
</td>
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
<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>
</tfoot>
</table>
</div>

<input type="hidden" name="option" value="com_profile" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="profile" />
<?php 

echo JHTML::_( 'form.token' ); ?>
</form>
