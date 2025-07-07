<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository{
    public function allWithRoles(){
        return User::with('roles')->get();
    }
    public function create(array $data){
        return User::create($data);
    }
}
