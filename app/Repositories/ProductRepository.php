<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Size;
use App\Models\ProductImage;
use App\Models\ProductSizeColor;
use App\Models\Color;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\UserUnauthorizedException;
use Config;

class ProductRepository
{
    public function search($inputs)
    {
        return Product::with('supplier')
        ->when(isset($inputs['id']), function ($query) use ($inputs) {
            return $query->where('id', $inputs['id']);
        })
        ->when(isset($inputs['status']), function ($query) use ($inputs) {
            return $query->where('status', $inputs['status']);
        })
        ->when(isset($inputs['name']), function ($query) use ($inputs) {
            return $query->where('name', 'LIKE', '%' . $inputs['name'] . '%');
        })
        ->orderBy('name', 'desc')
        ->paginate(10);
    }

    public function store($inputs, $newNamefile, $export_price)
    {
        return Product::create([
            'name'          => $inputs['name'],
            'img'           => $newNamefile,
            'note'          => $inputs['note'],
            'import_price'  => $inputs['import_price'],
            'export_price'  => $export_price,
            'sale'          => $inputs['sale'],
            'status'        => 1,
            'supplier_id'   => $inputs['supplier_id']
        ]);
    }

    public function storeImage($new_name, $product_id)
    {
        return ProductImage::create([
            'product_id'    => $product_id,
            'image'         => $new_name
        ]);
    }

    public function storeSize($data, $product_id)
    {
       // dd($rowSize);
        return ProductSize::create([
            'product_id' => $product_id,
            'size_id'    => $data['size'],
            'amount'     => $data['amount'],
            'status'     => 1
        ]);
    }

    public function storeColor($rowColor, $product_id)
    {
        return ProductColor::create([
            'product_id'    => $product_id,
            'color'         => $rowColor
        ]); 
    }

    public function show($id)
    {
        return Product::findOrFail($id);
    }

    public function destroy($id)
    {
        ProductImage::where('product_id', $id)
            ->delete();
        ProductSizeColor::where('product_id', $id)
            ->delete();
        return Product::findOrFail($id)
            ->delete();
    }
    
    // public function update($inputs, $id, $export_price)
    // {
    //     return Product::findOrFail($id)
    //         ->update([
    //             'name'          => $inputs['name'],
    //             //'amount'        => $inputs['amount'],
    //             // 'img'           => $newNamefile,
    //             'note'          => $inputs['note'],
    //             'import_price'  => $inputs['import_price'],
    //             'export_price'  => $export_price,
    //             'sale'          => $inputs['sale'],
    //             'status'        => $inputs['status'],
    //             'supplier_id'   => $inputs['supplier_id']
    //         ]);
    // }
    public function updateNew($inputs, $id, $newNamefile, $export_price)
    {
        return Product::findOrFail($id)
            ->update([
                'name'          => $inputs['name'],
                //'amount'        => $inputs['amount'],
                'img'           => $newNamefile,
                'note'          => $inputs['note'],
                'import_price'  => $inputs['import_price'],
                'export_price'  => $export_price,
                'sale'          => $inputs['sale'],
                'status'        => $inputs['status'],
                'supplier_id'   => $inputs['supplier_id']
            ]);
    }

    public function showImage($id)
    {
        return ProductImage::where('product_id', '=', $id)->get();
    }

    public function sum($product_id)
    {
        return ProductSize::where('product_id', $product_id)->sum('amount');
    }
    public function amount($product_id, $totalAmount)
    {
        return Product::where('id', '=', $product_id)
            ->update([
                'amount' => $totalAmount
            ]);
    }
}
