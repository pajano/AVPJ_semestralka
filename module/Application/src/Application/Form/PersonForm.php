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
class PersonForm extends Form {
    
    public function __construct($freeCodes, $actualCode = null, $name = null)
    {
        parent::__construct('personForm');
        
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'name' => 'titleBefore',
            'type' => 'Text',
            'options' => array(
                'label' => 'Titul pred menom',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Ing.',
            ),
        ));
        
        $this->add(array(
            'name' => 'firstName',
            'type' => 'Text',
            'options' => array(
                'label' => 'Meno',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Janko',
            ),
        ));
        
        $this->add(array(
            'name' => 'lastName',
            'type' => 'Text',
            'options' => array(
                'label' => 'Priezvisko',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Hraško',
            ),
        ));
        
        $this->add(array(
            'name' => 'titleBehind',
            'type' => 'Text',
            'options' => array(
                'label' => 'Titul za menom',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'PhD.',
            ),
        ));
        
        if($actualCode)
        {
            $this->add(array(
                'name' => 'code',
                'type' => 'Select',
                'options' => array(
                    'label' => 'Prístupový kód',
                    'value_options' => array(
                        'actual' => array(
                            'label' => 'Aktuálny kód',
                            'options' => $actualCode,
                        ),
                        'free' => array(
                            'label' => 'Voľné kódy',
                            'options' => $freeCodes,
                        ),
                    ),
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));
        }
        else
        {
            $this->add(array(
                'name' => 'code',
                'type' => 'Select',
                'options' => array(
                    'label' => 'Prístupový kód',
                    'value_options' => $freeCodes,
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));
        }
        
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
