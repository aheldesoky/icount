<?php

class Application_Form_Client extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text','clientPageNumber', array(
            'label' => $this->getTranslator()->translate('Page Number'),
            'required' => true,
        ));
        
        $groupModel = new Application_Model_Group();
        $groups = array();
        foreach ($groupModel->listGroups() as $group)
            $groups[$group['groupId']] = $group['groupName'];
        
        //var_dump($groups);die;
        
        $this->addElement('select','clientGroup', array(
            'label' => $this->getTranslator()->translate('Group'),
            'required' => true,
            'multiOptions' => $groups,
        ));
        
        $this->addElement('text','clientName', array(
            'label' => $this->getTranslator()->translate('Name'),
            'required' => true,
        ));
        
        $this->addElement('text','clientPhone', array(
            'label' => $this->getTranslator()->translate('Phone Number'),
            'required' => true,
        ));
        
        $this->addElement('text','clientWork', array(
            'label' => $this->getTranslator()->translate('Work'),
            'required' => true,
        ));
        
        $this->addElement('textarea','clientNotes', array(
            'label' => $this->getTranslator()->translate('Notes'),
            'required' => true,
            'cols' => 25,
            'rows' => 4,
        ));
        
        $this->addElement('text','clientBalance', array(
            'label' => $this->getTranslator()->translate('Balance'),
            'required' => true,
        ));
        
        $this->addElement('submit','submit',array(
            'label' => $this->getTranslator()->translate('Add Client')
        ));
    }


}

