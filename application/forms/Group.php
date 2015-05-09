<?php

class Application_Form_Group extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text','groupName', array(
            'label' => $this->getTranslator()->translate('Group Name'),
            'required' => true,
            /*'decorators' => array(
                array('ViewHelper'), 
                array('Label', array(
                    'tag' => 'dt'
                    )
                ),
                array('HtmlTag', array(
                    'tag' => 'div',
                    'openOnly' => true,
                    'id' => 'addGroupForm',
                    'placement' => 'prepend',
                    )
                ),
            ),*/
        ));
        
        $this->addElement('submit','submit',array(
            'label' => $this->getTranslator()->translate('Add Group')
        ));
        
    }


}

