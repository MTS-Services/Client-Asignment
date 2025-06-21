<?php

namespace App\Services\Admin\UserManagement;

use App\Models\Query;
use Illuminate\Database\Eloquent\Collection;

class QueryService
{
    public function getQueries($orderBy = 'sort_order', $order = 'asc')
    {
        return Query::orderBy($orderBy, $order)->latest();
    }

    public function getQuery(string $encryptedId): Query|Collection
    {
        return Query::findOrFail(decrypt($encryptedId));
    }
}
