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

use Application\Model\Administrator;
use Application\Form\LoginForm;
use Application\Model\Terminal;
use Application\Form\TerminalForm;
use Application\Model\AccessLog;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $view = new ViewModel();
        
        $roomsOptions = array();
        $rooms = $this->dataAccess()->getRoomTable()->selectAll();
        foreach($rooms as $room)
        {
            $roomsOptions[strval($room->id)] = "$room->label - $room->designation";
        }
        
        $loginForm = new LoginForm();
        $loginForm->setAttribute('action', $this->url()->fromRoute('home', array('action' => 'login')));
        $terminalForm = new TerminalForm($roomsOptions);
        
        $request = $this->getRequest();
        $message = "";
        if($request->isPost())
        {
            $message = "Do miestnosti nemáte prístup!";
            $view->setVariable('errorMessage', true);
            
            $model = new Terminal();
            $terminalForm->setInputFilter($model->getInputFilter());
            $terminalForm->setData($request->getPost());
            if($terminalForm->isValid())
            {
                $model->exchangeArray($terminalForm->getData());
                $accessCode = $this->dataAccess()->getAccessCodeTable()->selectAccessCodeWhere(array('code' => $model->code, 'active' => 1));
                if($accessCode->count() > 0)
                {
                    $personId = $accessCode->current()->refPerson;
                    $accessRight = $this->dataAccess()->getAccessRightTable()->selectAccessRightWhere(array(
                        'ref_personel' => $personId,
                        'ref_rooms' => $model->room,
                        ));
                    
                    if($accessRight->count() > 0)
                    {
                        $message = "Prístup povolený";
                        $view->setVariable('errorMessage', false);
                        $type = 0;
                        if(isset($terminalForm->getData()['submit-enter']))
                        {
                            $type = 1;
                        }
                        
                        $accessLog = new AccessLog();
                        $accessLog->accessLogType = $type;
                        $accessLog->refPerson = $personId;
                        $accessLog->refRoom = $model->room;
                        $this->dataAccess()->getAccessLogTable()->insertAccessLog($accessLog);
                    }
                }
            }
        }
        $view->setVariable('message', $message);
        $view->setVariable('loginForm', $loginForm);
        $view->setVariable('terminalForm', $terminalForm);
        return $view;
    }
    
    public function loginAction()
    {
        $request = $this->getRequest();
        if($request->isPost())
        {
            $administrator = new Administrator();
            $form = new LoginForm();
            $form->setInputFilter($administrator->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $administrator->exchangeArray($form->getData());
                $dataAccess = $this->dataAccess();
                $rows = $dataAccess->getAdministratorTable()->selectAdministratorWhere(array('login' => $administrator->login));
                if($rows->count() > 0)
                {
                    $adminFromDb = $rows->current();
                    if(password_verify($administrator->password, $adminFromDb->password))
                    {
                        $loginSession = new Container('loginSession');
                        $loginSession->isLogged = true;
                        return $this->redirect()->toRoute('accesslog');
                    }
                }
            }
        }
        
        return $this->redirect()->toRoute('home');
    }
    
    public function superAction()
    {
        $login = $this->params()->fromRoute('login');
        $pass = $this->params()->fromRoute('pass');
        
        $admin = new Administrator();
        $admin->login = $login;
        $admin->password = password_hash($pass, PASSWORD_BCRYPT);
        $this->dataAccess()->getAdministratorTable()->insertAdministrator($admin);
        echo "OK";
        exit;
    }
}
