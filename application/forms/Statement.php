<?php

class Application_Form_Statement extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('textarea','statementDescription', array(
            'label' => $this->getTranslator()->translate('Statement'),
            'required' => true,
            'cols' => 25,
            'rows' => 4,
        ));
        
        $this->addElement('text','statementPrice', array(
            'label' => $this->getTranslator()->translate('Price'),
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
        
        $this->addElement('text','statementPaid', array(
            'label' => $this->getTranslator()->translate('Paid'),
            //'required' => true,
            'validators' => array(
                array('Digits', false, array(
                        'messages' => array(
                            'notDigits' => "Invalid entry, ex. 10.00",
                            'digitsStringEmpty' => "",
                    ))),
                /*array('notEmpty', true, array(
                        'messages' => array(
                            'isEmpty' => 'Debit_amount can\'t be empty'
                        )
                )),*/

            ),
        ));
        
        $this->addElement('text','statementDate', array(
            'label' => $this->getTranslator()->translate('Date'),
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
                            'id'   => 'statementDatepicker',
                            'class' => 'statementDatepicker'
                        )
                    )
                )
            )
        );
        $this->dummy->clearValidators();
        
        $this->addElement('submit','submit',array(
            'label' => $this->getTranslator()->translate('Add Statement')
        ));
    }


}

