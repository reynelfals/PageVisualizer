<?php

defined('INI_FILE') or define('INI_FILE', 'dataModel.ini');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FilePersist
 *
 * @author zayfals2015
 */
class FilePersist {

    const FILE_DIR_KEY = 'dir_root';

    public static function persist() {
        $ini_array = parse_ini_file(INI_FILE);
        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . $ini_array[FilePersist::FILE_DIR_KEY];
        $target_dir = $dir . DIRECTORY_SEPARATOR;
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
// Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($fileType != "txt" && $imageFileType != "html") {
            echo "Sorry, only TXT or HTML files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $title = basename($_FILES["fileToUpload"]["name"]);
                echo "The file " . $title . " has been uploaded.";
                $title = pathinfo($title, PATHINFO_FILENAME);
                return $title;
            } else {
                echo "Sorry, there was an error uploading your file.";
                return NULL;
            }
        }
    }

}
