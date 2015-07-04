<?php

class StatementController extends Zend_Controller_Action
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
        // action body
    }

    public function addAction()
    {
        $clientId = $this->getRequest()->getParam('clientId');
        $clientModel = new Application_Model_Client();
        $client = $clientModel->getClientById($clientId);
        //echo '<pre>';print_r($client);die;
        
        $statementForm = new Application_Form_Statement();
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            unset($data['submit']);
            
            if($statementForm->isValid($data)){
                $statementModel = new Application_Model_Statement();
                $data['statementClient'] = $clientId;
                $statementModel->addStatement($data);
                //print_r($data);die;
                
                $this->redirect("/client/view/id/$clientId");
            } else {
                $statementForm->populate($data);
            }
        }
        
        $this->view->client = $client;
        $this->view->form = $statementForm;
        
    }

    public function viewAction()
    {
        $clientId = $this->getRequest()->getParam('clientId');
        $clientModel = new Application_Model_Client();
        $client = $clientModel->fetchRow("clientId=$clientId")->toArray();
        
        $statementModel = new Application_Model_Statement();
        $fullStatement = $statementModel->getFullStatement($clientId);
        
        $this->view->client = $client;
        $this->view->fullStatement = $fullStatement;
    }

    public function editAction()
    {
        $statementId = $this->getRequest()->getParam('id');
        $statementModel = new Application_Model_Statement();
        $statement = $statementModel->getStatementById($statementId);
        //echo '<pre>';print_r($statement);die;
        
        $clientModel = new Application_Model_Client();
        $client = $clientModel->getClientById($statement['statementClient']);
        //echo '<pre>';print_r($client);die;
        
        $translate = Zend_Registry::get('Zend_Translate');
        $statementForm = new Application_Form_Statement();
        $statementForm->getElement('submit')
                      ->setLabel($translate->translate('Edit Statement'))
                      ->setAttrib('class', 'btn btn-warning');
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            unset($data['submit']);
            
            if($statementForm->isValid($data)){
                $statementModel = new Application_Model_Statement();
                $data['statementClient'] = $client['clientId'];
                $statementModel->editStatement($statementId, $data);
                //print_r($data);die;
                
                $this->redirect("/client/view/id/{$client['clientId']}");
            } else {
                $statementForm->populate($data);
            }
        } else  {
            $statementForm->populate($statement);
        }
        
        $this->view->client = $client;
        $this->view->statementId = $statementId;
        $this->view->form = $statementForm;
        
    }

    public function deleteAction()
    {
        $statementId = $this->getRequest()->getParam('id');
        $clientId = $this->getRequest()->getParam('cid');
        
        $statementModel = new Application_Model_Statement();
        $statementModel->deleteStatement($statementId);
        
        $this->redirect("/client/edit-statement/id/$clientId");
    }


}

