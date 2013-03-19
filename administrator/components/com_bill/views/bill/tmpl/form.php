<?php defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript" src="/media/system/js/modal.js"></script>
<script type="text/javascript" src="/media/system/js/calendar.js"></script>
<script type="text/javascript" src="/media/system/js/calendar-setup.js"></script>
<link rel="stylesheet" href="/media/system/css/calendar-jos.css" type="text/css" title="" media="all">
  <script type="text/javascript">
		window.addEvent('domready', function(){ new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false,alwaysHide: true}); });
window.addEvent("domready", function() {
	var JTooltips = new Tips($$(".hasTip"), { maxTitleChars: 50, fixed: false});
});
window.addEvent("domready", function() {
	SqueezeBox.initialize({});
	$$("a.modal-button").each(function(el) {
		el.addEvent("click", function(e) {
			new Event(e).stop();
			SqueezeBox.fromElement(el);
		});
	});
});

			function isBrowserIE() {
				return navigator.appName=="Microsoft Internet Explorer";
			}

			function jInsertEditorText( text, editor ) {
				if (isBrowserIE()) {
					if (window.parent.tinyMCE) {
						window.parent.tinyMCE.selectedInstance.selection.moveToBookmark(window.parent.global_ie_bookmark);
					}
				}
				tinyMCE.execInstanceCommand(editor, 'mceInsertContent',false,text);
			}

			var global_ie_bookmark = false;

			function IeCursorFix() {
				if (isBrowserIE()) {
					tinyMCE.execCommand('mceInsertContent', false, '');
					global_ie_bookmark = tinyMCE.activeEditor.selection.getBookmark(false);
				}
				return true;
			}
window.addEvent("domready", function() {
	SqueezeBox.initialize({});
	$$("a.modal").each(function(el) {
		el.addEvent("click", function(e) {
			new Event(e).stop();
			SqueezeBox.fromElement(el);
		});
	});
});

			function insertReadmore(editor) {
				var content = tinyMCE.get('text').getContent();
				if (content.match(/<hr\s+id=("|')system-readmore("|')\s*\/*>/i)) {
					alert('уже существует');
					return false;
				} else {
					jInsertEditorText('<hr id="system-readmore" />', editor);
				}
			}
			
// Calendar i18n Setup.
Calendar._FD = 0;
Calendar._DN = new Array ("Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");
Calendar._SDN = new Array ("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс");
Calendar._MN = new Array ("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
Calendar._SMN = new Array ("Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек");

Calendar._TT = {};
Calendar._TT["INFO"] = "О календаре";
Calendar._TT["PREV_YEAR"] = "Нажмите, что бы перейти на предыдущий год. Нажмите и удерживайте для показа списка лет.";
Calendar._TT["PREV_MONTH"] = "Нажмите, что бы перейти на предыдущий месяц. Нажмите и удерживайте для показа списка месяцев.";
Calendar._TT["GO_TODAY"] = "Текущая дата";
Calendar._TT["NEXT_MONTH"] = "Нажмите, что бы перейти на следующий месяц. Нажмите и удерживайте для показа списка месяцев.";
Calendar._TT["NEXT_YEAR"] = "Нажмите, что бы перейти на следующий год. Нажмите и удерживайте для показа списка лет.";
Calendar._TT["SEL_DATE"] = "Выберите дату";
Calendar._TT["DRAG_TO_MOVE"] = "Потяните, чтобы переместить";
Calendar._TT["PART_TODAY"] = " (Сегодня)";
Calendar._TT["DAY_FIRST"] = "Показать первые %s";
Calendar._TT["WEEKEND"] = "0,6";
Calendar._TT["CLOSE"] = "Закрыть";
Calendar._TT["TODAY"] = "Сегодня";
Calendar._TT["TIME_PART"] = "Shift-клик или потяните, чтобы изменить значение.";
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%A, %B %e";
Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "Время:";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the \xab, \xbb buttons to select year\n" +
"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";

Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

window.addEvent('domready', function() {Calendar.setup({
        inputField     :    "tstart",     // id of the input field
        ifFormat       :    "%Y-%m-%d %H:%M",      // format of the input field
        button         :    "tstart_img",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true
    });});
function keepAlive() { new Ajax("index.php", {method: "get"}).request(); }
window.addEvent("domready", function() { keepAlive.periodical(840000); });
  </script>
  <script type="text/javascript">  
  function check_data()
	{
		return true;
	}
  </script>
  
  <script type="text/javascript">
function submitbutton(pressbutton) {
	
	if(pressbutton!='cancel')
	{
	if(document.adminForm.userid.value==-1) {alert('Выберите пользователя'); return;}
	if(document.adminForm.profileid.value==-1) {alert('Выберите профиль'); return;}
	if(document.adminForm.status.value==-1) {alert('Выберите статус'); return;}
	if(document.adminForm.tstart.value=='') {alert('Ведите дату начала показа'); return;}
	if(isNaN($a=parseInt(document.adminForm.adid.value, 10))||$a<=0) {alert('Ведите ID объявления'); return;}
	}
	submitform(pressbutton);
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Профиль' ); ?>:
				</label>
			</td>
			<td>
				<select name="profileid" id="profileid">
				<option value="-1" >-</option>
				<?php
				foreach($this->profiles as $v)
					{
					if($v->id==$this->hello->profileid) $std='selected';
						else $std='';
					echo '<option value="'.$v->id.'" '.$std.'>'.$v->name.'</option>';
					}
				?>
				</select>
			
			</td>

			</tr>
			<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'User' ); ?>:
				</label>
			</td>
				<td>
				<select name="userid" id="userid">
				<option value="-1" >-</option>
				<?php
				foreach($this->users as $v)
					{
					if($v->id==$this->hello->userid) $std='selected';
						else $std='';
					echo '<option value="'.$v->id.'" '.$std.'>'.$v->name.'</option>';
					}
				?>
				</select>
			</td>
			</tr>
				<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Статус' ); ?>:
				</label>
			</td>
				<td>
				<select name="status" id="status">
			<option value="-1" >-</option>
			<option value="0" <?php if($this->hello->status==0) echo 'selected';?>><?php echo JText::_( 'Не оплачено' ); ?></option>
			<option value="1" <?php if($this->hello->status==1) echo 'selected';?>><?php echo JText::_( 'Оплачено' ); ?></option>
			<option value="2" <?php if($this->hello->status==2) echo 'selected';?>><?php echo JText::_( 'Бонус' ); ?></option>
				</select>
			</td>
			</tr>		
					<tr>
			<td width="100" align="right" class="key">
				<label for="adres">
					<?php echo JText::_( 'Дата начала публикации' ); ?>:
				</label>
			</td>
			<td class="paramlist_value">
				<input class="inputbox" type="text" name="tstart" id="tstart"  value="<?php echo $this->hello->tstart;?>" />
				<img class="calendar" src="/templates/system/images/calendar.png" alt="calendar" id="tstart_img">
			</td>
			</tr>
			
						<tr>
			<td width="100" align="right" class="key">
				<label for="adres">
					<?php echo JText::_( 'ID объявления' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="adid" id="adid" size="32" maxlength="250" value="<?php echo $this->hello->adid;?>" />
				
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
<input type="hidden" name="option" value="com_bill" />
<input type="hidden" name="id" value="<?php echo $this->hello->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="bill" />
</form>
