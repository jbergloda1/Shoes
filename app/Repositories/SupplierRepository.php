<?php

namespace App\Repositories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\UserUnauthorizedException;
use Config;

class SupplierRepository
{
    public function search($inputs)
    {
        return Supplier::with('category')
        ->when(isset($inputs['id']), function ($query) use ($inputs) {
            return $query->where('id', $inputs['id']);
        })
        ->when(isset($inputs['name']), function ($query) use ($inputs) {
            return $query->where('name', 'LIKE', '%' . $inputs['name'] . '%');
        })
        ->orderBy('name', 'desc')
        ->paginate(10);
    }

    public function store($inputs)
    {
        return Supplier::create([
            'name'          => $inputs['name'],
            'category_id'   => $inputs['category_id'],
            'address'       => $inputs['address'],
            'phone'         => $inputs['phone'],
            'status'        => 1
        ]);
    }

    public function show($id)
    {
        return Supplier::findOrFail($id);
    }

    public function update($inputs, $id)
    {
        return Supplier::findOrFail($id)
            ->update($inputs);
    }
}
