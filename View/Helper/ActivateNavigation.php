<?php

class Author_View_Helper_ActivateNavigation extends Zend_View_Helper_Abstract
{
    public function activateNavigation(Zend_Controller_Request_Abstract $request, Zend_View_Interface $view)
    {
        $uri = $request->getPathInfo();
        $activeNav = $view->navigation()->findByUri($uri);
        try {
            if (!is_null($activeNav)) {
                $activeNav->active = true;
            }
        } catch (Exception $e) {
            //that´s ok, just trying
        }
    }
}

