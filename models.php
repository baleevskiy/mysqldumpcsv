<?php

/*
 * Just a simple wrapper for mysql
 * */

//TODO: make a base class
class UserModel {
    protected $_link;

    public function __construct($params){
        if(!($this->_link = mysql_connect($params['h'], $params['u'], $params['p'] or ''))){
            throw new DBException('Can\'t connect to host with specified credentials');
        }
        if(!mysql_select_db($params['d'], $this->_link)) {
            throw new DBException('Can\'t not select database.');
        }
    }

    public function createTable(){
        mysql_query('CREATE TABLE users ('
        .' `name` varchar(128) not null, '
        .' `surname` varchar(128) not null, '
        .' `email` varchar(128) not null , '
        .' UNIQUE KEY `email_unique` (`email`) )', $this->_link
    );
    }

    public function validate($data){
        list($name, $surname, $email) = $data;
        return $this->validateEmail($email) && $this->validateName($name) && $this->validateName($surname);
    }

    public function save($data){
        list($name, $surname, $email) = $data;
        $query = 'insert into users (`name`, `surname`, `email`) VALUES ('
            .'"'.mysql_real_escape_string(ucfirst($name)).'",'
            .'"'.mysql_real_escape_string(ucfirst($surname)).'",'
            .'"'.mysql_real_escape_string(strtolower($email)).'"'
            .')';
        mysql_query($query, $this->_link);
    }

    public function validateEmail($value){
        $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
        return is_string($value) && strlen($value)<=254 && preg_match($pattern,$value);
    }

    public function validateName($value){
        return is_string($value) && strlen($value)<=128;
    }
}

class DBException extends Exception {}