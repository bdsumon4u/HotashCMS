<?php

namespace Illuminate\Database\Schema
{

    use App\Database\Schema\ExclusiveForeign;

    class Blueprint
    {
        public function foreignIdX($column): ExclusiveForeign {}
        public function foreignIdForX($model, $column = null): ExclusiveForeign {}
    }
}
