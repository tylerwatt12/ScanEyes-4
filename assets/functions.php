<?php
    function __loadclass($class_name)
    {
      include_once 'class/'.$class_name . '.class.php';
    }
    function __loadasset($asset_name)
    {
        include_once 'assets/'.$asset_name . '.php';
    }
    function timeStrConvert($input){
    #20140102062933 to YYYY-MM-DD HH:MM:SS
        $y = substr($input, 0,4);
        $m = substr($input, 4,2);
        $d = substr($input, 6,2);
        $h = substr($input, 8,2);
        $i = substr($input, 10,2);
        $s = substr($input, 12,2);
        if (date('Ymd') == $y.$m.$d) {
            $output = $h.":".$i.":".$s;
        }else{
            $output = $y."-".$m."-".$d." ".$h.":".$i.":".$s;
        }

        return $output;
    }
?>