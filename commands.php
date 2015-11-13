<?php
include('models.php');

interface Executable{
    public function execute();
}


abstract class Command implements Executable{
    protected  $_params;
    protected $_required_params = [];
    //TODO: add defaults

    public function __construct($params){
        $this->_params = $params;
        foreach($this->_required_params as $param){
            if(!$this->hasParam($param)){
                throw new ParamException('param '.$param.' required');
            }
        }
    }

    public function getParam($key, $default=null){
        return isset($this->_params[$key])?$this->_params[$key]:$default;
    }

    public function hasParam($key){
        return isset($this->_params[$key]);
    }

    public function verbose($message){
        if($this->hasParam('verbose')){
            print $message;
        }
    }
}

class ExportCommand extends Command{
    protected $_required_params = ['u', 'h', 'd', 'p', 'file'];

    public function execute(){
        $model_class = $this->getParam('data_model', 'UserModel');

        $db = new $model_class($this->_params);
        if (($handle = fopen($this->getParam('file'), "r")) !== false) {

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if($db->validate($data)){
                    $this->verbose('saving set :'. implode(' ', $data));
                    $this->verbose( "\r\n");
                    if(!$this->hasParam('dry_run')){
                        $db->save($data);
                    }

                } else {
                    $this->verbose('invalid set :'. implode(' ', $data));
                    $this->verbose("\r\n");
                }
            }
            fclose($handle);
        } else {
            throw new ParamException('Can not read the file');
        }
    }
}


class CreateTableCommand extends Command{
    protected $_required_params = ['u', 'h', 'd', 'p'];

    public function execute(){
        $model_class = $this->getParam('data_model', 'UserModel');
        $db = new $model_class($this->_params);
        $db->createTable();
        $this->verbose('TABLE CREATED');
        return true;
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

--data_model - this will define a model of your csv, "UserModel" by default

-u – MySQL username

-p – MySQL password

-h – MySQL host

-d - MySQL database

--help – which will output the above list of directives with details.

--verbose - will output more info

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
                $command = new ExportCommand($this->_params);
            }
            $command->execute();
        }
        catch(DBException $e){
            print 'DB Exception';
            die($e->getMessage());
        }
        catch(ParamException $e){
            print $e->getMessage();
            print "\n\r\n";
            $command = new PrintHelpCommand($this->_params);
            $command->execute();
        }

        return True;
    }
}

class ParamException extends Exception {}