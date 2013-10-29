<?php

class Author_View_Helper_TypeListLink extends Zend_View_Helper_Abstract
{
    public function typeListLink(Author_Collection_Work $work, Zend_View $view)
    {
        $workType = $work->getType();

        switch($workType) {
            case Author_Collection_WorkTypeConstants::TYPE_CHILDREN:
                $listLink = $this->view->translate('/works/children');
                break;

            case Author_Collection_WorkTypeConstants::TYPE_ESSAY:
                $listLink = $this->view->translate('/works/essays');
                break;

            case Author_Collection_WorkTypeConstants::TYPE_FICTION:
                $listLink = $this->view->translate('/works/fiction');
                break;

            case Author_Collection_WorkTypeConstants::TYPE_YOUNG:
                $listLink = $this->view->translate('/works/young');
                break;

            default:
                $listLink = $this->view->translate('/works');
                break;

        }
        return $listLink;
    }
}

