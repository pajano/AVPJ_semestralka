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
 * Description of Terminal
 *
 * @author janpa
 */
class Terminal implements InputFilterAwareInterface {
    
    public $room;
    public $code;
    protected $inputFilter;
    
    public function exchangeArray($data)
    {
        $this->room = (!empty($data['room'])) ? $data['room'] : null;
        $this->code = (!empty($data['code'])) ? $data['code'] : null;
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
                'name' => 'room',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'IsInt',
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'code',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 8,
                            'max'      => 100,
                        ),
                    ),
                    array(
                        'name' => 'Alnum',
                    ),
                ),
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}
