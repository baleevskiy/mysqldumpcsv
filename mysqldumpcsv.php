#!/usr/bin/env php
<?php
include('dump.php');

$opts = getopt ('u:p:h:' , [
    'file',
    'create_table',
    'dry_run::',
    'help::',
] );

$command = new MainCommand($opts);
$command->execute();
