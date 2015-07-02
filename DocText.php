<?php
require_once 'Document.php';
require_once 'Parser.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocText
 *
 * @author zayfals2015
 */
class DocText extends Document{
    

    public function toHTML() {
        $strtemp=  $this->content;
        Parser::run($strtemp);
        return $strtemp;
    }

//put your code here
}
