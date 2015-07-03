<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Parser
 *
 * @author zayfals2015
 */
class Parser {
    /* Convertir todos los URLs y correos electrÃ³nicos a links HTML vÃ¡lidos.
      Convertir todos los grupos de lÃ­neas de texto no vacÃ­as y consecutivas en pÃ¡rrafos HTML
      Convertir todos los pÃ¡rrafos que terminan con una lÃ­nea de - (guiÃ³n) o = (signo igual que) a HTML Headers de primer nivel (H1)
      Convertir todos los pÃ¡rrafos que empiezan con 2 o mÃ¡s signo de nÃºmeros (#) en HTML Headers de segundo nivel (H2) o otros niveles, dependiendo el nÃºmero de signos que tenga
      Convertir todos los pÃ¡rrafos que empiezan con asterisco items (li) de listas (ul). */

    static private function formatEmail(&$text) {
        $pattern = "/([a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+)/";
        $replacement = '<a href="mailto:$0">$0</a>';
        $text = preg_replace($pattern, $replacement, $text);
    }

    private static function formatURL(&$text) {

        $pattern = '#(http|https|ftp|ftps)\:\/\/[-a-zA-Z0-9:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si';
        $replacement = '<a href="$0">$0</a>';
        $text = preg_replace($pattern, $replacement, $text);
    }

    private static function formatH1(&$text) {

        $pattern = '/(.*)\n(={3,}|\-{3,})/';
        $replacement = '<h1>$1</h1>' . PHP_EOL;
        $text = preg_replace($pattern, $replacement, $text);
    }

    private static function formatHn(&$text) {
        $pattern = '/\n\s*(#{2,})(.*)/';
        $text = preg_replace_callback($pattern, 'self::headern', $text);
    }

    private static function headern($groups) {
        $charSeq = $groups[1];
        $n = strlen($charSeq);
        return PHP_EOL . '<h' . $n . '>' . $groups[2] . '</h' . $n . '>' . PHP_EOL;
    }

    private static function formatP(&$text) {
        $pattern = '/\n([^\n]+)/';
        $text = preg_replace_callback($pattern, 'self::paragraph', $text);
    }

    private static function paragraph($groups) {
        $original = $groups[1];
        $line = trim($original);
        if (preg_match('/(^<\/?(ul|ol|li|h|p|bl)|^$)/', $line)) {
            return $groups[0];
        }
        return '<p>' . $original . '</p>' . PHP_EOL;
    }

    private static function formatLi(&$text) {
        $pattern = '/^(\*.*\r?\n)+/m';
        $text = preg_replace_callback($pattern, 'self::unordered', $text);
    }

    private static function unordered($groups) {
        $line = $groups[0];
        $temp = preg_replace('/^\*(.*)/m', '    <li>$1</li>', $line);
        //$temp=preg_replace('/^\*/', '', $temp);
        return '<ul>' . PHP_EOL . $temp . '</ul>' . PHP_EOL;
    }

    public static function run(&$text) {
        $text = PHP_EOL . $text . PHP_EOL;
        self::formatEmail($text);
        self::formatURL($text);
        self::formatH1($text);
        self::formatHn($text);
        self::formatLi($text);
        self::formatP($text);
        return $text;
    }

}
