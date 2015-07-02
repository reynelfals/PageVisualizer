<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Document
 *
 * @author zayfals2015
 */
abstract class Document {

    protected $title;
    protected $content;

    public function __construct($title, $content) {
        $this->title = $title;
        $this->content = $content;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    private function getHeader() {
        $doctype = '<!DOCTYPE html>' . PHP_EOL;
        $headOpen = '<html>' . PHP_EOL . '    <head>' . PHP_EOL . '        <title>';
        $headClose = '</title>' . PHP_EOL . '    </head>' . PHP_EOL . '    <body>' . PHP_EOL;
        return $doctype . $headOpen . $this->title . $headClose;
    }

    private function getFooter() {
        $bodyClose = '</body>' . PHP_EOL . '</html>';
        return $bodyClose;
    }

    public function process() {
        return $this->getHeader() . $this->toHTML();
    }

    abstract public function toHTML();
}
