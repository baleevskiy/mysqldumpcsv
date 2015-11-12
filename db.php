<?php

/*
 * Just a simple wrapper for mysql
 * */
class DB {
    protected $_link;

    public function __construct($params){
        if(!($this->_link = mysql_connect($params['h'], $params['u'], $params['p']))){
            throw new DBException('Can\'t connect to host with specified credentials');
        }
        if(!mysql_select_db($params['d'], $this->_link)) {
            throw new DBException('Can\'t not select database.');
        }
    }

    public function createUsersTable(){
        mysql_query('CREATE TABLE users ('
        .' `name` varchar(128) not null, '
        .' `surname` varchar(128) not null, '
        .' `email` varchar(128) not null , '
        .' UNIQUE KEY `email_unique` (`email`) )', $this->_link
    );
    }

    public function validate($name, $surname, $email){
       return $this->validateEmail($email) && $this->validateName($name) && $this->validateName($surname);
    }

    public function saveUser($name, $surname, $email){

        mysql_query('insert into users (`name`, `surname`, `email`) VALUES ('
            .'"'.mysql_real_escape_string(ucfirst($name)).'"'
            .'"'.mysql_real_escape_string(ucfirst($surname)).'"'
            .'"'.mysql_real_escape_string(strtolower($email)).'"'
            .')'
            , $this->_link);
    }

    public function validateEmail($value){
        $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
        return is_string($value) && strlen($value)<=254 && preg_match($this->pattern,$value);
    }

    public function validateName($value){
        return is_string($value) && strlen($value)<=128;
    }
}

class DBException extends Exception {}