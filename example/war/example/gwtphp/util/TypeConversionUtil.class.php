<?php

class TypeConversionUtil {
	public static function dec2hex($dec) {
		$hex = ($dec == 0 ? '0' : '');
		
		if ($dec < 0) {
			$dec = - $dec;
			$sign = '-';
		} else {
			$sign = '';
		}
		while ( $dec > 0 ) {
			$hex = dechex ( $dec - floor ( $dec / 16 ) * 16 ) . $hex;
			$dec = floor ( $dec / 16 );
		}
		return $sign . $hex;
	}
	public static function hex2dec($hex) {
		$dec = hexdec ( $hex );
		if ($dec != 0 && $hex [0] == '-') {
			$dec = - $dec;
		}
		return $dec;
	}
	/**
	 * @param string $v
	 * @return boolean
	 */
	public static function parseBoolean($v) {
		return ( boolean ) $v;
	}
	/**
	 * @param string $v
	 * @return byte (int)
	 */
	public static function parseByte($v) {
		return intval ( $v ); // there are not type byte in php
	}
	/**
	 * @param string $v
	 * @return char (int)
	 */
	public static function parseChar($v) {
		return intval ( $v ); // there are not type byte in php
	}
	
	/**
	 * accepts NaN, Infinity, -Infinity
	 * @param string $v
	 * @return double
	 */
	public static function parseDouble($v) {
		switch ($v) {
			case 'NaN' :
				return NAN;
			case 'Infinity' :
				return INF;
			case '-Infinity' :
				return - INF;
			default :
				return doubleval ( $v );
		}
	}
	/**
	 * accepts NaN, Infinity, -Infinity
	 * @param string $v
	 * @return double
	 */
	public static function parseFloat($v) {
		switch ($v) {
			case 'NaN' :
				return NAN;
			case 'Infinity' :
				return INF;
			case '-Infinity' :
				return - INF;
			default :
				return floatval ( $v );
		}
	}
	/**
	 * @param string $v
	 * @return int
	 */
	public static function parseInt($v) {
		return intval ( $v );
	}
	/** 
	 * for large values we make long as double.
	 * @param string $v
	 * @return long (double)
	 */
	public static function parseLong($v) {
		return doubleval ( $v );
	}
	/** 
	 * @param string $v
	 * @return short (int) 
	 */
	public static function parseShort($v) {
		return intval ( $v );
	}
}

?>