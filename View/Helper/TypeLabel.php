<?php

class Author_View_Helper_TypeLabel extends Zend_View_Helper_Abstract
{
    public function typeLabel(Author_Collection_Work $work, Author_Collection_WorkTypes $types, Zend_View $view)
    {
        $workType = $work->getType();
        return $view->translate($types->TitleForType($workType));
    }
}

