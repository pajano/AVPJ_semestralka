<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of TerminalForm
 *
 * @author janpa
 */
class TerminalForm extends Form {
    
    public function __construct(array $roomOptions, $name = null)
    {
        parent::__construct('terminalForm');
        
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'name' => 'room',
            'type' => 'Select',
            'options' => array(
                'label' => 'Miestnosť:',
                'value_options' => $roomOptions,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'AB315',
            ),
        ));
        
        $this->add(array(
            'name' => 'code',
            'type' => 'Password',
            'options' => array(
                'label' => 'Osobný kód:',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit-enter',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Vstup',
                'class' => 'btn btn-default btn-block'
            ),
        ));
        
        $this->add(array(
            'name' => 'submit-leave',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Odchod',
                'class' => 'btn btn-default btn-block'
            ),
        ));
    }
}
