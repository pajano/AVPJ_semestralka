<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Description of FormHelper
 *
 * @author janpa
 */
class FormHelper extends AbstractHelper {
    
    public function formItem($form, $name)
    {
        $view = $this->getView();
        echo $view->partial('application\helpers\form-item', array('form' => $form, 'name' => $name));
    }
    
    public function formSubmit($form, $name)
    {
        $view = $this->getView();
        echo $view->partial('application\helpers\form-item-submit', array('form' => $form, 'name' => $name));
    }
}
