<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of PersonTable
 *
 * @author janpa
 */
class PersonTable {
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function selectAll()
    {
        return $this->tableGateway->select();
    }
    
    public function selectPerson($id)
    {
        $rows = $this->tableGateway->select(array('id' => (int) $id));
        if($rows->count() == 0)
        {
            throw new Exception("Could not select person $id");
        }
        
        return $rows->current();
    }
    
    public function selectActivePersons()
    {
        return $this->tableGateway->select(array('active' => 1));
    }

    public function selectPersonsWhere($where)
    {
        return $this->tableGateway->select($where);
    }

    public function insertPerson(Person $person)
    {
        if($this->tableGateway->insert($person->getDataArray()) > 0)
        {
            return $this->tableGateway->lastInsertValue;;
        }
        else
        {
            throw new Exception("Can not insert person");
        }
    }
    
    public function updatePerson(Person $person)
    {
        if($this->selectPerson((int)$person->id))
        {
            $this->tableGateway->update($person->getDataArray(), array('id' => (int)$person->id));
        }
        else
        {
            throw new Exception("Cant update person, $person->id does not exist");
        }
    }
    
    public function deletePerson($id)
    {
        if($this->tableGateway->delete(array('id' => (int) $id)) == 0)
        {
            throw new Exception("Can not delete Person $id, id does not exist");
        }
    }
}
