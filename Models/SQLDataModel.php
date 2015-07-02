<?php

defined('INI_FILE') or define('INI_FILE', 'dataModel.ini');
require_once 'iDocDataModel.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SQLDataModel
 *
 * @author zayfals2015
 */
class SQLDataModel implements IDocDataModel {

    //DB keys
    const DB_DSN_KEY = 'dsn';
    const DB_USER_KEY = 'username';
    const DB_PSSWD_KEY = 'password';
    const DB_STTMNT_TTL_KEY = 'statement_title';
    const DB_STTMNT_DOC_KEY = 'statement_doc';
    const DB_INDX_LINK_KEY = 'index_link';
    const DB_INDX_TTL_KEY = 'index_title';
    const DB_INDX_MIME_KEY = 'index_mime';
    const DB_INDX_TEXT_KEY = 'index_text';

    private $ini_array = array();
    private $PDO = null;

    public function __construct() {

        $this->ini_array = parse_ini_file(INI_FILE);
        try {
            $this->PDO = new PDO($this->ini_array[SQLDataModel::DB_DSN_KEY], $this->ini_array[SQLDataModel::DB_USER_KEY]);
            $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            header('Location: ' . urldecode(IError::URL) . '?' . IError::KEY . '=' . urlencode($e->getMessage()));
            exit();
        }
    }

    public function getTitles() {
        $resultList = array();


        try {
            $statement = $this->PDO->prepare($this->ini_array[SQLDataModel::DB_STTMNT_TTL_KEY]);

            if ($statement->execute()) {
                $statement->bindColumn($this->ini_array[SQLDataModel::DB_INDX_LINK_KEY], $link);


                while ($row = $statement->fetch(PDO::FETCH_BOUND)) {
                    array_push($resultList, $link);
                }
            } else {
                echo 'error executed';
            }
            $statement->closeCursor();
        } catch (PDOException $e) {
            header('Location: ' . urldecode(IError::URL) . '?' . IError::KEY . '=' . urlencode($e->getMessage()));
            exit();
        }
        return $resultList;
    }

    public function getDocByTitle($title) {
        $resultList = array();
        try {
            $statement = $this->PDO->prepare($this->ini_array[SQLDataModel::DB_STTMNT_DOC_KEY]);
            if (!$statement->bindParam(
                            $this->ini_array[SQLDataModel::DB_INDX_LINK_KEY], $title)) {
                echo 'error binding title';
            }
            if ($statement->execute()) {
                $statement->bindColumn($this->ini_array[SQLDataModel::DB_INDX_LINK_KEY], $link);
                $statement->bindColumn($this->ini_array[SQLDataModel::DB_INDX_TTL_KEY], $title);
                $statement->bindColumn($this->ini_array[SQLDataModel::DB_INDX_MIME_KEY], $mime);
                $statement->bindColumn($this->ini_array[SQLDataModel::DB_INDX_TEXT_KEY], $text);
                while ($row = $statement->fetch(PDO::FETCH_BOUND)) {
                    $docBuilder = new DocumentBuilder();
                    $doc = $docBuilder->setMime($mime)
                                    ->setTitle($title)
                                    ->setContent($text)->build();
                    array_push($resultList, $doc);
                }
            } else {
                echo 'error executed';
            }
            $statement->closeCursor();
        } catch (PDOException $e) {
            header('Location: ' . urldecode(IError::URL) . '?' . IError::KEY . '=' . urlencode($e->getMessage()));
            exit();
        }
        return $resultList;
    }

    public function __destruct() {
        $this->PDO = NULL;
    }

}
