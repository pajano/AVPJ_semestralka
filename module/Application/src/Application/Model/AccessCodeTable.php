<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of AccessCodeTable
 *
 * @author janpa
 */
class AccessCodeTable {
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function selectAll()
    {
        return $this->tableGateway->select();
    }
    
    public function selectAccessCode($id)
    {
        $rows = $this->tableGateway->select(array('id' => (int) $id));
        if($rows->count() == 0)
        {
            throw new Exception("Could not select accessCode $id");
        }
        
        return $rows->current();
    }
    
    public function selectAccessCodeWhere(array $where)
    {
        return $this->tableGateway->select($where);
    }
    
    public function insertAccessCode(AccessCode $accessCode)
    {
        if($this->tableGateway->insert($accessCode->getDataArray()) > 0)
        {
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            throw new Exception("Can not insert accessCode");
        }
    }
    
    public function updateAccessCode(AccessCode $accessCode)
    {
        if($this->selectAccessCode((int)$accessCode->id))
        {
            $this->tableGateway->update($accessCode->getDataArray(), array('id' => (int)$accessCode->id));
        }
        else
        {
            throw new Exception("Cant update accessCode, id $accessCode->id does not exist");
        }
    }
    
    public function deleteAccessCode($id)
    {
        if($this->tableGateway->delete(array('id' => (int) $id)) == 0)
        {
            throw new Exception("Can not delete accessCode, id $id does not exist");
        }
    }
}
