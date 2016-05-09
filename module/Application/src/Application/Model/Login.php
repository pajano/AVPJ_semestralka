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
 * Description of LoginForm
 *
 * @author janpa
 */
class LoginForm implements InputFilterAwareInterface {
    
    private $login;
    private $password;
    protected $inputFilter;
    
    public function exchangeArray($data)
    {
        $this->login = (!empty($data['login'])) ? $data['login'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
    }
    
    
    

}
