<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class AdministrationController extends AbstractActionController
{
    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        
        $loginSession = new Container('loginSession');
        if(!isset($loginSession->isLogged) && $loginSession->isLogged != true)
        {
            return $this->redirect()->toRoute('home');
        }
        parent::onDispatch($e);
    }
    
    public function indexAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function logoutAction()
    {
        $loginSession = new Container('loginSession');
        $loginSession->getManager()->destroy();
        return $this->redirect()->toRoute('home');
    }
}
