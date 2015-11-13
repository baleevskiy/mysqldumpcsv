<?php

for($num=1; $num<=100; $num++){
    $res = $num % 3 == 0? 'triple': '';
    $res .= $num % 5 == 0? 'fiver': '';
    print $res == ''? $num : $res;
    print "\n";
}