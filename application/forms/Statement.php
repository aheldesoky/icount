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
            'validators' => array(
                array('notEmpty', true, array(
                        'messages' => array(
                            'isEmpty' => $this->getTranslator()->translate('Value is required')
                        )
                ))
            ),
        ));
        
        $this->addElement('text','statementPrice', array(
            'label' => $this->getTranslator()->translate('Price'),
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
            ),
        ));
        
        $this->addElement('text','statementPaid', array(
            'label' => $this->getTranslator()->translate('Paid'),
            //'required' => true,
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
            ),
        ));
        
        $this->addElement('text','statementDateView', array(
            'label' => $this->getTranslator()->translate('Date'),
            'value' => date('Y-m-d'),
            'required' => false,
            'disabled' => 'disabled'
        ));
        
        $this->addElement('hidden','statementDate', array(
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

