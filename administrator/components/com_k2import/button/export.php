<?php
/**
 * Renders a HTML export button in the toolbar
 *
 * @package    K2import
 * @link http://www.individual-it.net
 * @license		GNU/GPL
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JButtonExport extends JButton
{
	/**
	 * Button type
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'Export';

	public function fetchButton($type='Custom', $html = '', $id = 'custom')
	{

		//Construct the form action string:
		$Action = JURI::base().'index.php?option=com_k2import&amp;format=raw';

	return
       '<td><a class="toolbar" href="#"
         		onClick="$(\'adminForm\').task.value = \'export\'; $(\'adminForm\').action = \'' . $Action . '\'; $(\'adminForm\').submit();" ><span title="'.$this->_name.'" 
         		class="icon-32-save">
		</span>'.$this->_name.'</a></td>';

	}

	public function fetchId($type='Custom', $html = '', $id = 'custom')
	{
		if(version_compare(JVERSION,'1.6.0','ge')) {
			return $this->_parent->getName().'-'.$id;
		}
	}

}
