<?php

class Author_View_Helper_CheckTypeFromGet extends Zend_View_Helper_Abstract
{
    public function CheckTypeFromGet($data)
    {
        $validator = new Moxca_Util_ValidInteger();

        if ((isset($data['type'])) && ($validator->isValid($data['type']))) {
            $type = $data['type'];
            return $type;
        }
        throw new Moxca_Util_Exception("Invalid type from Get");

    }
}

