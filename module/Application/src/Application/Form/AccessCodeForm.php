<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of AccessCodeForm
 *
 * @author janpa
 */
class AccessCodeForm extends Form {
    
    public function __construct($name = null)
    {
        parent::__construct('accessCodeForm');
        
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'name' => 'code',
            'type' => 'Text',
            'options' => array(
                'label' => 'Prístupový kód',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => '93321D5P',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit-add',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Pridať',
                'class' => 'btn btn-default btn-block'
            ),
        ));
        
        $this->add(array(
            'name' => 'submit-update',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Upraviť',
                'class' => 'btn btn-default btn-block'
            ),
        ));
    }
}
