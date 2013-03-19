<?php defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript">
function submitbutton(pressbutton) {
	
	if(pressbutton!='cancel')
	{
	if(document.adminForm.name.value=='') {alert('Ведите название'); return;}
	if(isNaN($a=parseInt(document.adminForm.term.value, 10))||$a<1) {alert('Ведите срок размещения'); return;}
	if(isNaN($a=parseInt(document.adminForm.price.value, 10))||$a<=0) {alert('Ведите цену'); return;}
	}
	submitform(pressbutton);
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Название' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->hello->name;?>" />
			</td>
			</tr>
			
			<tr>
			<td width="100" align="right" class="key">
				<label for="opisanie">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
			<td>
<?php
//Подключение редактора
                $editor =& JFactory::getEditor();
                echo $editor->display('desc', $this->hello->desc, '550', '400', '60', '20', true );
                ?>
			</td>
			</tr>
			
					<tr>
			<td width="100" align="right" class="key">
				<label for="term">
					<?php echo JText::_( 'Срок размещения, сутки' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="term" id="term" size="32" maxlength="250" value="<?php echo $this->hello->term;?>" />
			</td>
			</tr>
			
						<tr>
			<td width="100" align="right" class="key">
				<label for="adres">
					<?php echo JText::_( 'Цена размещения, рубли' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="price" id="price" size="32" maxlength="250" value="<?php echo $this->hello->price;?>" />
			</td>
			</tr>
			
						<tr>
			<td width="100" align="right" class="key">
				<label for="adres">
					<?php echo JText::_( 'Image' ); ?>:
				</label>
			</td>
			<td>
				<?php if($this->hello->image!=''):?>
				<img src="<?php echo $this->hello->image;?>" height="200"><br/>
		<!--		<input type="checkbox" name="delete_image" id="delete_image"  value="1" />
				<label for="delete_image">
					<?php echo JText::_( 'delete' ); ?>
				</label>
				<br/><br/> -->
				<?php endif; ?>
				<input class="text_area" type="file" name="image" id="image"  value="" />
			</td>
			</tr>
			
			<?php
			if($this->hello->published==1){ echo '
			<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					'.JText::_( "published" ).'
				</label>
			</td>
			<td>
'.JText::_( "no" ).'<input type="radio" name="published" id="published0" value="0" />    
'.JText::_( "yes" ).'<input type="radio" name="published" id="published1" value="'.$this->hello->published.'" checked/>
		</tr>
		';}
		else{
		echo '
			<tr>
			<td width="100" align="right" class="key">
				<label for="name">
				'.JText::_( "published" ).'
				</label>
			</td>
			<td>  
'.JText::_( "no" ).'<input type="radio" name="published" id="published0" value="'. $this->hello->published.'" checked>'.
JText::_( "yes" ).'<input type="radio" name="published" id="published1" value="1" />
		</tr>
		';
		}
		
		?>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_profile" />
<input type="hidden" name="id" value="<?php echo $this->hello->id; ?>" />
<input type="hidden" name="old_image" value="<?php echo $this->hello->image;?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="profile" />
</form>
