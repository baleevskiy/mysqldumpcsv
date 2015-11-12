#!/usr/bin/env php
<?php


$opts = getopt ('u:p:h:' , [
    'file:',
    'create_table',
    'dry_run',
    'help',
] );


$docs=<<<EOT
--file [csv file name] – this is the name of the CSV to be parsed

--create_table – this will cause the MySQL users table to be built (and no further

action will be taken)

--dry_run – this will be used with the --file directive in the instance that we want

to run the script but not insert into the DB. All other functions will be executed,

but the database won't be altered.

-u – MySQL username

-p – MySQL password

-h – MySQL host

--help – which will output the above list of directives with details.
EOT;


var_dump($opts);