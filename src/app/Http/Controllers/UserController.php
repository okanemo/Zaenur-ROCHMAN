<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function show($id){
        try{
            if ($id == "") {
                $msg = "Oops ID masih kosong.";
                return $this->responseError($this->dataNotFound, $msg, 404);
            }
    
            $dataOutput = $this->userRepository->getById($id);
    
            if ($dataOutput == null) {
                $msg = "Oops ID Tidak Ditemukan.";
                return $this->responseError($this->dataNotFound, $msg, 404);
            }
    
            $data['data'] = $this->handleNullMultiDimensi([$dataOutput]);
            $data['message'] = "Request sukses";
            return $this->responseSukses($data, 200);
        }catch(Exception $e){
            $msg = $this->serviceError;
            return $this->responseError($msg, $e->getMessage(), 400);
        }
    }

    public function update(Request $request, $id){
        try {
            $validator = Validator::make(request()->all(), [
                'username'  => 'required|unique:users,username,'.$id.',_id',
                'name'      => 'required',
                'email'     => 'required|email|unique:users,email,'.$id.',_id'
            ]);

            if ($validator->fails()) {
                $msg = $this->validationError;
                return $this->responseError($msg, $validator->errors(), 400);
            }
            $data_in = [
                "name" => $request->name,
                "username" => $request->username,
                "email" => $request->email,
            ];
            $this->userRepository->updateUser($data_in,$id);
            $data['data'] = $this->handleNullMultiDimensi(array($this->userRepository->getById($id)));
            $data['message'] = "Request sukses";
            return $this->responseSukses($data, 201); 
        } catch (Exception $e) {
            $msg = $this->serviceError;
            return $this->responseError($msg, $e->getMessage(), 400);
        }
    }

}