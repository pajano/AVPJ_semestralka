<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Description of DataAccessPlugin
 *
 * @author janpa
 */
class DataAccessPlugin extends AbstractPlugin {
    
    private $accessCodeTable;
    private $accessLogTable;
    private $accessRightTable;
    private $personTable;
    private $roomTable;
    private $administratorTable;
    
    /* Table access functions */
    
    public function getPersonTable()
    {
        if(!$this->personTable)
        {
            $sm = $this->getController()->getServiceLocator();
            $this->personTable = $sm->get('Application\Model\PersonTable');
        }
        return $this->personTable;
    }
    
    public function getAccessCodeTable()
    {
        if(!$this->accessCodeTable)
        {
            $sm = $this->getController()->getServiceLocator();
            $this->accessCodeTable = $sm->get('Application\Model\AccessCodeTable');
        }
        return $this->accessCodeTable;
    }
    
    public function getAccessLogTable()
    {
        if(!$this->accessLogTable)
        {
            $sm = $this->getController()->getServiceLocator();
            $this->accessLogTable = $sm->get('Application\Model\AccessLogTable');
        }
        return $this->accessLogTable;
    }
    
    public function getAccessRightTable()
    {
        if(!$this->accessRightTable)
        {
            $sm = $this->getController()->getServiceLocator();
            $this->accessRightTable = $sm->get('Application\Model\AccessRightTable');
        }
        return $this->accessRightTable;
    }
    
    public function getRoomTable()
    {
        if(!$this->roomTable)
        {
            $sm = $this->getController()->getServiceLocator();
            $this->roomTable = $sm->get('Application\Model\RoomTable');
        }
        return $this->roomTable;
    }
    
    public function getAdministratorTable()
    {
        if(!$this->administratorTable)
        {
            $sm = $this->getController()->getServiceLocator();
            $this->administratorTable = $sm->get('Application\Model\AdministratorTable');
        }
        return $this->administratorTable;
    }
    
    /* Concrete data access functions */
    
    public function getAllPersons()
    {
        return $this->getPersonTable()->selectAll();
    }
    
    public function getAllRooms()
    {
        return $this->getRoomTable()->selectAll();
    }
    
    public function getFreeAccessCodes()
    {
        return $this->getAccessCodeTable()->selectAccessCodeWhere(array('ref_personel' => NULL));
    }
    
}
