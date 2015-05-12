<?php

class Application_Model_Client extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name= 'client';
    
    public function addClient($client)
    {
        $client['clientCreationDate'] = date('Y-m-d h:i:s');
        return $this->insert($client);
    }
    
    public function editClient($clientId, $client)
    {
        return $this->update($client, "clientId=$clientId");
    }
    
    public function listClients()
    {
        return $this->fetchAll()->toArray();
    }
    
    public function getClientsByGroupId($groupId)
    {
        $joinStatement = $this->select()->setIntegrityCheck(false);
    	$joinStatement->from(array('c' => 'client'));
    	$joinStatement->joinLeft(   array('s' => 'statement'), 'c.clientId = s.statementClient', 
                            array('clientGoods' => new Zend_Db_Expr('SUM(CASE WHEN s.statementPrice IS NOT NULL THEN s.statementPrice ELSE 0 END)'), 
                                  'clientPaid' => new Zend_Db_Expr('SUM(CASE WHEN s.statementPaid IS NOT NULL THEN s.statementPaid ELSE 0 END)')
                            )
                );
        $joinStatement->where("c.clientGroup=$groupId");
        $joinStatement->group('c.clientId', 'p.paymentId');
    	$joinStatement->order('c.clientPageNumber ASC');
    	$resultJoinStatement = $this->fetchAll($joinStatement)->toArray();
        //echo '<pre>';print_r($resultJoinStatement);die;
        
        
        $joinPayment = $this->select()->setIntegrityCheck(false);
    	$joinPayment->from(array('c' => 'client'), array());
        $joinPayment->joinLeft( array('p' => 'payment'), 'c.clientId = p.paymentClient', 
                           array('clientPaid'=>new Zend_Db_Expr('SUM(CASE WHEN p.paymentAmount IS NOT NULL THEN p.paymentAmount ELSE 0 END)'))
                );
        $joinPayment->where("c.clientGroup=$groupId");
        $joinPayment->group('c.clientId', 'p.paymentId');
    	$joinPayment->order('c.clientPageNumber ASC');
    	$resultJoinPayment = $this->fetchAll($joinPayment)->toArray();
        //echo '<pre>';print_r($resultJoinPayment);die;
        
        $i = -1;
        foreach ($resultJoinStatement as &$client){
            $i++;
            $client['clientPaid'] += $resultJoinPayment[$i]['clientPaid'];
        }
        //echo '<pre>';print_r($resultJoinStatement);die;
        
    	return $resultJoinStatement;
        //return $this->fetchAll("clientGroup=$groupId", "clientPageNumber ASC");
    }
    
    public function getClientsByName($clientName)
    {
        $joinStatement = $this->select()->setIntegrityCheck(false);
    	$joinStatement->from(array('c' => 'client'));
    	$joinStatement->joinLeft(   array('s' => 'statement'), 'c.clientId = s.statementClient', 
                            array('clientGoods' => new Zend_Db_Expr('SUM(CASE WHEN s.statementPrice IS NOT NULL THEN s.statementPrice ELSE 0 END)'), 
                                  'clientPaid' => new Zend_Db_Expr('SUM(CASE WHEN s.statementPaid IS NOT NULL THEN s.statementPaid ELSE 0 END)')
                            )
                );
        $joinStatement->where("c.clientName LIKE '%$clientName%'");
        $joinStatement->group('c.clientId', 'p.paymentId');
    	$joinStatement->order('c.clientPageNumber ASC');
    	$resultJoinStatement = $this->fetchAll($joinStatement)->toArray();
        //echo '<pre>';print_r($resultJoinStatement);die;
        
        
        $joinPayment = $this->select()->setIntegrityCheck(false);
    	$joinPayment->from(array('c' => 'client'), array());
        $joinPayment->joinLeft( array('p' => 'payment'), 'c.clientId = p.paymentClient', 
                           array('clientPaid'=>new Zend_Db_Expr('SUM(CASE WHEN p.paymentAmount IS NOT NULL THEN p.paymentAmount ELSE 0 END)'))
                );
        $joinPayment->where("c.clientName LIKE '%$clientName%'");
        $joinPayment->group('c.clientId', 'p.paymentId');
    	$joinPayment->order('c.clientPageNumber ASC');
    	$resultJoinPayment = $this->fetchAll($joinPayment)->toArray();
        //echo '<pre>';print_r($resultJoinPayment);die;
        
        $i = -1;
        foreach ($resultJoinStatement as &$client){
            $i++;
            $client['clientPaid'] += $resultJoinPayment[$i]['clientPaid'];
        }
        //echo '<pre>';print_r($resultJoinStatement);die;
        
    	return $resultJoinStatement;
        //return $this->fetchAll("clientGroup=$groupId", "clientPageNumber ASC");
    }
    
    
    public function deleteClient($clientId)
    {
        return $this->delete("clientId=$clientId");
    }
}

