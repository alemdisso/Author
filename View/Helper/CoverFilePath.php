<?php

class Author_View_Helper_CoverFilePath extends Zend_View_Helper_Abstract
{
    public function coverFilePath(Author_Collection_Edition $editionObj, $noImgFilePath="/img/no_img.png", $type="md")
    {
        
        $filename = $editionObj->getCover();
        if ($filename == "") {
            $coverFileRelativePath = $noImgFilePath;
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
            $coverFileRelativePath = '/img/editions/' . $folder . '/' . $filename;
        }
        
        $coverFileAbsolutPath = APPLICATION_PATH . '/../public/' . $coverFileRelativePath;
        
        if ((!file_exists($coverFileAbsolutPath)) && ($noImgFilePath != "")) {
            $coverFileRelativePath = $noImgFilePath;
        }
 
        return $coverFileRelativePath;
    }
}

