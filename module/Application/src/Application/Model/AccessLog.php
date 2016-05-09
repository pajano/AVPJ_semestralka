<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

/**
 * Description of AccessLog
 *
 * @author janpa
 */
class AccessLog {
    
    public $id;
    public $timestamp;
    public $accessLogType;
    public $refPerson;
    public $refRoom;
    
    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->refPerson = (!empty($data['ref_personel'])) ? $data['ref_personel'] : null;
        $this->refRoom = (!empty($data['ref_rooms'])) ? $data['ref_rooms'] : null;
        $this->accessLogType = (!empty($data['accessLogType'])) ? $data['accessLogType'] : null;
        $this->timestamp = (!empty($data['timestamp'])) ? $data['timestamp'] : null;
    }
    
    public function getDataArray()
    {
        return array(
            'timestamp' => $this->timestamp,
            'accessLogType' => $this->accessLogType,
            'ref_personel' => $this->refPerson,
            'ref_rooms' => $this->refRoom,
        );
    }
}
