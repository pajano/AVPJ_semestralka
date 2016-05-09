<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of LoginForm
 *
 * @author janpa
 */
class RoomForm extends Form {
    
    public function __construct($name = null)
    {
        parent::__construct('roomForm');
        
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'name' => 'label',
            'type' => 'Text',
            'options' => array(
                'label' => 'Označenie',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'AB314',
            ),
        ));
        
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Názov',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Laboratórium',
            ),
        ));
        
        $this->add(array(
            'name' => 'designation',
            'type' => 'Text',
            'options' => array(
                'label' => 'Určenie',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Laboratórium inteligentných dopravných...',
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
