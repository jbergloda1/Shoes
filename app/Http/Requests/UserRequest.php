<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        if($this->isMethod('get')){
            return $this->searchRules();
        }elseif($this->isMethod('post')){
            return $this->storeRules();
        }elseif($this->isMethod('put')){
            return $this->updateRules();
        }
    }

    //search User
    public function searchRules() :array
    {
        return [
            // 'code' => 'required',
            // 'name' => 'required',
            // 'dateofbirth' =>'required',
            // 'phone' => 'required',
            // 'address' => 'required',
            // 'email' => 'required',
            // 'img' => 'required',
            // 'password' => 'required',
            // 'role' => 'required',
            // 'status' => 'required'
        ];
    }
    public function searchFilter()
    {
        return $this->only([
            'code',
            'name',
            // 'dateofbirth',
            // 'phone',
            // 'address',
            'email',
            // 'img',
            // 'password',
            // 'role',
            // 'status'
        ]);
    }
    //Store Supplier
    public function storeRules(): array
    {
        return [
            'code'              => 'required|min:1|max:20|unique:users',
            'name'              => 'required|min:2|max:50',
            'dateofbirth'       => 'required|date',
            'phone'             => 'required|numeric',
            'address'           => 'required|min:1|max:200',
            'email'             => 'required|email|unique:users',
            'img'               => 'required|file|image',
            'password'          => 'required',
            'confirmpassword'   =>  'required|same:password',
            'role'              => 'required|max:2'
        ];
    }
    public function storeFilter()
    {
        return $this->only([
            'code',
            'name',
            'dateofbirth',
            'phone',
            'address',
            'email',
            'img',
            'password',
            'confirmpassword',
            'role'
        ]);
    }

    //updaet User
    public function updateRules(): array
    {
        return [
            //'code'              => 'required|min:1|max:20|unique:users',
            // 'name'              => 'required|min:2|max:50',
            // 'dateofbirth'       => 'required|date',
            // 'phone'             => 'required|numeric',
            // 'address'           => 'required|min:1|max:200',
            // 'email'             => 'required|email|unique:users,email'.$this->id,
            // 'img'               => 'required|file|image',
            // 'password'          => 'required',
            // 'confirmpassword'   => 'required|same:password',
            // 'role'              => 'required|max:2'
        ];
    }
    public function updateFilter()
    {
        return $this->only([
            'code',
            'name',
            'dateofbirth',
            'phone',
            'address',
            'email',
            'img',
            'password',
            'confirmpassword',
            'role'
        ]);
    }
}
