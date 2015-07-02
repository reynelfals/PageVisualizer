<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>File Browser</title>
        <script type="text/javascript">


            function myfunction(title) {

                parent.document.getElementById("contentframeid").src = title;
                parent.document.getElementById("menuframeid").src = "Viewers/menu.php";
            }
        </script>
        <link rel="stylesheet" href="css/filebrowser.css"></link>
    </head>
    <body>
        <form action="filebrowser.php" method="post" enctype="multipart/form-data">
            <label for="fileToUpload">Browse for file</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload File" name="submit">

        </form>
        <?php
        if (isset($_POST["submit"])) {
            require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'FilePersist.php';
            $title = FilePersist::persist();
            if ($title != NULL) {
                $urldec = urldecode('Viewers/HTMLView.php?title=' . $title);
                echo '<script type="text/javascript">',
                'myfunction("' . $urldec . '");',
                '</script><br/>';
                echo PHP_EOL . '<a href="' . urldecode('HTMLView.php?title=' . $title) . '" target="contentframe">' . $title . '</a>';
            }
        }
        ?>
    </body>
</html>
