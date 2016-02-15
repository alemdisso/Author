<?php

class Author_View_Helper_CoverFilePath extends Zend_View_Helper_Abstract
{
    public function coverFilePath(Author_Collection_Edition $editionObj, $noImgFilename="no_img.png", $type="md")
    {
        $filename = $editionObj->getCover();
        if ($filename == "") {
            $coverFilePath = "/img/$noImgFilename";
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
        }
            
        $coverFilePath = '/img/editions/' . $folder . '/' . $filename;
 
        return $coverFilePath;
    }
}

