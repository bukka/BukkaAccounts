<?php

namespace BukkaAccounts\Form;

use Zend\Form\Form;

class IncomeForm extends Form
{
    /**
     * Create form
     * @param mixed $name
     */
    public function __construct($name = null)
    {
        parent::__construct('income');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'price',
            'type' => 'Text',
            'options' => array(
                'label' => 'Price',
            ),
        ));
        
        $this->add(array(
            'name' => 'invoice_id',
            'type' => 'Text',
            'options' => array(
                'label' => 'Invoice ID',
            ),
        ));
        
        $this->add(array(
            'name' => 'description',
            'type' => 'Text',
            'options' => array(
                'label' => 'Description',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}

