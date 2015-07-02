<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="css/menu.css"></link>
    </head>
    <body>
        <nav>
            <ul>
                <li>
                    <a href="initial.php" target="contentframe">Instructive page</a><br/>
                </li>
                <?php
                require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'DocumentManager.php';
                $docMngr = new DocumentManager();
                $titles = $docMngr->getTitles();
                foreach ($titles as $value) {
                    echo '<li>' . PHP_EOL . '<a href="' . urldecode('HTMLView.php?title=' . $value) . '" target="contentframe">' . $value . '</a>' . PHP_EOL . '</li>';
                }
                ?>
            </ul>
        </nav>
    </body>
</html>
