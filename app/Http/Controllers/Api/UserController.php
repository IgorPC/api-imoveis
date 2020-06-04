<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = $this->user->paginate(10);

        return response()->json($users, 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if(!$request->has('password') || !$request->get('password')){
            $message = new ApiMessages("É necessario preencher uma senha para o usuario");
            return response()->json($message->getMessage(), 401);
        }

        Validator::make($data, [
           'phone' => 'required',
            'mobile_phone' => 'required'
        ])->validate();

        try{

            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data);
            $user->profile()->create(
                [
                    'phone' => $data['phone'],
                    'mobile_phone' => $data['mobile_phone']
                ]
            );
            return response()->json([
                'data'=> [
                    'msg' => 'Usuario cadastrado com sucesso'
                ]
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }

    public function show($id)
    {
        try{

            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);

            return response()->json([
                'data'=> $user
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        if($request->has('password') && $request->get('password')){
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }

        Validator::make($data, [
            'profile.phone' => 'required',
            'profile.mobile_phone' => 'required'
        ])->validate();

        try{
            $profile = $data['profile'];
            $profile['social_networks'] = \Opis\Closure\serialize($profile['social_networks']);
            $user = $this->user->findOrFail($id);
            $user->update($data);

            $user->profile()->update($profile);
            return response()->json([
                'data'=> [
                    'msg' => 'Usuario foi atualizado com sucesso'
                ]
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try{

            $user = $this->user->findOrFail($id);
            $user->delete();

            return response()->json([
                'data'=> [
                    'msg' => 'Usuario foi removido com sucesso'
                ]
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }
}
