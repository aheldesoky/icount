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
    
    public function getClientById($clientId)
    {
        $joinStatement = $this->select()->setIntegrityCheck(false);
    	$joinStatement->from(array('c' => 'client'));
    	$joinStatement->joinLeft(   array('s' => 'statement'), 'c.clientId = s.statementClient', 
                            array('clientGoods' => new Zend_Db_Expr('SUM(CASE WHEN s.statementPrice IS NOT NULL THEN s.statementPrice ELSE 0 END)'), 
                                  'clientPaid' => new Zend_Db_Expr('SUM(CASE WHEN s.statementPaid IS NOT NULL THEN s.statementPaid ELSE 0 END)')
                            )
                );
        $joinStatement->where("c.clientId=$clientId");
        $joinStatement->group('c.clientId', 'p.paymentId');
    	$resultJoinStatement = $this->fetchRow($joinStatement)->toArray();
        //echo '<pre>';print_r($resultJoinStatement);die;
        
        
        $joinPayment = $this->select()->setIntegrityCheck(false);
    	$joinPayment->from(array('c' => 'client'), array());
        $joinPayment->joinLeft( array('p' => 'payment'), 'c.clientId = p.paymentClient', 
                           array('clientPaid'=>new Zend_Db_Expr('SUM(CASE WHEN p.paymentAmount IS NOT NULL THEN p.paymentAmount ELSE 0 END)'))
                );
        $joinPayment->where("c.clientId=$clientId");
        $joinPayment->group('c.clientId', 'p.paymentId');
    	$resultJoinPayment = $this->fetchRow($joinPayment)->toArray();
        //echo '<pre>';print_r($resultJoinPayment);die;
        
        $resultJoinStatement['clientPaid'] += $resultJoinPayment['clientPaid'];
        //echo '<pre>';print_r($resultJoinStatement);die;
        
    	return $resultJoinStatement;
        //return $this->fetchAll("clientGroup=$groupId", "clientPageNumber ASC");
    }
    
    public function countClientsByGroupId($groupId = null){
        $select = $this->select()->from("client", array("totalClients"=>"COUNT(*)"));
        if($groupId)
            $select->where("clientGroup=$groupId");
        $result = $this->fetchRow($select)->toArray();
        return $result['totalClients'];
    }
    
    public function getClientsByGroupId($groupId, $page, $clientsPerPage)
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
        $joinStatement->limitPage($page, $clientsPerPage);
    	$resultJoinStatement = $this->fetchAll($joinStatement)->toArray();
        //echo '<pre>';print_r($resultJoinStatement);die;
        
        
        $joinPayment = $this->select()->setIntegrityCheck(false);
    	$joinPayment->from(array('c' => 'client'), array());
        $joinPayment->joinLeft( array('p' => 'payment'), 'c.clientId = p.paymentClient', 
                           array('clientPaid'=>new Zend_Db_Expr('SUM(CASE WHEN p.paymentAmount IS NOT NULL THEN p.paymentAmount ELSE 0 END)'), 
                                 'lastPaid'=>new Zend_Db_Expr('MAX(p.paymentDate)')
                           )
                );
        $joinPayment->where("c.clientGroup=$groupId");
        $joinPayment->group('c.clientId', 'p.paymentId');
    	$joinPayment->order('c.clientPageNumber ASC');
        $joinStatement->limitPage($page, 2);
    	$resultJoinPayment = $this->fetchAll($joinPayment)->toArray();
        //echo '<pre>';print_r($resultJoinPayment);die;
        
        $i = -1;
        foreach ($resultJoinStatement as &$client){
            $i++;
            $client['clientPaid'] += $resultJoinPayment[$i]['clientPaid'];
            $client['lastPaid'] = $resultJoinPayment[$i]['lastPaid'];
        }
        //echo '<pre>';print_r($resultJoinStatement);die;
        
    	return $resultJoinStatement;
        //return $this->fetchAll("clientGroup=$groupId", "clientPageNumber ASC");
    }
    
    public function getClientsByFilter($filter)
    {
        $joinStatement = $this->select()->setIntegrityCheck(false);
    	$joinStatement->from(array('c' => 'client'));
    	$joinStatement->joinLeft(   array('s' => 'statement'), 'c.clientId = s.statementClient', 
                            array('clientGoods' => new Zend_Db_Expr('SUM(CASE WHEN s.statementPrice IS NOT NULL THEN s.statementPrice ELSE 0 END)'), 
                                  'clientPaid' => new Zend_Db_Expr('SUM(CASE WHEN s.statementPaid IS NOT NULL THEN s.statementPaid ELSE 0 END)')
                            )
                );
        $joinStatement->where("c.clientName LIKE '%${filter['clientName']}%'");
        if($filter['clientGroup'])
            $joinStatement->where ("c.clientGroup={$filter['clientGroup']}");
        $joinStatement->group('c.clientId', 'p.paymentId');
    	$joinStatement->order('c.clientPageNumber ASC');
    	$resultJoinStatement = $this->fetchAll($joinStatement)->toArray();
        //echo '<pre>';print_r($resultJoinStatement);die;
        
        
        $joinPayment = $this->select()->setIntegrityCheck(false);
    	$joinPayment->from(array('c' => 'client'), array());
        $joinPayment->joinLeft( array('p' => 'payment'), 'c.clientId = p.paymentClient', 
                           array('clientPaid'=>new Zend_Db_Expr('SUM(CASE WHEN p.paymentAmount IS NOT NULL THEN p.paymentAmount ELSE 0 END)'))
                );
        $joinPayment->where("c.clientName LIKE '%${filter['clientName']}%'");
        if($filter['clientGroup'])
            $joinStatement->where ("c.clientGroup={$filter['clientGroup']}");
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

