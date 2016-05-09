<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of AdministratorTable
 *
 * @author janpa
 */
class AdministratorTable {
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function selectAll()
    {
        return $this->tableGateway->select();
    }
    
    public function selectAdministrator($id)
    {
        $rows = $this->tableGateway->select(array('id' => (int) $id));
        if($rows->count() == 0)
        {
            throw new Exception("Could not select administrator $id");
        }
        
        return $rows->current();
    }
    
    public function selectAdministratorWhere(array $where)
    {
        return $this->tableGateway->select($where);
    }
    
    public function insertAdministrator(Administrator $administrator)
    {
        if($this->tableGateway->insert($administrator->getDataArray()) > 0)
        {
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            throw new Exception("Can not insert administrator");
        }
    }
    
    public function updateAdministrator(Administrator $administrator)
    {
        if($this->selectAdministrator((int)$administrator->id))
        {
            $this->tableGateway->update($administrator->getDataArray(), array('id' => (int)$administrator->id));
        }
        else
        {
            throw new Exception("Cant update administrator, id $administrator->id does not exist");
        }
    }
    
    public function deleteAdministrator($id)
    {
        if($this->tableGateway->delete(array('id' => (int) $id)) == 0)
        {
            throw new Exception("Can not delete administrator, id $id does not exist");
        }
    }
}
