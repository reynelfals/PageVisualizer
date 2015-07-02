<?php

//defined('INI_FILE_USERS') or define('INI_FILE_USERS', 'fxUsers.ini');
defined('INI_FILE_LOGIN') or define('INI_FILE_LOGIN', 'login.ini');
defined('LOGIN_SCRIPT') or define('LOGIN_SCRIPT', 'login.php');
defined('MAIN_SCRIPT') or define('MAIN_SCRIPT', 'fxCalc.php');
require_once 'iError.php';
//session_start();
class LoginDataModel {

    const USER_KEY = 'user';
    const PSSWD_KEY = 'pass';
    //DB keys
    const DB_DSN_KEY = 'dsn';
    const DB_USER_KEY = 'username';
    const DB_PSSWD_KEY = 'password';
    const DB_STTMNT_KEY = 'statement';
    const DB_INDX_PSSWD_KEY = 'index_password';
    const DB_INDX_USR_KEY = 'index_username';
    const DB_INDX_OUT_KEY = 'index_output';

    private $credentials = array();
    private $names_input_attr = array();
    private $PDO;
    private $statement;

    function __construct() {
        $this->names_input_attr = parse_ini_file(INI_FILE_LOGIN);
        try{
        $this->PDO = new PDO($this->names_input_attr[LoginDataModel::DB_DSN_KEY], $this->names_input_attr[LoginDataModel::DB_USER_KEY], 
                $this->names_input_attr[LoginDataModel::DB_PSSWD_KEY]);
        $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $this->statement = $this->PDO->prepare(
            $this->names_input_attr[LoginDataModel::DB_STTMNT_KEY]);
            
        }  catch (PDOException $e){
            header('Location: '.urldecode(IError::URL).'?'.IError::KEY.'='.urlencode($e->getMessage()));
            exit();            
        }
    }

    public function validateUser($user, $pass) {
        $bd_pass = '';
        try{
        if (!$this->statement->bindParam(
                        $this->names_input_attr[LoginDataModel::DB_INDX_USR_KEY], $user)) {
                
            echo 'error binding user';
        }

        if ($this->statement->execute()) {

            if (!$this->statement->bindColumn(
                            $this->names_input_attr[LoginDataModel::DB_INDX_OUT_KEY], $bd_pass)) {
                echo 'error binding output';
            }
            if($this->statement->fetch(PDO::FETCH_BOUND)===FALSE){
                $this->statement->closeCursor();
                return FALSE;
            }
            $this->statement->closeCursor();
            return $bd_pass === $pass;
        } else {
            echo 'error executing';
        }
         }  catch (PDOException $e){
            header('Location: '.urldecode(IError::URL).'?'.IError::KEY.'='.urlencode($e->getMessage()));
            exit();            
        }
        return FALSE;
    }

    public function getNames() {
        return $this->names_input_attr;
    }

    function __destruct() {
        try{
        $this->PDO = NULL;
        }  catch (PDOException $e){
            header('Location: '.urldecode(IError::URL).'?'.IError::KEY.'='.urlencode($e->getMessage()));
            exit();            
        }
    }

}
