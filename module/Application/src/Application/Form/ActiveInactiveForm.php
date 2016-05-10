<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of ActiveInactiveForm
 *
 * @author janpa
 */
class ActiveInactiveForm extends Form {
    
    public function __construct($name = null)
    {
        parent::__construct('activeInactiveForm');
        
        $this->add(array(
            'name' => 'typ',
            'type' => 'Select',
            'options' => array(
                'value_options' => array(
                    '1' => 'Aktívny',
                    '0' => 'Neaktívny',
                ),
            ),
            'attributes' => array(
                'class' => 'form-control',
                'onchange' => 'this.form.submit()',
            ),
        ));
    }
}
