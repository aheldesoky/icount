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
        return $this->fetchAll("clientGroup=$groupId", "clientPageNumber ASC");
    }
    
    public function deleteClient($clientId)
    {
        return $this->delete("clientId=$clientId");
    }
}

