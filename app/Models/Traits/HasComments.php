<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    /**
     * Return all comments for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany;
     */
    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable')->orderBy('created_at', 'asc');
    }
}
