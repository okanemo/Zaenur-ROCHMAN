<?php

namespace App\Http\Controllers;

use App\Models\SocialIdentity;
use App\Models\User;
use App\Repositories\SocialIdentityRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;
// use Socialite;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $userRepository, $jwt;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, JWTAuth $jWTAuth)
    {
        $this->userRepository = $userRepository;
        $this->jwt = $jWTAuth;
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'username'  => 'required|unique:users,username',
                'name'      => 'required',
                'password'  => 'required|
                                min:6|
                                confirmed',
                'email'     => 'required|email|unique:users,email'
            ]);

            if ($validator->fails()) {
                $msg = $this->validationError;
                return $this->responseError($msg, $validator->errors(), 400);
            }
            $data_in = [
                "name" => $request->name,
                "username" => $request->username,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ];
            $user = $this->userRepository->addNewUser($data_in);
            $data['data'] = $this->handleNullMultiDimensi(array($user));
            $data['message'] = "Request sukses";
            return $this->responseSukses($data, 201);
        } catch (Exception $e) {
            $msg = $this->serviceError;
            return $this->responseError($msg, $e->getMessage(), 400);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'username'  => 'required',
                'password'  => 'required',
            ]);

            if ($validator->fails()) {
                $msg = $this->validationError;
                return $this->responseError($msg, $validator->errors(), 400);
            }

            $data_in = [
                "username" => $request->username,
                "password" => $request->password
            ];

            if (!$this->userRepository->authenticate($data_in)) {
                $data['message'] = "Oops bad credentials.";
                $data['data'] = [];
                return $this->responseSukses($data, 401);
            }
            $token = $this->jwt->attempt($data_in);
            $data['token'] = $token;
            $data['token_type'] = 'bearer';
            $data['expires_in'] = $this->jwt->factory()->getTTL() * 1440;
            $data['message'] = $this->loginSukses;
            $data['data'] = "-";
            return $this->responseSukses($data, 200);
        } catch (Exception $e) {
            $msg = $this->serviceError;
            return $this->responseError($msg, $e->getMessage(), 400);
        }
    }

    public function redirectToProvider($provider)
    {
        $config = [
            'client_id' => '9241c2f139bccf098913',
            'client_secret' => 'a4f3377b1d3514fd8cecaa8976af7b159bae4e89',
            'redirect' => 'http://127.0.0.1:8080/login/github/callback'
        ];

        $provider = Socialite::buildProvider(
            \Laravel\Socialite\Two\GithubProvider::class,
            $config
        );

        return $provider->stateless()->redirect();
        //    return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(SocialIdentityRepository $socialIdentityRepository)
    {
        $config = [
            'client_id' => '9241c2f139bccf098913',
            'client_secret' => 'a4f3377b1d3514fd8cecaa8976af7b159bae4e89',
            'redirect' => 'http://127.0.0.1:8080/login/github/callback'
        ];

        $provider = Socialite::buildProvider(
            \Laravel\Socialite\Two\GithubProvider::class,
            $config
        );
        $providerUser = $provider->stateless()->user();
        // dd($providerUser->getId());
        $password = "random";
        $user = $this->userRepository->getByEmail($providerUser->getEmail());
        if (empty($user)) {
            $data_in = [
                "username" => $providerUser->getNickName(),
                "email" => $providerUser->getEmail(),
                "name" => $providerUser->getName(),
                "password" => Hash::make("random"),
            ];
            $user = $this->userRepository->addNewUser($data_in);
        }

        $data_social = $socialIdentityRepository->getByProviderId($providerUser->getId());
        if(empty($data_social)){
            $data_social = [
                "user_id" => $user->id,
                "provider_id" => $providerUser->getId(),
                "provider_name" => "github"
            ];
            $socialIdentityRepository->addNew($data_social);
        }

        $data_in = [
            "username" => $user->username,
            "password" => $password
        ];
        $token = $this->jwt->attempt($data_in);
        $data['token'] = $token;
        $data['token_type'] = 'bearer';
        $data['expires_in'] = $this->jwt->factory()->getTTL() * 1440;
        $data['message'] = $this->loginSukses;
        $data['data'] = "-";
        return $this->responseSukses($data, 200);
    }

    //
}
