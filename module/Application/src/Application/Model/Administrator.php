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
 * Description of Administrator
 *
 * @author janpa
 */
class Administrator implements InputFilterAwareInterface {
    
    public $id;
    public $login;
    public $password;
    
    protected $inputFilter;


    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->login = (!empty($data['login'])) ? $data['login'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
    }
    
    public function getDataArray()
    {
        return array(
            'login' => $this->login,
            'password' => $this->password,
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
                'name' => 'login',
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
                            'max'      => 50,
                        ),
                    ),
                    array(
                        'name' => 'Alnum',
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'password',
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
                            'min'      => 6,
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
