<?php
/**
 * Renders a HTML help button in the toolbar
 *
 * @package    K2import
 * @link http://www.individual-it.net
 * @license		GNU/GPL
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JButtonInstructions extends JButton
{
	/**
	 * Button type
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'Instructions';

	public function fetchButton($type='Custom', $html = '', $id = 'custom')
	{	
		
	return
      '<td><a class="toolbar" href="'. JText::_('COM_K2IMPORT_INSTRUCTIONS_LINK') . '">
       <span title="'.$this->_name.'" class="icon-32-help">
		</span>'.$this->_name.'</a></td>';

	}

	public function fetchId($type='Custom', $html = '', $id = 'custom')
	{
		if(version_compare(JVERSION,'1.6.0','ge')) {
			return $this->_parent->getName().'-'.$id;
		}
	}

}
