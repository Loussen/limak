<?php

namespace App\Models;

class ComplaintReason
{
    private $types;

    public function __construct()
    {
        $this->setTypes();
    }

    public function setTypes(){
        $this->types = [
            1 => (object)['id'=>1,'key'=>'Gecikmə'],
            2 => (object)['id'=>2,'key'=>'Davranış'],
            3 => (object)['id'=>3,'key'=>'Yükü zədələmə'],
            4 => (object)['id'=>4,'key'=>'Səhv sifariş'],
            5 => (object)['id'=>5,'key'=>'İtmiş bağlama'],
            6 => (object)['id'=>6,'key'=>'Digər']
        ];
    }

    public function find($id){
        return $this->types[$id];
    }

    public function all(){
        return array_values($this->types);
    }

}
