<?php

class ClientController extends Zend_Controller_Action
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
        $groupId = $this->getRequest()->getParam('groupId');
        $clientModel = new Application_Model_Client();
        
        $this->view->clients = $clientModel->getClientsByGroupId($groupId);
    }

    public function viewAction()
    {
        $clientId = $this->getRequest()->getParam('id');
        $clientModel = new Application_Model_Client();
        $client = $clientModel->fetchRow("clientId=$clientId")->toArray();
        
        $statementModel = new Application_Model_Statement();
        $fullStatement = $statementModel->getFullStatement($clientId);
        
        
        
        $groupId = $client['clientGroup'];
        $groupModel = new Application_Model_Group();
        $group = $groupModel->fetchRow("groupId=$groupId")->toArray();
        
        $this->view->client = $client;
        $this->view->group = $group;
        $this->view->fullStatement = $fullStatement;
    }

    public function addAction()
    {
        $clientForm = new Application_Form_Client();
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            unset($data['submit']);
            
            if($clientForm->isValid($data)){
                $clientModel = new Application_Model_Client();
                $clientModel->addClient($data);
                $this->redirect("/client/group/id/{$data['clientGroup']}");
            } else {
                $clientForm->populate($data);
            }
        }
        
        $this->view->form = $clientForm;
    }

    public function editAction()
    {
        $clientId = $this->getRequest()->getParam('id');
        $clientModel = new Application_Model_Client();
        $client = $clientModel->fetchRow("clientId=$clientId")->toArray();
        
        $clientForm = new Application_Form_Client();
        $clientForm->getElement('clientBalance')->setAttrib('disabled', 'disabled');
        
        $translate = Zend_Registry::get('Zend_Translate');
        $clientForm->addElement('submit','submit',array(
            'label' => $translate->translate('Edit Client')
        ));
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            unset($data['submit']);
            
            if($clientForm->isValid($data)){
                $clientModel = new Application_Model_Client();
                $clientModel->editClient($clientId, $data);
                $this->redirect("/client/view/id/$clientId");
            } else {
                $clientForm->populate($data);
            }
            
        } else {
            $clientForm->populate($client);
        }
        
        $this->view->client = $client;
        $this->view->form = $clientForm;
    }

    public function deleteAction()
    {
        $clientId = $this->getRequest()->getParam('id');
        $clientModel = new Application_Model_Client();
        
        $clientModel->deleteClient($clientId);
        
        $this->redirect('/client');
    }

    public function groupAction()
    {
        $groupId = $this->getRequest()->getParam('id');
        
        $groupModel = new Application_Model_Group();
        $this->view->group = $groupModel->fetchRow("groupId=$groupId")->toArray();
        
        
        $clientModel = new Application_Model_Client();
        $this->view->clients = $clientModel->getClientsByGroupId($groupId);
        
        //echo '<pre>';print_r($this->view->clients);die;
        
        $this->render('index');
    }

    public function searchAction()
    {
        $clientSearchForm = new Application_Form_Search();
        
        if($this->getRequest()->isPost()){
            
            $data = $this->getRequest()->getPost();
            $clientSearchForm->populate($data);
            
            $clientModel = new Application_Model_Client();
            
            $translate = Zend_Registry::get('Zend_Translate');
            $this->view->panelTitle = $translate->translate('Search Result');
            $this->view->clients = $clientModel->getClientsByName($data['clientName']);
            $this->render('index');
        }
        
        $this->view->form = $clientSearchForm;
    }

}
