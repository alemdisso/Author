<?php

class Author_View_Helper_ThemesList extends Zend_View_Helper_Abstract
{
    public function themesList(Author_Collection_TaxonomyMapper $mapper)
    {
        $rawLabelsArray = $mapper->getAllThemesAlphabeticallyOrdered();

        $labelsArray = array();

        foreach($rawLabelsArray as $k => $label) {
            $labelsArray[$k] = $label;
        }

        return $labelsArray;

    }
}

