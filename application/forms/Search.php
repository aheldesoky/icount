<?php

class Application_Form_Search extends Zend_Form
{

    public function init()
    {
        
        $this->setMethod('post');
        
        $this->addElement('text','clientName', array(
            'label' => $this->getTranslator()->translate('Name'),
            'required' => true,
        ));
        
        $groupModel = new Application_Model_Group();
        $groups = array('' => $this->getTranslator()->translate('Any Group'));
        foreach ($groupModel->listGroups() as $group)
            $groups[$group['groupId']] = $group['groupName'];
        
        //var_dump($groups);die;
        
        $this->addElement('select','clientGroup', array(
            'label' => $this->getTranslator()->translate('Group'),
            'required' => true,
            'multiOptions' => $groups,
        ));
        
        $this->addElement('submit','submit',array(
            'label' => $this->getTranslator()->translate('Search')
        ));
    }


}

