<?php

defined('INI_FILE') or define('INI_FILE', 'fxCalc.ini');
defined('MAIN_SCRIPT') or define('MAIN_SCRIPT', 'fxCalc.php');
require_once 'iError.php';
//session_start();
class FxDataModel {

    const FILE_KEY = 'fx.rates.file';
    const DST_AMT_KEY = 'dst.amt';
    const DST_CUCY_KEY = 'dst.cucy';
    const SRC_AMT_KEY = 'src.amt';
    const SRC_CUCY_KEY = 'src.cucy';
    const DATA_MODEL = 'DATA_MODEL_OBJECT';
//DB keys
    const DB_DSN_KEY = 'dsn';
    const DB_USER_KEY = 'username';
    const DB_PSSWD_KEY = 'password';
    const DB_STTMNT_KEY = 'statement';
    const DB_INDX_SRC_KEY = 'index_srcCucy';
    const DB_INDX_DST_KEY = 'index_dstCucy';
    const DB_INDX_RATE_KEY = 'index_fxRate';
//regex email
    const EMAIL_TO = 'email';

    private $ini_array = array();
    private $fxCurrencies = array();
    private $fxRates = array();

    function __construct() {

        $this->ini_array = parse_ini_file(INI_FILE);

        try{
        $PDO = new PDO($this->ini_array[FxDataModel::DB_DSN_KEY], $this->ini_array[FxDataModel::DB_USER_KEY], 
                $this->ini_array[FxDataModel::DB_PSSWD_KEY]);
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $statement = $PDO->prepare($this->ini_array[FxDataModel::DB_STTMNT_KEY]);

        if ($statement->execute()) {
            $statement->bindColumn($this->ini_array[FxDataModel::DB_INDX_SRC_KEY], $srcCucy);
            $statement->bindColumn($this->ini_array[FxDataModel::DB_INDX_DST_KEY], $dstCucy);
            $statement->bindColumn($this->ini_array[FxDataModel::DB_INDX_RATE_KEY], $fxRate);

            while ($row = $statement->fetch(PDO::FETCH_BOUND)) {
                $this->fxRates[$srcCucy][$dstCucy] = $fxRate;
                array_push($this->fxCurrencies, $srcCucy);
                array_push($this->fxCurrencies, $dstCucy);
            }
            $this->fxCurrencies = array_unique($this->fxCurrencies, $sort_flags = SORT_ASC | SORT_STRING);
        } else {
            echo 'error executed';
        }
        $statement->closeCursor();
        $PDO = NULL;
        }  catch (PDOException $e){
            header('Location: '.urldecode(IError::URL).'?'.IError::KEY.'='.urlencode($e->getMessage()));
            exit();            
        }
    }

    public function getCurrencies() {
        return $this->fxCurrencies;
    }

    public function getFxRate($inCurrency, $outCurrency) {

        $cmpValue=strcmp($inCurrency, $outCurrency);
        if($cmpValue==0){
            return 1.0;
        }
        if($cmpValue<0){
            return $this->fxRates[$inCurrency][$outCurrency];
        }
        return 1/$this->fxRates[$outCurrency][$inCurrency];
    }

    public function getIniArray() {
        return $this->ini_array;
    }

}
