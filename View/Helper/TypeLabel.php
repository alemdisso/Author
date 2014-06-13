<?php

class Author_View_Helper_TypeLabel extends Zend_View_Helper_Abstract
{
    public function typeLabel($workType, Author_Collection_WorkTypes $types, Zend_View $view)
    {
        return $view->translate($types->TitleForType($workType));
    }
}

