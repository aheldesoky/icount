<?php

class StatementController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        $clientId = $this->getRequest()->getParam('clientId');
        $clientModel = new Application_Model_Client();
        $client = $clientModel->fetchRow("clientId=$clientId")->toArray();
        
        $statementForm = new Application_Form_Statement();
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            unset($data['submit']);
            
            if($statementForm->isValid($data)){
                $statementModel = new Application_Model_Statement();
                $data['statementClient'] = $clientId;
                $statementModel->addStatement($data);
                
                //For the first payment
                if($data['statementPaid'] > 0){
                    $payment['paymentClient'] = $clientId;
                    $payment['paymentAmount'] = $data['statementPaid'];
                    $payment['paymentDate'] = $data['statementDate'];
                    $paymentModel = new Application_Model_Payment();
                    $paymentModel->addPayment($payment);
                }
                
                $balance = $client['clientBalance']+$data['statementPrice']-$data['statementPaid'];
                $clientModel->update(
                        array('clientBalance' => $balance), 
                        "clientId=$clientId"
                );
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
        
        
        $this->view->client = $client;
    }

}