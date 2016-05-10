<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of AddAccessRightForm
 *
 * @author janpa
 */
class AddAccessRightForm extends Form {
    
    public function __construct(array $roomOptions, $name = null)
    {
        parent::__construct('addAccessRightForm');
        
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'type' => 'Select',
            'name' => 'room',
            'options' => array(
                'label' => 'Miestnosť',
                'value_options' => $roomOptions,
            ),
            'attributes' => array(
                'class' => 'form-control',
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
