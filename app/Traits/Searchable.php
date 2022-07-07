<?php

namespace App\Traits;

trait Searchable
{
    use \Laravel\Scout\Searchable;

    public function searchableAs(): string
    {
        $prefix = config('scout.prefix');
        if (tenant()) {
            $prefix .= tenant('id').'_';
        }
        return $prefix.$this->getTable();
    }
}
