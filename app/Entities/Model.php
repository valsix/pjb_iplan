<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    /**
     * Return new instance of model if the related is null
     */
    protected function getRelationshipFromMethod($method)
    {
        $models = parent::getRelationshipFromMethod($method);

        !is_null($models) ?: $models = $this->$method()->newQuery()->getModel();

        return $this->relations[$method] = $models;
    }

}
