<?php

class Author_View_Helper_CoverFilePath extends Zend_View_Helper_Abstract
{
    public function coverFilePath(Author_Collection_Edition $editionObj, $noImgFilename="no_img.png")
    {
        $filename = $editionObj->getCover();
        if ($filename == "") {
            $coverFilePath = "/img/$noImgFilename";
        } else {
            $coverFilePath = '/img/editions/raw/' . $filename;
        }

        return $coverFilePath;
    }
}

