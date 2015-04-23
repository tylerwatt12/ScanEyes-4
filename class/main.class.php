<?php
class main{
	public function convertTime($input,$outputFormat){ // converts timestamp to various other formats
		if ((strlen($input) === 16 && is_numeric($input)) || strlen($input) === 10 && is_numeric($input)) { // converting 16 digit times to 10 digit standard UNIX epoch time
			$input = substr($input, 0,10);

			switch ($outputFormat) { // strtotime doesn't support UNIX timestamps that's why code is mostly copied from below
				case 'TIME': // outputs unix epoch with padded zeros until the 16th digit to satisfy database requirement
					return (string)$input."000000";
				case 'UNIXTS': // outputs regular unix epoch 10 digit
					return (string)$input;
				default: // run regular converter
					return(string)(date($outputFormat,$input));
			}

		}else{
			switch ($outputFormat) {
				case 'TIME': // outputs unix epoch with padded zeros until the 16th digit to satisfy database requirement
					return (string)strtotime($input)."000000";
				case 'UNIXTS': // outputs regular unix epoch 10 digit
					return (string)strtotime($input);
				default: // run regular converter
					return(string)(date($outputFormat,strtotime($input)));
			}
		}
	}
	public function getTimeago( $ptime ){
		$estimate_time = time() - $ptime;

		if( $estimate_time < 1 ){
			return 'less than 1 second ago';
		}

		$condition = array( 
			12 * 30 * 24 * 60 * 60  =>  'year',
			30 * 24 * 60 * 60       =>  'month',
			24 * 60 * 60            =>  'day',
			60 * 60                 =>  'hour',
			60                      =>  'minute',
			1                       =>  'second'
		);

		foreach( $condition as $secs => $str ){
			$d = $estimate_time / $secs;

			if( $d >= 1 ){
				$r = round( $d );
				return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
			}
		}
	}
}

?>