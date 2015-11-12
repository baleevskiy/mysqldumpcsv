#!/usr/bin/env php
<?php
include('dump.php');

$opts = getopt ('d:u:p:h:' , [
    'file',
    'create_table',
    'dry_run::',
    'help::',
] );

$command = new MainCommand($opts);
$command->execute();
