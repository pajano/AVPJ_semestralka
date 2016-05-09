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
 * Description of Person
 *
 * @author janpa
 */
class Person implements InputFilterAwareInterface {
    
    public $id;
    public $firstName;
    public $lastName;
    public $titleBefore;
    public $titleBehind;
    public $active;
    
    protected $inputFilter;


    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->firstName = (!empty($data['firstName'])) ? $data['firstName'] : null;
        $this->lastName = (!empty($data['lastName'])) ? $data['lastName'] : null;
        $this->titleBefore = (!empty($data['titleBefore'])) ? $data['titleBefore'] : null;
        $this->titleBehind = (!empty($data['titleBehind'])) ? $data['titleBehind'] : null;
        $this->active = (!empty($data['active'])) ? $data['active'] : null;
    }
    
    public function getDataArray()
    {
        return array(
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'titleBefore' => $this->titleBefore,
            'titleBehind' => $this->titleBehind,
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
                'name' => 'titleBefore',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/([A-Za-z., ])+/',
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'firstName',
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
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                    array(
                        'name' => 'Alpha',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'lastName',
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
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                    array(
                        'name' => 'Alpha',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'titleBehind',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/([A-Za-z., ])+/',
                        ),
                    ),
                ),
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}
