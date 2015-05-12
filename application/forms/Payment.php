<?php

class Application_Form_Payment extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text','paymentAmount', array(
            'label' => $this->getTranslator()->translate('Payment Amount'),
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
                )))
            )
        ));
        
        $this->addElement('text','paymentDateView', array(
            'label' => $this->getTranslator()->translate('Payment Date'),
            'value' => date('Y-m-d'),
            'required' => false,
            'disabled' => 'disabled'
        ));
        
        $this->addElement('hidden','paymentDate', array(
            'value' => date('Y-m-d'),
            'required' => true,
        ));
        
        $this->addElement(
            'hidden',
            'dummy',
            array(
                'required' => false,
                'ignore' => true,
                'autoInsertNotEmptyValidator' => false,
                'decorators' => array(
                    array(
                        'HtmlTag', array(
                            'tag'  => 'div',
                            'id'   => 'paymentDatepicker',
                            'class' => 'paymentDatepicker'
                        )
                    )
                )
            )
        );
        $this->dummy->clearValidators();
        
        $this->addElement('submit','submit',array(
            'label' => $this->getTranslator()->translate('Add Payment')
        ));
    }


}

