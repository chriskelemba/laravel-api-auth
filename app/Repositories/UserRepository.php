<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function index(){
        return User::all();
    }

    public function getById($id){
       return User::findOrFail($id);
    }

    public function store(array $data){
       return User::create($data);
    }

    public function update(array $data,$id){
       return User::whereId($id)->update($data);
    }
    
    public function delete($id){
       User::destroy($id);
    }

    public function login(array $credentials) {
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }
    
        return false;
    }
}