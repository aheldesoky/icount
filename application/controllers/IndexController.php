<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        //checking if user is authenticated or not
        $authorization = Zend_Auth::getInstance();
        if(!$authorization->hasIdentity()) {
            $this->redirect('/auth/login');
        }
    }

    public function indexAction()
    {
        
    }


}

