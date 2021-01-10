<?php

namespace App\Models\Traits;

trait HasMetas
{
    /**
     * Return all metas for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metas()
    {
        return $this
            ->morphMany(\App\Models\Meta::class, 'metable')
            ->orderBy('label', 'asc');
    }

    /**
     * Attach related meta fields.
     *
     * @param  array  $fields
     */
    public function addMetas(array $fields)
    {
        $fieldsToStore = $this->parseFields($fields);

        $this->metas()->createMany($fieldsToStore->toArray());
    }

    /**
     * Add
     *
     * @param  array  $fields
     */
    public function syncMetas(array $fields)
    {
        $submittedFields = $this->parseFields($fields)
                                ->map(function ($field) {
                                    $field['metable_id'] = $this->id;
                                    $field['metable_type'] = get_class($this);
                                    return $field;
                                });

        $this->metas()->upsert($submittedFields->toArray(), ['id'], ['label', 'value']);
        $this->metas()->whereNotIn('id', $submittedFields->pluck('id'))->delete();
    }

    /**
     * @param  array  $fields
     * @return \Illuminate\Support\Collection
     */
    private function parseFields(array $fields)
    {
        return collect($fields)
            ->map(function ($item, $index) {
                return [
                    'id'    => $item['id'] ?? null,
                    'label' => $item['label'],
                    'value' => $item['value'],
                ];
            });
    }
}
