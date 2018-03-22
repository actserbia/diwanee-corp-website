<?php

namespace App\Search;

use Illuminate\Database\Eloquent\Collection;

class NodesRepository
{
    public function search(string $query = ""): Collection {}
}