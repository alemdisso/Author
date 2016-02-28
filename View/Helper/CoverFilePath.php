<?php

class Author_View_Helper_CoverFilePath extends Zend_View_Helper_Abstract
{
    public function coverFilePath(Author_Collection_Edition $editionObj, $noImgFilename="no_img.png", $type="md")
    {
        
        $filename = $editionObj->getCover();
        $noImagePath = "/img/$noImgFilename";
        if ($filename == "") {
            $coverFilePath = $noImagePath;
        } else {
            switch ($type) {
                case "tb":
                case "md":
                case "raw" :
                    $folder = $type;
                    break;
                
                default:
                    $folder = "md";
                    break;
            }
            $coverFilePath = '/img/editions/' . $folder . '/' . $filename;
        }
        
        if (!file_exists(APPLICATION_PATH . '/../public/' . $coverFilePath)) {
            $coverFilePath = $noImagePath;
        }
 
        return $coverFilePath;
    }
}

