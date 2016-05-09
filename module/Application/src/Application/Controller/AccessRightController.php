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
use Zend\Form\Element;
use Zend\Form;


class AccessRightController extends AbstractActionController
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
        return $this->redirect()->toRoute('accessright');
    }

    public function listAction()
    {
        $view = new ViewModel();
        $dataAccess = $this->dataAccess();
        $request = $this->getRequest();
                
        $container = new Container('message');
        if(isset($container->message))
        {
            $view->setVariable('successMessage', $container->message);
            unset($container->message);
        }
        
        $roomsOptions = array();
        $rooms = $this->dataAccess()->getRoomTable()->selectAll();
        foreach($rooms as $room)
        {
            $roomsOptions[strval($room->id)] = "$room->label - $room->designation";
        }
        
        $roomsElement = new Element\Select('rooms');
        $roomsElement->setLabel('Miestnosti');
        $roomsElement->setValueOptions($roomsOptions);
        $roomsElement->setAttribute('class', 'form-control');
        $roomsElement->setAttribute('onchange', 'this.form.submit()');
        $roomsForm = new \Zend\Form\Form();
        $roomsForm->setAttribute('class', 'form-horizontal');
        $roomsForm->add($roomsElement);
        
        reset($roomsOptions);
        $selectedRoomId = key($roomsOptions);
        
        if($request->isPost())
        {
            $roomsForm->setData($request->getPost());
            if($roomsForm->isValid()) 
            {
                $selectedRoomId = $roomsForm->getData()['rooms'];
            }
        }
        
        $persons = array();
        $accessRights = $dataAccess->getAccessRightTable()->selectAccessRightWhere(array('ref_rooms' => $selectedRoomId));
        foreach($accessRights as $accessRight)
        {
            $person = $dataAccess->getPersonTable()->selectPerson($accessRight->refPerson);
            $persons[strval($accessRight->id)] = $person;
        }
        
        $view->setVariable('persons', $persons);
        $view->setVariable('roomsForm', $roomsForm);
        return $view;
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $this->validateId($id);
        
        $this->dataAccess()->getAccessRightTable()->deleteAccessRight($id);
        
        $container = new Container('message');
        $container->message = 'Prístupové právo bolo odstránené';
        $this->redirectToList();
    }
}
