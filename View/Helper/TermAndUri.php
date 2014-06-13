<?php

class Author_View_Helper_TermAndUri extends Zend_View_Helper_Abstract
{
    public function termAndUri($termId, Author_Collection_TaxonomyMapper $mapper)
    {

        $data = $mapper->getTermAndUri($termId, $mapper);
        return $data;
    }
}

