<?php

for($num=1; $num<=100; $num++){
    $res = '';
    if($num % 3 == 0){
        $res = 'triple';
    }
    if($num % 5 == 0){
        $res .= 'fiver';
    }
    if($res == ''){
        print $num;
    } else {
        print $res;
    }
    print "\n";
}