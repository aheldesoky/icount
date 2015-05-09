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

}