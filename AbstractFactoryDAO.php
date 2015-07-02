<?php

require_once 'SQLDataModel.php';
require_once 'FileDataModel.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractFactory
 *
 * @author zayfals2015
 */
class AbstractFactoryDAO {

    public static function getDao($key) {
        if (strpos($key, 'SQL') === FALSE) {
            return new FileDataModel();
        }
        return new SQLDataModel();
    }

}
