<?php

class Author_View_Helper_ThemeTermAndUri extends Zend_View_Helper_Abstract
{
    public function themeTermAndUri($termId, Author_Collection_TaxonomyMapper $mapper)
    {

        $data = $mapper->getTermAndUri($termId, $mapper);
        return $data;
    }
}

