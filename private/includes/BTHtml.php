<?php

class BTHtml {
	/**
	 * Encodes a string's HTML entities. 
	 * 
	 * @param string $string The string to encode
	 * @return string
	 */
	public static function encode($string) {
		return htmlentities($string, ENT_QUOTES, 'UTF-8');
	}
	
	/**
	 * Decodes a string's HTML entities back to their original formats
	 * 
	 * @param string $string The string to decode
	 * @return string
	 */
	public static function decode($string) {
		return html_entity_decode($string, ENT_QUOTES, 'UTF-8');
	}
}