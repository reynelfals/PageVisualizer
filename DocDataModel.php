<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocDataModel
 *
 * @author zayfals2015
 */
interface IDocDataModel {
    public function __construct();
    public function getTitles();
    public function getDocByTitle($title);

}
