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
class LoginForm extends Form {
    
    public function __construct($name = null)
    {
        parent::__construct('loginForm');
        
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'name' => 'login',
            'type' => 'Text',
            'options' => array(
                'label' => 'Login:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'admin',
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Heslo:',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Prihlásiť',
                'class' => 'btn btn-default btn-block'
            ),
        ));
    }
}
