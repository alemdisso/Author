<?php

class Author_View_Helper_CheckThemeFromGet extends Zend_View_Helper_Abstract
{
    public function CheckThemeFromGet($data)
    {
        $validator = new Moxca_Util_ValidString();

            if ((isset($data['theme'])) && ($validator->isValid($data['theme']))) {
                $theme = $data['theme'];
                return $theme;
            }
            throw new Moxca_Util_Exception("Invalid theme from Get");

    }
}

