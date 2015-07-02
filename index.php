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
        <link rel="stylesheet" href="Viewers/css/index.css"></link>
    </head>
    <body>
        <?php
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'PageVisualizer' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'DocumentManager.php';
        // put your code here
        $myarr = [1, 1, 3, 5, 6, 8, 10, 11, 10, 9, 8, 9, 10, 11, 7];

        function getSeqIndex($arr) {
            $arrOut = [];
            $before = 0;
            for ($i = 0; $i < count($arr) - 1; $i++) {
                $diff = $arr[$i] - $arr[$i + 1];
                if ($diff != $before && ($diff == 1 || $diff == -1)) {
                    array_push($arrOut, $i);
                }
                $before = $diff;
            }
            if (count($arrOut) > 0) {
                return $arrOut;
            } else {
                return null;
            }
        }
?>
        
        <div id="header"></div>
        <div id="content">
            <iframe  src="Viewers/menu.php" name="menuframe" id="menuframeid">
            <p>iframe not supported by the browser.</p>
            </iframe>
            <iframe  src="Viewers/initial.php" name="contentframe" id="contentframeid">
            <p>iframe not supported by the browser.</p>
            </iframe>
        </div>
        <div id="footer"></div>
        
    </body>
</html>
