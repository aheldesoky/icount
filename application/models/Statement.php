<?php

class Application_Model_Statement extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name= 'statement';
    
    public function addStatement($statement)
    {
        //$statement['statementDate'] = date('Y-m-d h:i:s');
        return $this->insert($statement);
    }
    
    public function editStatement($statementId, $statement)
    {
        return $this->update($statement, "statementId=$statementId");
    }
    
    public function getClientStatements($clientId)
    {
        return $this->fetchAll("statementClient=$clientId")->toArray();
    }
    
    public function listStatements()
    {
        return $this->fetchAll()->toArray();
    }
    
    public function deleteStatement($statementId)
    {
        return $this->delete("statementId=$statementId");
    }
    
    public function getFullStatement($clientId)
    {
        $statements = $this->select();
    	$statements->from(array('s' => 'statement'), array('statementDescription', 'statementPrice', 'statementPaid', 'statementDate'));
        $statements->where("s.statementClient=$clientId");
    	$resultStatements = $this->fetchAll($statements)->toArray();
        //echo '<pre>';print_r($resultStatements);//die;
        
        
        $payments = $this->select()->setIntegrityCheck(false);
    	$payments->from(array('p' => 'payment'), array(
            new Zend_Db_Expr ('"" AS statementDescription'), 
            new Zend_Db_Expr ('"0" AS statementPrice'), 
            'statementPaid' => 'paymentAmount', 
            'statementDate' => 'paymentDate'
            ));
        $payments->where("p.paymentClient=$clientId");
    	$resultPayments = $this->fetchAll($payments)->toArray();
        //echo '<pre>';print_r($resultPayments);die;
        
        
        $result = array_merge($resultStatements,$resultPayments);
        usort($result, 'self::date_compare');
        //echo '<pre>';print_r($result);die;
        
    	return $result;
    }
    
    protected static function date_compare($a, $b)
    {
        $t1 = strtotime($a['statementDate']);
        $t2 = strtotime($b['statementDate']);
        return $t1 - $t2;
        //return $a['statementDate'] - $b['statementDate'];
    }

}