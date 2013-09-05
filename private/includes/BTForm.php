<?php

class BTForm {
	public static function createText($name,$value,$id = '',$classes = '') {
		printf('<input type="text" value="%s" name="%s" id="%s" class="%s" />',$value,$name,$id,$classes);
	}

	/**
	 * Generates a select box. Works with associate arrays or objects.
	 * 
	 * @param string $name
	 * @param array $rows
	 * @param string $default
	 * @param string $id
	 * @param string $classes
	 * @param string $label_key Key used for the label string	
	 * @param string $value_key Key used for the option value
	 * @param string $zero_label Insert an item with ID "0" at the beginning, with this label
	 */
	public static function createSelect($name,$rows,$default = null,$id = '',$classes = '',$label_key = 'label',$value_key = 'value',$zero_label = '') {
		printf('<select name="%s" id="%s" class="%s">',$name,$id,$classes);
		
		if($zero_label) {
			printf('<option value="0">%s</option>',$zero_label);
		}
		
		foreach($rows as $row) {
			$selected = "";
			
			$value = (is_array($row)) ? $row[$value_key] : $row->$value_key;
			$label = (is_array($row)) ? $row[$label_key] : $row->$label_key;
			
			if($value == $default) {
				$selected = 'selected="selected"';
			}
			
			printf('<option %s value="%s">%s</option>',$selected,$value, BTHtml::encode($label));
		}
		
		printf("</select>");
	}
}