<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of AccessRightTable
 *
 * @author janpa
 */
class AccessRightTable {
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function selectAll()
    {
        return $this->tableGateway->select();
    }
    
    public function selectAccessRight($id)
    {
        $rows = $this->tableGateway->select(array('id' => (int) $id));
        if($rows->count() == 0)
        {
            throw new Exception("Could not select accessRight $id");
        }
        
        return $rows->current();
    }
    
    public function selectAccessRightWhere(array $where)
    {
        return $this->tableGateway->select($where);
    }
    
    public function insertAccessRight(AccessRight $accessRight)
    {
        if(($id = $this->tableGateway->insert($accessRight->getDataArray())) > 0)
        {
            return $id;
        }
        else
        {
            throw new Exception("Can not insert accessRight");
        }
    }
    
    public function updateAccessRight(AccessRight $accessRight)
    {
        if($this->selectAccessCode((int)$accessRight->id))
        {
            $this->tableGateway->update($accessRight->getDataArray(), array('id' => (int)$accessRight->id));
        }
        else
        {
            throw new Exception("Cant update accessRight, id $accessRight->id does not exist");
        }
    }
    
    public function deleteAccessRight($id)
    {
        if($this->tableGateway->delete(array('id' => (int) $id)) == 0)
        {
            throw new \Exception("Can not delete accessRight, id $id does not exist");
        }
    }
}
