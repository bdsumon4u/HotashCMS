<?php

namespace App\Database\Schema;

use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Support\Str;

class ExclusiveForeign extends ForeignIdColumnDefinition
{
    /**
     * Create a foreign key constraint on this column referencing the "id" column of the conventionally related table.
     *
     * @param string|null $table
     * @param string $column
     * @return \Illuminate\Database\Schema\ForeignKeyDefinition
     */
    public function constrained($table = null, $column = 'id')
    {
        return ignorable($this, !config('database.constrained'))
            ->references($column)->on($table ?? Str::plural(Str::beforeLast($this->name, '_' . $column)));
    }
}
