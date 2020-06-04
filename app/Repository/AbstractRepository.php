<?php

namespace App\Repository;

abstract class AbstractRepository
{

    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function selectFilter($fields)
    {
        $this->model = $this->model->selectRaw($fields);
    }

    public function selectConditions($conditions)
    {
        $expressions = explode(';', $conditions);
        foreach ($expressions as $condition){
            $cond = explode(':', $condition);
            $this->model = $this->model->where($cond[0], $cond[1], $cond[2]);
        }
    }

    public function getResult()
    {
        return $this->model;
    }

}
