<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository {

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getByUsername($username){
        return $this->user->where('username',$username)->first();
    }

    public function getByEmail($email){
        return $this->user->where('email',$email)->first();
    }

    public function addNewUser($data){
        return $this->user->create([
            "username" => $data['username'],
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => $data['password'],
        ]);
    }

    public function authenticate($data){
        $user = $this->user->where('username',$data['username'])->first();
        // dd(Hash::check($data['password'], $user->password));
        if($user){
            if(Hash::check($data['password'], $user->password)){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public function getById($id){
        return $this->user->find($id);
    }

    public function updateUser($data, $id){
        return $this->getById($id)->update([
            "username" => $data['username'],
            "email" => $data['email'],
            "name" => $data['name']
        ]);
    }

}