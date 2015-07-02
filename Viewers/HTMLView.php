<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'DocumentManager.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_REQUEST['title'])){
    $initTime = microtime(true);
    $title=$_REQUEST['title'];
    $docMngr=new DocumentManager();
    $docs=$docMngr->getDocumentByTitle($title);
    echo $docs[0]->process();
    $endTime=  microtime(true);
    $totalTime=$endTime-$initTime;
    echo PHP_EOL.'    <div align="center">'
            .PHP_EOL.'       <h6>Tiempo de ejecución: '
            .$totalTime.' segundos.</h6>'
            .PHP_EOL.'    </div>'
            .PHP_EOL.'    </body>'
            .PHP_EOL.'</html>';
}

