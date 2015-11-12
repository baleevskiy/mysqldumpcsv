<?php

/*
 * Just a simple wrapper for mysql
 * */
class DB {
    public function __construct($params){
        if(!mysql_connect($params['h'], $params['u'], $params['p'])){
            throw new DBException('Cant connect to host with specified credentials');
        }
        if(!mysql_select_db($params['d'])) {
            throw new DBException('Could not select database.');
        }
    }

    public function createTable(){
        mysql_query('CREATE TABLE users ('
        .' `name` varchar(64) not null, '
        .' `surname` varchar(64) not null, '
        .' `email` varchar(64) not null , '
        .' UNIQUE KEY `email_unique` (`email`) )'
    );
    }
}

class DBException extends Exception {}