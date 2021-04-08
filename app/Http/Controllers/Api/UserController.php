<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\user\UserCollection;
use App\Http\Resources\user\UserResource;
use App\Http\Resources\BaseResource;
use App\Http\Resources\user\SessionUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function search(UserRequest $request)
    {
        return new UserCollection($this->userRepository->search($request->searchFilter()));
    }

    public function store(UserRequest $request)
    {
        $image          = $request->file('img');
        $newNamefile    = rand().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('/uploads/user/'),$newNamefile);
        return new UserResource($this->userRepository->store($request->storeFilter(), $newNamefile));
    }

    public function show($id)
    {
        return new UserResource($this->userRepository->show($id));
    }

    public function update(UserRequest $request, $id)
    {   
        //getdataUser
        $getdataUser = new UserResource($this->userRepository->show($id));
        if($getdataUser->img == $request->img){
            return new BaseResource($this->userRepository->update($request->updateFilter(), $id));
        }else{
            $image          = $request->file('img');
            $newNamefile    = rand().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/uploads/user/'),$newNamefile);
            $updateUser = new BaseResource($this->userRepository->updateNew($request->updateFilter(), $id, $newNamefile));
            unlink("uploads/user/".$getdataUser->img);
            return $updateUser;
        }
    }

    public function destroy($id)
    {
        return new BaseResource($this->userRepository->destroy($id));
    }

    public function login(LoginRequest $request)
    {
        $datacheckLogin = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if(Auth::attempt($datacheckLogin)){
            $checkTokenExit = $this->userRepository->get();
            if(empty($checkTokenExit)){
                $userSession = new SessionUserResource($this->userRepository->login($request->filter()));
                return $userSession;
            }else{
                $userSession = $checkTokenExit;
                return $userSession;
            } 
        }
    }
}
