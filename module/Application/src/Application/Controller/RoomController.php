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

use Application\Form\RoomForm;
use Application\Model\Room;

class RoomController extends AbstractActionController
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
            $e = $this->getEvent();
            $e->stopPropagation();
            return $this->redirect()->toRoute('room');
        }
    }
    
    public function redirectToList()
    {
        $e = $this->getEvent();
        $e->stopPropagation();
        return $this->redirect()->toRoute('room');
    }

    public function listAction()
    {
        $view = new ViewModel();
        
        $dataAccess = $this->dataAccess();
        
        $container = new Container('message');
        if(isset($container->message))
        {
            $view->setVariable('successMessage', $container->message);
            unset($container->message);
        }
        
        $view->setVariable('rooms', $dataAccess->getAllRooms());
        return $view;
    }
    
    public function addAction()
    {
        $view = new ViewModel();
        $form = new RoomForm();
        $form->setAttribute('action', $this->url()->fromRoute('room', array('action' => 'add')));
        
        $request = $this->getRequest();
        if($request->isPost())
        {
            $room = new Room();
            $form->setInputFilter($room->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $room->exchangeArray($form->getData());
                $this->dataAccess()->getRoomTable()->insertRoom($room);
                
                $container = new Container('message');
                $container->message = 'Miestnosť bola úspešne pridaná';
                $this->redirectToList();
            }
        }
        
        $view->setVariable('form', $form);
        return $view;
    }
    
    public function editAction() {
        
        $view = new ViewModel();
        $form = new RoomForm();
        
        $id = $this->params()->fromRoute('id');
        $this->validateId($id);
        
        $form->setAttribute('action', $this->url()->fromRoute('room', array('action' => 'edit', 'id' => $id)));
        $request = $this->getRequest();
        if($request->isPost())
        {
            $room = new Room();
            $form->setInputFilter($room->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $room->exchangeArray($form->getData());
                $room->id = $id;
                $this->dataAccess()->getRoomTable()->updateRoom($room);
                
                $container = new Container('message');
                $container->message = 'Miestnosť bola úspešne upravená';
                $this->redirectToList();
            }
        }
        else
        {
            $room = $this->dataAccess()->getRoomTable()->selectRoom($id);
            $form->setData($room->getDataArray());
        }
        
        $view->setVariable('form', $form);
        return $view;
    }
    
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $this->validateId($id);
        
        $this->dataAccess()->getRoomTable()->deleteRoom($id);
        
        $container = new Container('message');
        $container->message = 'Miestnosť bola úspešne vymazaná';
        $this->redirectToList();
    }
}
