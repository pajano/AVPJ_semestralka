<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Description of AccessCode
 *
 * @author janpa
 */
class AccessCode implements InputFilterAwareInterface {
    
    public $id;
    public $refPerson;
    public $code;
    public $active;
    
    protected $inputFilter;
    
    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->refPerson = (!empty($data['ref_personel'])) ? $data['ref_personel'] : null;
        $this->code = (!empty($data['code'])) ? $data['code'] : null;
        $this->active = (!empty($data['active'])) ? $data['active'] : null;
    }
    
    public function getDataArray()
    {
        return array(
            'ref_personel' => $this->refPerson,
            'code' => $this->code,
            'active' => $this->active,
        );
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter) 
    {
        throw new Exception("setInputFilter is not used in this model");
    }
    
    public function getInputFilter() 
    {
        if(!$this->inputFilter)
        {
            $inputFilter = new InputFilter();
            
            $inputFilter->add(array(
                'name' => 'code',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Alnum',
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 8,
                            'max' => 100,
                        ),
                    ),
                ),
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}
