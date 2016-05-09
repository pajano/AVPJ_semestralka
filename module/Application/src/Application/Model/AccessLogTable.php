<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of AccessLogTable
 *
 * @author janpa
 */
class AccessLogTable {
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function selectAll()
    {
        return $this->tableGateway->select(function(\Zend\Db\Sql\Select $select){
            $select->order('timestamp DESC');
        });
    }
    
    public function selectAccessLog($id)
    {
        $rows = $this->tableGateway->select(array('id' => (int) $id));
        if($rows->count() == 0)
        {
            throw new Exception("Could not select accessLog $id");
        }
        
        return $rows->current();
    }
    
    public function selectAccessLogFiltered($person, $room, $dateBegin, $dateEnd)
    {
        $where = new \Zend\Db\Sql\Where();
        
        if(!empty($dateBegin) && !empty($dateEnd))
        {
            $where->between('timestamp', $dateBegin, $dateEnd);
        }
        else if(!empty($dateBegin))
        {
            $where->greaterThan('timestamp', $dateBegin);
        }
        else if(!empty($dateEnd))
        {
            $where->lessThan('timestamp', $dateEnd);
        }
        
        if(!empty($person))
        {
            $where->equalTo('ref_personel', $person);
        }
        
        if(!empty($room))
        {
            $where->equalTo('ref_rooms', $room);
        }
        
        return $this->tableGateway->select($where);
    }
    
    public function insertAccessLog(AccessLog $accessLog)
    {
        if(($id = $this->tableGateway->insert($accessLog->getDataArray())) > 0)
        {
            return $id;
        }
        else
        {
            throw new Exception("Can not insert accessLog");
        }
    }
    
    public function updateAccessLog(AccessLog $accessLog)
    {
        if($this->selectAccessCode((int)$accessLog->id))
        {
            $this->tableGateway->update($accessLog->getDataArray(), array('id' => (int)$accessLog->id));
        }
        else
        {
            throw new Exception("Cant update accessLog, id $accessLog->id does not exist");
        }
    }
    
    public function deleteAccessLog($id)
    {
        if($this->tableGateway->delete(array('id' => (int) $id)) == 0)
        {
            throw new Exception("Can not delete accessLog, id $id does not exist");
        }
    }
}
