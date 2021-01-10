<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasAttachments
{
    /**
     * Return all attachments for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany;
     */
    public function attachments()
    {
        return $this->morphMany(\App\Models\Attachment::class, 'attachable')->orderBy('created_at', 'asc');
    }
}
