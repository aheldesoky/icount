<?php

class GroupController extends Zend_Controller_Action
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
        $groupModel = new Application_Model_Group();
        $this->view->groups = $groupModel->listGroups();
    }

    public function addAction()
    {
        $groupForm = new Application_Form_Group();
        
        if($this->getRequest()->isPost()){
            $group = $this->getRequest()->getPost();
            unset($group['submit']);
            
            if($groupForm->isValid($group)){    
                $groupModel = new Application_Model_Group();
                $groupModel->addGroup($group);
                $this->redirect('/group');
            } else {
                $groupForm->populate($group);
            }
        }
        
        $this->view->form = $groupForm;
    }

    public function editAction()
    {
        $groupId = $this->getRequest()->getParam('id');
        $groupModel = new Application_Model_Group();
        $group = $groupModel->fetchRow("groupId=$groupId")->toArray();
        
        $groupForm = new Application_Form_Group();
        
        $translate = Zend_Registry::get('Zend_Translate');
        $groupForm->addElement('submit','submit',array(
            'label' => $translate->translate('Edit Group')
        ));
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            unset($data['submit']);
            
            if($groupForm->isValid($data)){
                $groupModel = new Application_Model_Group();
                $groupModel->editGroup($groupId, $data);
                $this->redirect('/group');
            } else {
                $groupForm->populate($data);
            }
            
        } else {
            $groupForm->populate($group);
        }
        
        $this->view->form = $groupForm;
    }

    public function deleteAction()
    {
        $groupId = $this->getRequest()->getParam('id');
        $groupModel = new Application_Model_Group();
        
        $groupModel->deleteGroup($groupId);
        
        $this->redirect('/group');
    }


}







