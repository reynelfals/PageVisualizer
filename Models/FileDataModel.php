<?php

defined('INI_FILE') or define('INI_FILE', 'dataModel.ini');
require_once 'iDocDataModel.php';
require_once 'DocumentBuilder.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TextDataModel
 *
 * @author zayfals2015
 */
class FileDataModel implements IDocDataModel {

    const FILE_DIR_KEY = 'dir_root';

    private $dir = null;

    public function __construct() {
        $ini_array = parse_ini_file(INI_FILE);
        $this->dir = $ini_array[FileDataModel::FILE_DIR_KEY];
    }

    public function getTitles() {
        $cdir = scandir($this->dir);
        $listfile = array();
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                
                array_push($listfile,  pathinfo($value, PATHINFO_FILENAME));
            }
        }
        return $listfile;
    }

    public function getDocByTitle($title) {

        $cdir = scandir($this->dir);
        $resultList = array();
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", "..")) && pathinfo($value, PATHINFO_FILENAME) === $title) {
                if (pathinfo($value, PATHINFO_EXTENSION) === 'txt') {
                    //echo "mime es txt<br/>";
                    $mime = 'plain';
                } else {
                    //echo "mime es html<br/>";
                    $mime = 'html';
                }

                $handler = fopen($this->dir . DIRECTORY_SEPARATOR . $value, "r");
                if (FALSE === $handler) {
                    exit("Falló la apertura del flujo al URL");
                }

                $text = '';
                while (!feof($handler)) {
                    $text .= fread($handler, 8192);
                }
                fclose($handler);
                $docBuilder = new DocumentBuilder();
                $doc = $docBuilder->setMime($mime)
                                ->setTitle($title)
                                ->setContent($text)->build();
                array_push($resultList, $doc);
            }
        }
        return $resultList;
    }

//put your code here
}
