#!/usr/bin/env php
<?php


$opts = getopt ('u:p:h:' , [
    'file',
    'create_table',
    'dry_run::',
    'help::',
] );




if(isset($opts['help'])){
    die($docs);
}




var_dump($opts);