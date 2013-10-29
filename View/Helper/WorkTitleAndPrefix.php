<?php

class Author_View_Helper_WorkTitleAndPrefix extends Zend_View_Helper_Abstract
{
    public function workTitleAndPrefix(Author_Collection_Work $work)
    {

        $prefix = $work->getPrefix();
        if ($prefix) {
            $title = $work->getTitle(true) . ", $prefix";
        } else {
            $title = $work->getTitle();
        }

        return $title;
    }
}

