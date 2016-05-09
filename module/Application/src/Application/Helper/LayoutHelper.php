<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

use Application\Controller\Plugin\DataAccessPlugin;

/**
 * Description of LayoutHelper
 *
 * @author janpa
 */
class LayoutHelper extends AbstractHelper {
    
    public function isLogged()
    {
        $session = new Container('loginSession');
        if(isset($session->isLogged) && $session->isLogged == true)
        {
            return true;
        }
        
        return false;
    }
}
