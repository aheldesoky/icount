<?php

class Application_Model_Payment extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name= 'payment';
    
    public function addPayment($payment)
    {
        return $this->insert($payment);
    }
    
    public function editPayment($paymentId, $payment)
    {
        return $this->update($payment, "paymentId=$paymentId");
    }
    
    public function getPaymentById($paymentId)
    {
        return $this->fetchRow("paymentId=$paymentId")->toArray();
    }
    
    public function getClientPayments($clientId)
    {
        return $this->fetchAll("paymentClient=$clientId")->toArray();
    }
    
    public function listPayments()
    {
        return $this->fetchAll()->toArray();
    }
    
    public function deletePayment($paymentId)
    {
        return $this->delete("paymentId=$paymentId");
    }

}

