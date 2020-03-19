<?php

namespace App;


class ComplaintType
{
    private $types;

    public function __construct()
    {
        $this->setTypes();
    }

    public function setTypes(){
        $this->types = [
            1 => (object)['id'=>1,'key'=>'Müştəri xidməti'],
            2 => (object)['id'=>2,'key'=>'Kuryer'],
            3 => (object)['id'=>3,'key'=>'Sifariş et xidməti'],
            4 => (object)['id'=>4,'key'=>'Kassa'],
            5 => (object)['id'=>5,'key'=>'Anbar'],
            6 => (object)['id'=>6,'key'=>'Saytdan istifadə']
        ];
    }

    public function find($id){
        return $this->types[$id];
    }

    public function all(){
        return array_values($this->types);
    }

}
