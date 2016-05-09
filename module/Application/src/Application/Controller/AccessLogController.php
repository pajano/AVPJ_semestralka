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


class AccessLogController extends AbstractActionController
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

    public function listAction()
    {
        $view = new ViewModel();
        
        $dataAccess = $this->dataAccess();
        
        $persons = $dataAccess->getPersonTable()->selectAll();
        $personsOptions = array();
        foreach($persons as $person)
        {
            $personsOptions[strval($person->id)] = $person->titleBefore . ' ' . $person->firstName . ' ' . $person->lastName . ' ' . $person->titleBehind;
        }
        
        $rooms = $this->dataAccess()->getRoomTable()->selectAll();
        $roomsOptions = array();
        foreach($rooms as $room)
        {
            $roomsOptions[strval($room->id)] = "$room->label - $room->designation";
        }
        
        $personSelect = new Element\Select('person-select');
        $personSelect->setAttribute('class', 'form-control');
        $personSelect->setOptions(array(
            'empty_option' => 'Vyberte pracovníka',
        ));
        $personSelect->setValueOptions($personsOptions);
        
        $roomSelect = new Element\Select('room-select');
        $roomSelect->setAttribute('class', 'form-control');
        $roomSelect->setOptions(array(
            'empty_option' => 'Vyberte miestnosť',
        ));
        $roomSelect->setValueOptions($roomsOptions);
        
        $dateBegin = new Element\Date('date-begin');
        $dateBegin->setAttribute('class', 'form-control');
        
        $dateEnd = new Element\Date('date-end');
        $dateEnd->setAttribute('class', 'form-control');
        
        $submit = new Element\Submit('filter-submit');
        $submit->setAttribute('class', 'form-control btn btn-info');
        $submit->setValue('Filtrovať');
        
        $filterForm = new Form\Form();
        $filterForm->setAttribute('class', 'form-horizontal');
        $filterForm->add($personSelect);
        $filterForm->add($roomSelect);
        $filterForm->add($dateBegin);
        $filterForm->add($dateEnd);
        $filterForm->add($submit);
        
        $request = $this->getRequest();
        if($request->isPost())
        {
            $filterForm->getInputFilter()->get('room-select')->setRequired(false);
            $filterForm->getInputFilter()->get('date-begin')->setRequired(false);
            $filterForm->getInputFilter()->get('date-end')->setRequired(false);
            $filterForm->getInputFilter()->get('person-select')->setRequired(false);
            $filterForm->setData($request->getPost());
            if($filterForm->isValid())
            {
                $accessLogs = $dataAccess->getAccessLogTable()->selectAccessLogFiltered(
                        $filterForm->getData()['person-select'], 
                        $filterForm->getData()['room-select'],
                        $filterForm->getData()['date-begin'],
                        $filterForm->getData()['date-end']);
            }
            
        }
        else
        {
            $accessLogs = $dataAccess->getAccessLogTable()->selectAll();
        }
        
        $viewData = array();
        foreach($accessLogs as $accessLog)
        {
            $person = $dataAccess->getPersonTable()->selectPerson($accessLog->refPerson);
            $room = $dataAccess->getRoomTable()->selectRoom($accessLog->refRoom);
            
            $accessCode = 'N/A';
            $accessCodes = $dataAccess->getAccessCodeTable()->selectAccessCodeWhere(array('ref_personel' => $person->id));
            if($accessCodes->count() > 0)
            {
                $accessCode = $accessCodes->current()->code;
            }
            
            $viewData[] = array(
                'person' => $person->titleBefore . ' ' . $person->firstName . ' <strong>' . $person->lastName . '</strong> ' . $person->titleBehind,
                'room' => $room->label,
                'code' => $accessCode,
                'entryType' => $accessLog->accessLogType,
                'timestamp' => $accessLog->timestamp,
            );
        }
        
        $view->setVariable('filterForm', $filterForm);
        $view->setVariable('logs', $viewData);        
        return $view;
    }
}
