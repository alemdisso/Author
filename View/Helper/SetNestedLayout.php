<?php

class Author_View_Helper_SetNestedLayout extends Zend_View_Helper_Abstract
{
    public function setNestedLayout(Zend_Layout_Controller_Action_Helper_Layout $layoutHelper, $layoutName="inner.phtml")
    {
        $layout = $layoutHelper->getLayoutInstance();
        $layout->nestedLayout = $layoutName;

        return $layout;
    }
}

