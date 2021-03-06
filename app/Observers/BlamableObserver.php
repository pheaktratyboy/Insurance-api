<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class BlamableObserver
{
    /**
     * @param Model $model
     */
    public function saving(Model $model)
    {
        if (!$model->created_by) {
            $model->created_by = $this->getAuthenticatedUserId();
        }

        if (!$model->updated_by) {
            $model->updated_by = $this->getAuthenticatedUserId();
        }
    }

    /**
     * @param Model $model
     */
    public function saved(Model $model)
    {
        if (!$model->created_by) {
            $model->created_by = $this->getAuthenticatedUserId();
        }
        if (!$model->updated_by) {
            $model->updated_by = $this->getAuthenticatedUserId();
        }
    }

    /**
     * @param Model $model
     */
    public function creating(Model $model)
    {
        if (!$model->created_by) {
            $model->created_by = $this->getAuthenticatedUserId();
        }
        if (!$model->updated_by) {
            $model->updated_by = $this->getAuthenticatedUserId();
        }
    }

    /**
     * @param Model $model
     */
    public function updating(Model $model)
    {
        if (!$model->isDirty('updated_by')) {
            $model->updated_by = $this->getAuthenticatedUserId();
        }
    }

    protected function getAuthenticatedUserId()
    {
        return auth()->check() ? auth()->id() : null;
    }
}
