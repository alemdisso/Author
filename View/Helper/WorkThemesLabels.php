<?php

class Author_View_Helper_WorkThemesLabels extends Zend_View_Helper_Abstract
{
    public function workThemesLabels($workId, Author_Collection_TaxonomyMapper $mapper)
    {
        $keywordsTermsAndUris = $mapper->getThemesRelatedToWork($workId);
        $keywordsLabels = array();
        foreach($keywordsTermsAndUris as $keywordUri => $keywordLabel) {


            $keywordsLabels[$keywordUri] = array('label' => $keywordLabel, 'uri' => $keywordUri);
        }
        return $keywordsLabels;
    }
}

