<?php

require_once 'DocHTML.php';
require_once 'DocText.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocumentBuilder
 *
 * @author zayfals2015
 */
class DocumentBuilder {

    private $mime = NULL;
    private $title = NULL;
    private $text = NULL;

    private function checkNotNull($obj, $varName) {
        if ($obj != NULL) {
            throw new Exception("'" + $varName + "' is already defined.");
        }
    }

    private function checkNull($obj, $varName) {
        if ($obj == NULL) {
            throw new Exception("'" + $varName + "' is not defined.");
        }
    }

    public function setMime($mime) {
        $this->checkNotNull($this->mime, "mime");
        $this->mime = $mime;
        return $this;
    }

    public function setTitle($title) {
        $this->checkNotNull($this->title, "title");
        $this->title = $title;
        return $this;
    }

    public function setContent($text) {
        $this->checkNotNull($this->text, "text");
        $this->text = $text;
        return $this;
    }

    public function build() {
        $this->checkNull($this->mime, "mime");
        $this->checkNull($this->title, "title");
        $this->checkNull($this->text, "text");

        if (strpos($this->mime, 'plain') === FALSE) {
            return new DocHTML($this->title, $this->text);
        }
        return new DocText($this->title, $this->text);
    }

}
