<?php

interface Executable{
    public function execute();
}


abstract class Command implements Executable{
    protected  $_params;

    public function __construct($params){
        $this->_params = $params;
    }
}

class DumpCommand extends Command{

    public function execute(){

    }


}


class CreateTableCommand extends Command{
    public function execute(){

    }
}


class PrintHelpCommand extends Command{
    public function execute(){

    }
}
