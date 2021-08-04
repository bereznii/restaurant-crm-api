<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class AbstractRepository
{
    /** @var string */
    protected string $modelClass;

    /**
     * @return Model
     */
    protected function _getInstance(): Model
    {
        return app($this->modelClass);
    }
}
