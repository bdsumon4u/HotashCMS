<?php

namespace App\Database\Schema;

/** @mixin \Illuminate\Database\Schema\Blueprint */
class BlueprintMixin
{
    public function foreignIdX()
    {
        return function ($column) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumnDefinition(new ExclusiveForeign($this, [
                'type' => 'bigInteger',
                'name' => $column,
                'autoIncrement' => false,
                'unsigned' => true,
            ]));
        };
    }

    public function foreignUuidX()
    {
        return function ($column) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumnDefinition(new ExclusiveForeign($this, [
                'type' => 'uuid',
                'name' => $column,
            ]));
        };
    }

    public function foreignIdForX()
    {
        return function ($model, $column = null) {
            if (is_string($model)) {
                $model = new $model;
            }

            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $model->getKeyType() === 'int' && $model->getIncrementing()
                ? $this->foreignIdX($column ?: $model->getForeignKey())
                : $this->foreignUuidX($column ?: $model->getForeignKey());
        };
    }
}
