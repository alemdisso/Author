<?php

class Author_View_Helper_WorkKeywordsLabels extends Zend_View_Helper_Abstract
{
    public function workKeywordsLabels($workId, Author_Collection_TaxonomyMapper $mapper)
    {
        $keywordsTermsAndUris = $mapper->getKeywordsRelatedToWork($workId);
        $keywordsLabels = array();
        foreach($keywordsTermsAndUris as $keywordUri => $keywordLabel) {


            $keywordsLabels[$keywordUri] = array('label' => $keywordLabel, 'uri' => $keywordUri);
        }
        return $keywordsLabels;
    }
}

