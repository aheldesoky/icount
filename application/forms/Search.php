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
        
        $this->addElement('submit','submit',array(
            'label' => $this->getTranslator()->translate('Search')
        ));
    }


}

