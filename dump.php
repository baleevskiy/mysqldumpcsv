<?php
include('db.php');

interface Executable{
    public function execute();
}


abstract class Command implements Executable{
    protected  $_params;
    protected $_required_params = [];

    public function __construct($params){
        $this->_params = $params;
        foreach($this->_required_params as $param){
            if(!$this->hasParam($param)){
                throw new ParamException('params '.$param.' required');
            }
        }
    }

    public function getParam($key, $default=null){
        return isset($this->_params[$key])?$this->_params[$key]:$default;
    }

    public function hasParam($key){
        return isset($this->_params[$key]);
    }
}

class DumpCommand extends Command{
    protected $_required_params = ['u', 'h', 'd', 'p'];

    public function execute(){

    }
}


class CreateTableCommand extends Command{
    protected $_required_params = ['u', 'h', 'd', 'p'];

    public function execute(){
        $db = new DB($this->_params);
        $db->createTable();
    }
}


class PrintHelpCommand extends Command{
    private $__doc=<<<EOT
--file [csv file name] – this is the name of the CSV to be parsed

--create_table – this will cause the MySQL users table to be built (and no further

action will be taken)

--dry_run – this will be used with the --file directive in the instance that we want

to run the script but not insert into the DB. All other functions will be executed,

but the database won't be altered.

-u – MySQL username

-p – MySQL password

-h – MySQL host

-d - MySQL database

--help – which will output the above list of directives with details.

EOT;

    public function execute(){
        print $this->__doc;
        return True;
    }
}


class MainCommand extends Command {
    public function execute(){
        try{

            if($this->hasParam('help')){
                $command = new PrintHelpCommand($this->_params);
            } elseif ($this->hasParam('create_table')){
                $command = new CreateTableCommand($this->_params);
            } else {
                $command = new DumpCommand($this->_params);
            }
            $command->execute();
        }
        catch(DBException $e){
            print 'DB Exception';
            die($e->getMessage());
        }
        catch(ParamException $e){
            print $e->getMessage();
            print "\r\n\r\n";
            $command = new PrintHelpCommand($this->_params);
            $command->execute();
        }

        return True;
    }
}

class ParamException extends Exception {}