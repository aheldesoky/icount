<?php

class Application_Form_Client extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text','clientName', array(
            'label' => $this->getTranslator()->translate('Name'),
            'required' => true,
            'validators' => array(
                array('notEmpty', true, array(
                        'messages' => array(
                            'isEmpty' => $this->getTranslator()->translate('Value is required')
                        )
                ))
            )
        ));
        
        $groupModel = new Application_Model_Group();
        $groups = array();
        foreach ($groupModel->listGroups() as $group)
            $groups[$group['groupId']] = $group['groupName'];
        
        $this->addElement('select','clientGroup', array(
            'label' => $this->getTranslator()->translate('Group'),
            'required' => true,
            'multiOptions' => $groups,
        ));
        
        $this->addElement('text','clientPageNumber', array(
            'label' => $this->getTranslator()->translate('Page Number'),
            'required' => true,
            'validators' => array(
                array('notEmpty', true, array(
                        'messages' => array(
                            'isEmpty' => $this->getTranslator()->translate('Value is required')
                        )
                )),
                array('Digits', false, array(
                        'messages' => array(
                            'notDigits' => $this->getTranslator()->translate('Digits only allowed'),
                            'digitsStringEmpty' => $this->getTranslator()->translate('Digits only allowed')
                ))),
                /*
                array('db_NoRecordExists', true, array(
                        'table' => 'client', 
                        'field' => 'clientPageNumber', 
                        'messages' => $this->getTranslator()->translate('This page is assigned to another client')
                ))
                */
            )
        ));
        
        $this->addElement('text','clientPhone', array(
            'label' => $this->getTranslator()->translate('Phone Number'),
            'required' => false,
            'validators' => array(
                array('Digits', false, array(
                        'messages' => array(
                            'notDigits' => $this->getTranslator()->translate('Digits only allowed'),
                            'digitsStringEmpty' => $this->getTranslator()->translate('Digits only allowed')
                )))
            )
        ));
        
        $this->addElement('text','clientWork', array(
            'label' => $this->getTranslator()->translate('Work'),
            'required' => false,
            'validators' => array(
                array('notEmpty', true, array(
                        'messages' => array(
                            'isEmpty' => $this->getTranslator()->translate('Value is required')
                        )
                ))
            )
        ));
        
        $this->addElement('textarea','clientNotes', array(
            'label' => $this->getTranslator()->translate('Notes'),
            'required' => false,
            'cols' => 25,
            'rows' => 4,
        ));
        
        $this->addElement('text','clientBalance', array(
            'label' => $this->getTranslator()->translate('Initial Balance'),
            'required' => false,
            'validators' => array(
                array('Digits', false, array(
                        'messages' => array(
                            'notDigits' => $this->getTranslator()->translate('Digits only allowed'),
                            'digitsStringEmpty' => $this->getTranslator()->translate('Digits only allowed')
                )))
            ),
        ));
        
        $this->addElement('submit','submit',array(
            'label' => $this->getTranslator()->translate('Add Client'),
            'class' => 'btn btn-success'
        ));
    }


}

