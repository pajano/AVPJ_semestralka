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
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
use Zend\Session\Container;

use Application\Form\AccessCodeForm;
use Application\Model\AccessCode;
use Application\Form\ActiveInactiveForm;

class AccessCodeController extends AbstractActionController
{
    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        
        $loginSession = new Container('loginSession');
        if(!isset($loginSession->isLogged) && $loginSession->isLogged != true)
        {
            return $this->redirect()->toRoute('home');
        }
        parent::onDispatch($e);
    }
    
    public function validateId($id)
    {
        $idInput = new Input('id');
        $idInput->getValidatorChain()->attach(new Digits());
        $idInputFilter = new InputFilter();
        $idInputFilter->add($idInput)->setData(array('id' => $id));
        if(!$idInputFilter->isValid())
        {
            $this->redirectToList();
        }
    }
    
    public function redirectToList()
    {
        $e = $this->getEvent();
        $e->stopPropagation();
        return $this->redirect()->toRoute('accesscodes');
    }

    public function listAction()
    {
        $view = new ViewModel();
        $dataAccess = $this->dataAccess();
        $form = new ActiveInactiveForm();
        $codeType = 1;
        $request = $this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $codeType = $form->getData()['typ'];
            }
        }
        
        $container = new Container('message');
        if(isset($container->message))
        {
            $view->setVariable('successMessage', $container->message);
            unset($container->message);
        }
        
        $view->setVariable('actualCodeType', $codeType);
        $view->setVariable('activeInactiveForm', $form);
        $view->setVariable('codes', $dataAccess->getAccessCodeTable()->selectAccessCodeWhere(array('active' => $codeType)));
        return $view;
    }
    
    public function addAction()
    {
        $view = new ViewModel();
        
        $form = new AccessCodeForm();
        $form->setAttribute('action', $this->url()->fromRoute('accesscodes', array('action' => 'add')));
        
        $request = $this->getRequest();
        if($request->isPost())
        {
            $code = new AccessCode();
            $form->setInputFilter($code->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $accessCodesTable = $this->dataAccess()->getAccessCodeTable();
                
                if($accessCodesTable->selectAccessCodeWhere(array('code' => $form->getData()['code']))->count() == 0)
                {
                    $code->exchangeArray($form->getData());
                    $code->active = true;
                    $accessCodesTable->insertAccessCode($code);

                    $container = new Container('message');
                    $container->message = 'Prístupový kód bol pridaný';
                    $this->redirectToList();   
                }
                else
                {
                    $form->get('code')->setMessages(array('Zadaný kód už existuje'));
                }
            }
        }
        
        $view->setVariable('form', $form);
        return $view;
    }
    
    public function editAction() {
        
        $view = new ViewModel();
       
        $id = $this->params()->fromRoute('id');
        $this->validateId($id);
        $form = new AccessCodeForm();
        $form->setAttribute('action', $this->url()->fromRoute('accesscodes', array('action' => 'edit', 'id' => $id)));
        $request = $this->getRequest();
        if($request->isPost())
        {
            $code = new AccessCode();
            $form->setInputFilter($code->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $accessCodesTable = $this->dataAccess()->getAccessCodeTable();
                if($accessCodesTable->selectAccessCodeWhere(array('code' => $form->getData()['code']))->count() == 0)
                {
                    $code = $accessCodesTable->selectAccessCode($id);
                    $code->code = $form->getData()['code'];
                    $accessCodesTable->updateAccessCode($code);

                    $container = new Container('message');
                    $container->message = 'Prístupový kód bol uptavený';
                    $this->redirectToList();   
                }
                else
                {
                    $form->get('code')->setMessages(array('Zadaný kód už existuje'));
                }
            }
        }
        else
        {
            $code = $this->dataAccess()->getAccessCodeTable()->selectAccessCode($id);
            $form->setData($code->getDataArray());
        }
        
        $view->setVariable('form', $form);
        return $view;
    }
    
    public function deactivateAction()
    {
        $this->setAccessCodeActiveState(false);
    }
    
    public function activateAction()
    {
        $this->setAccessCodeActiveState(true);
    }
    
    public function setAccessCodeActiveState($state)
    {
        if(!is_bool($state)) 
        {
            return;
        }
        
        $id = $this->params()->fromRoute('id');
        $this->validateId($id);
        
        $codeTable = $this->dataAccess()->getAccessCodeTable();
        $code = $codeTable->selectAccessCode($id);
        $code->active = $state;
        $codeTable->updateAccessCode($code);
        
        $container = new Container('message');
        if($state)
        {
            $container->message = 'Prístupový kód bol aktivovaný';
        }
        else
        {
            $container->message = 'Prístupový kód bol deaktivovaný';   
        }
        $this->redirectToList();
    }
}
