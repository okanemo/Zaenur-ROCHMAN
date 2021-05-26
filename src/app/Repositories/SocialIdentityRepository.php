<?php

namespace App\Repositories;

use App\Models\SocialIdentity;

class SocialIdentityRepository {

    protected $socialIdentity;

    public function __construct(SocialIdentity $socialIdentity)
    {
        $this->socialIdentity = $socialIdentity;
    }

    public function addNew($data){
        return $this->socialIdentity->create([
            "user_id" => $data['user_id'],
            "provider_name" => $data['provider_name'],
            "provider_id" => $data['provider_id'],
        ]);
    }

    public function getByProviderId($id){
        return $this->socialIdentity->where('provider_id',$id)->first();
    }

}