<?php ?>
<!DOCTYPE html>
<!--
 
-->
<html>

    <title>PageVisualizer</title>

    <body>
        <div id="content" align="center">
            <h2>Error</h2>

            Sorry, an exception has been occurred<br>
            To continue, click the Back button.
            Please check the dataModel.ini file.<br>
            <h2><br>Details<br></h2>
            <?php echo $_GET['errMsg']; ?>
        </div>
    </body>
</html>



