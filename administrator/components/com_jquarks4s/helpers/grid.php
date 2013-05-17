<?php
defined('_JEXEC') or die('Restricted access');

class Jquarks4sJHTMLGridHelper
{
	/**
	* @param int The row index
	* @param int The record id
	* @param boolean
	* @param string The name of the form element
	*
	* @return string
	*/
	function id( $rowNum, $recId, $checked=false, $name='cid' )
	{
		$checkbox = '<input type="checkbox" id="cb'.$rowNum.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" ';
		if ( $checked ) {
			$checkbox .= ' checked=checked ';
		}
		$checkbox .= ' />';
		
		return $checkbox;
	}

}
