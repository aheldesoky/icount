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
                array('Digits', false, array(
                        'messages' => array(
                            'notDigits' => "Invalid entry, ex. 10.00",
                            'digitsStringEmpty' => "",
                    ))),
                array('notEmpty', true, array(
                        'messages' => array(
                            'isEmpty' => 'Debit_amount can\'t be empty'
                        )
                )),

            ),
        ));
        
        $this->addElement('text','paymentDate', array(
            'label' => $this->getTranslator()->translate('Payment Date'),
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

