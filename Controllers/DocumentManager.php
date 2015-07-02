<?php

require_once 'DAOAbstractFactory.php';;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocumentManager
 *
 * @author zayfals2015
 */
class DocumentManager {

    private $daokeys = ['SQL', 'File'];
    private $daoList = array();

    function __construct() {
        foreach ($this->daokeys as $keyvalue) {
            array_push($this->daoList, AbstractFactoryDAO::getDao($keyvalue));
        }
    }

    public function getTitles() {
        $titles = array();
        foreach ($this->daoList as $dao) {
            $titles = array_merge($titles, $dao->getTitles());
        }
        return $titles;
    }

    public function getDocumentByTitle($title) {
        $docs = array();
        foreach ($this->daoList as $dao) {
            $docs = array_merge($docs, $dao->getDocByTitle($title));
        }
        return $docs;
    }

}
