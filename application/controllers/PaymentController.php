<?php

class PaymentController extends Zend_Controller_Action
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
        // action body
    }

    public function deleteAction()
    {
        // action body
    }


}







