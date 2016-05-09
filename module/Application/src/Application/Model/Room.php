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
 * Description of Room
 *
 * @author janpa
 */
class Room implements InputFilterAwareInterface {
    
    public $id;
    public $label;
    public $designation;
    public $title;
    
    protected $inputFilter;
    
    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->label = (!empty($data['label'])) ? $data['label'] : null;
        $this->designation = (!empty($data['designation'])) ? $data['designation'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
    }
    
    public function getDataArray()
    {
        return array(
            'label' => $this->label,
            'designation' => $this->designation,
            'title' => $this->title,
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
                'name' => 'label',
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
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'title',
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
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'designation',
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
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}
