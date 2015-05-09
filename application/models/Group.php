<?php

class Application_Model_Group extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name= 'group';
    
    public function addGroup($group)
    {
        return $this->insert($group);
    }
    
    public function editGroup($groupId, $group)
    {
        return $this->update($group, "groupId=$groupId");
    }
    
    public function listGroups()
    {
        return $this->fetchAll()->toArray();
    }
    
    public function deleteGroup($groupId)
    {
        return $this->delete("groupId=$groupId");
    }
}

