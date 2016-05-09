<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

/**
 * Description of AccessRight
 *
 * @author janpa
 */
class AccessRight {
    
    public $id;
    public $refPerson;
    public $refRoom;
    
    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->refPerson = (!empty($data['ref_personel'])) ? $data['ref_personel'] : null;
        $this->refRoom = (!empty($data['ref_rooms'])) ? $data['ref_rooms'] : null;
    }
    
    public function getDataArray()
    {
        return array(
            'ref_personel' => $this->refPerson,
            'ref_rooms' => $this->refRoom,
        );
    }
}
