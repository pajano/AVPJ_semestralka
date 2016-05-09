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

use Application\Form\PersonForm;
use Application\Model\Person;
use Application\Form\ActiveInactiveForm;
use Application\Form\AddAccessRightForm;

class PersonController extends AbstractActionController
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
        return $this->redirect()->toRoute('person');
    }

    public function listAction()
    {
        $view = new ViewModel();
        $dataAccess = $this->dataAccess();
        $form = new ActiveInactiveForm();
        $personType = 1;
        $request = $this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $personType = $form->getData()['typ'];
            }
        }
        
        $container = new Container('message');
        if(isset($container->message))
        {
            $view->setVariable('successMessage', $container->message);
            unset($container->message);
        }
        
        $accessCodes = array();
        $accessCodesRows = $this->dataAccess()->getAccessCodeTable()->selectAll();
        foreach($accessCodesRows as $row)
        {
            if($row->refPerson)
            {
                $accessCodes[strval($row->refPerson)] = $row->code;
            }
        }
        
        $view->setVariable('actualPersonType', $personType);
        $view->setVariable('personActiveForm', $form);
        $view->setVariable('codes', $accessCodes);
        $view->setVariable('persons', $dataAccess->getPersonTable()->selectPersonsWhere(array('active' => $personType)));
        return $view;
    }
    
    public function addAction()
    {
        $view = new ViewModel();
        
        $freeCodes = array();
        foreach($this->dataAccess()->getFreeAccessCodes() as $accessCode)
        {
            $freeCodes[strval($accessCode->id)] = $accessCode->code;
        }
        
        $form = new PersonForm($freeCodes);
        $form->setAttribute('action', $this->url()->fromRoute('person', array('action' => 'add')));
        
        $request = $this->getRequest();
        if($request->isPost())
        {
            $person = new Person();
            $form->setInputFilter($person->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $person->exchangeArray($form->getData());
                $person->active = true;
                $personId = $this->dataAccess()->getPersonTable()->insertPerson($person);
                
                $codeId = $form->getData()['code'];
                $this->validateId($codeId);
               
                $accessCode = $this->dataAccess()->getAccessCodeTable()->selectAccessCode($codeId);
                $accessCode->refPerson = $personId;
                $accessCode->active = 1;
                $this->dataAccess()->getAccessCodeTable()->updateAccessCode($accessCode);
                
                $container = new Container('message');
                $container->message = 'Pracovník bol pridaný';
                $this->redirectToList();
            }
        }
        
        $view->setVariable('form', $form);
        return $view;
    }
    
    public function editAction() {
        
        $view = new ViewModel();
       
        $id = $this->params()->fromRoute('id');
        $this->validateId($id);
        
        $freeCodes = array();
        foreach($this->dataAccess()->getFreeAccessCodes() as $accessCode)
        {
            $freeCodes[strval($accessCode->id)] = $accessCode->code;
        }
        
        $personAccessCodeId = 0;
        $accessCodeRows = $this->dataAccess()->getAccessCodeTable()->selectAccessCodeWhere(array('ref_personel' => $id));
        if($accessCodeRows->count() > 0)
        {
            $personAccessCodeId = $accessCodeRows->current()->id;
            $actualCode[strval($personAccessCodeId)] = $accessCodeRows->current()->code;
            $form = new PersonForm($freeCodes, $actualCode);
        }
        else
        {
            $form = new PersonForm($freeCodes);
        }
        
        $form->setAttribute('action', $this->url()->fromRoute('person', array('action' => 'edit', 'id' => $id)));
        $request = $this->getRequest();
        if($request->isPost())
        {
            $person = new Person();
            $form->setInputFilter($person->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $person = $this->dataAccess()->getPersonTable()->selectPerson($id);
                $isActive = $person->active;
                $person->exchangeArray($form->getData());
                $person->id = $id;
                $person->active = $isActive;
                $this->dataAccess()->getPersonTable()->updatePerson($person);
                
                $codeId = $form->getData()['code'];
                $this->validateId($codeId);
                
                if($personAccessCodeId > 0 && $codeId != $personAccessCodeId)
                {
                    $actualCode = $this->dataAccess()->getAccessCodeTable()->selectAccessCode($personAccessCodeId);
                    $actualCode->refPerson = null;
                    $actualCode->active = 0;
                    $this->dataAccess()->getAccessCodeTable()->updateAccessCode($actualCode);
                }
                
                $accessCode = $this->dataAccess()->getAccessCodeTable()->selectAccessCode($codeId);
                $accessCode->refPerson = $id;
                $accessCode->active = 1;
                $this->dataAccess()->getAccessCodeTable()->updateAccessCode($accessCode);
                
                $container = new Container('message');
                $container->message = 'Pracovník bol upravený';
                $this->redirectToList();
            }
        }
        else
        {
            $person = $this->dataAccess()->getPersonTable()->selectPerson($id);
            $form->setData($person->getDataArray());
        }
        
        $view->setVariable('form', $form);
        return $view;
    }
    
    public function addAccessRightAction()
    {
        $view = new ViewModel();
        $dataAccess = $this->dataAccess();

        $id = $this->params()->fromRoute('id');
        $this->validateId($id);
        
        $personAccessRights = $dataAccess->getAccessRightTable()->selectAccessRightWhere(array('ref_personel' => (int)$id));

        $roomsOptions = array();
        $rooms = $this->dataAccess()->getRoomTable()->selectAll();
        foreach($rooms as $room)
        {
            $roomsOptions[strval($room->id)] = "$room->label - $room->designation";
        }
        
        foreach($personAccessRights as $personAccessRight)
        {
            if(isset($roomsOptions[strval($personAccessRight->refRoom)]))
            {
                unset($roomsOptions[strval($personAccessRight->refRoom)]);
            }
        }
        
        $accessRightForm = new AddAccessRightForm($roomsOptions);
        
        $request = $this->getRequest();
        if($request->isPost())
        {
            $accessRightForm->setData($request->getPost());
            if($accessRightForm->isValid())
            {
                $roomId = $accessRightForm->getData()['room'];
                $accessRight = new \Application\Model\AccessRight();
                $accessRight->refPerson = $id;
                $accessRight->refRoom = $roomId;
                $dataAccess->getAccessRightTable()->insertAccessRight($accessRight);
                $container = new Container('message');
                $container->message = 'Prístupové právo bolo pridané';
                $this->redirectToList();
            }
        }
        
        $view->setVariable('form', $accessRightForm);
        return $view;
    }
    
    public function deactivateAction()
    {
        $this->setPersonActiveState(false);
    }
    
    public function activateAction()
    {
        $this->setPersonActiveState(true);
    }
    
    public function setPersonActiveState($state)
    {
        if(!is_bool($state)) 
        {
            return;
        }
        
        $id = $this->params()->fromRoute('id');
        $this->validateId($id);
        
        $personCodeRows = $this->dataAccess()->getAccessCodeTable()->selectAccessCodeWhere(array('ref_personel' => $id));
        if($personCodeRows->count() > 0)
        {
            $personCode = $personCodeRows->current();
            $personCode->active = $state;
            $this->dataAccess()->getAccessCodeTable()->updateAccessCode($personCode);
        }
        
        $personTable = $this->dataAccess()->getPersonTable();
        $person = $personTable->selectPerson($id);
        $person->active = $state;
        $personTable->updatePerson($person);
        
        $container = new Container('message');
        if($state)
        {
            $container->message = 'Pracovník bol aktivovaný';
        }
        else
        {
            $container->message = 'Pracovník bol deaktivovaný';   
        }
        $this->redirectToList();
    }
}
