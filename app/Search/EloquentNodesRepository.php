<?php

namespace App\Search;

use App\Node;
use Illuminate\Database\Eloquent\Collection;

class EloquentNodesRepository extends NodesRepository
{
    public function search(string $query = ""):Collection
    {
        return Node::with('elements','tags')->where('nodes.title', 'like', "%{$query}%")->get();
           // ->orWhere('elements.data', 'like', "%{$query}%")->get();
    }
}