<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of RoomTable
 *
 * @author janpa
 */
class RoomTable {
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function selectAll()
    {
        return $this->tableGateway->select();
    }
    
    public function selectRoom($id)
    {
        $rows = $this->tableGateway->select(array('id' => (int) $id));
        if($rows->count() == 0)
        {
            throw new \Exception("Could not select room $id");
        }
        
        return $rows->current();
    }
    
    public function insertRoom(Room $room)
    {
        if(($id = $this->tableGateway->insert($room->getDataArray())) > 0)
        {
            return $id;
        }
        else
        {
            throw new \Exception("Can not insert room");
        }
    }
    
    public function updateRoom(Room $room)
    {
        if($this->selectRoom((int)$room->id))
        {
            $this->tableGateway->update($room->getDataArray(), array('id' => (int)$room->id));
        }
        else
        {
            throw new \Exception("Cant update room, id $room->id does not exist");
        }
    }
    
    public function deleteRoom($id)
    {
        if($this->tableGateway->delete(array('id' => (int) $id)) == 0)
        {
            throw new \Exception("Can not delete room, id $id does not exist");
        }
    }
}
