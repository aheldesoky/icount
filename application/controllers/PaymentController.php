<?php

class PaymentController extends Zend_Controller_Action
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
        //$client = $clientModel->fetchRow("clientId=$clientId")->toArray();
        $client = $clientModel->getClientById($clientId);
        //echo '<pre>';print_r($client);die;
        
        $paymentForm = new Application_Form_Payment();
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            unset($data['submit']);
            
            if($paymentForm->isValid($data)){
                $paymentModel = new Application_Model_Payment();
                $data['paymentClient'] = $clientId;
                $paymentModel->addPayment($data);
                /*$clientModel->update(
                        array('clientBalance' => $client['clientBalance']-$data['paymentAmount']), 
                        "clientId=$clientId"
                );*/
                $this->redirect("/client/view/id/$clientId");
            } else {
                $paymentForm->populate($data);
            }
        }
        
        
        $this->view->client = $client;
        $this->view->form = $paymentForm;
    }

    public function editAction()
    {
        $paymentId = $this->getRequest()->getParam('id');
        $paymentModel = new Application_Model_Payment();
        $payment = $paymentModel->getPaymentById($paymentId);
        
        $clientModel = new Application_Model_Client();
        $client = $clientModel->getClientById($payment['paymentClient']);
        //echo '<pre>';print_r($client);die;
        
        $translate = Zend_Registry::get('Zend_Translate');
        $paymentForm = new Application_Form_Payment();
        $paymentForm->getElement('submit')
                    ->setLabel($translate->translate('Edit Payment'))
                    ->setAttrib('class', 'btn btn-warning');
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            unset($data['submit']);
            
            if($paymentForm->isValid($data)){
                $paymentModel = new Application_Model_Payment();
                $data['paymentClient'] = $client['clientId'];
                $paymentModel->editPayment($paymentId, $data);
                
                $this->redirect("/client/view/id/{$client['clientId']}");
            } else {
                $paymentForm->populate($data);
            }
        } else {
            $paymentForm->populate($payment);
        }
        
        
        $this->view->client = $client;
        $this->view->form = $paymentForm;
    }

    public function deleteAction()
    {
        // action body
    }


}







