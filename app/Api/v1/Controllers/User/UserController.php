<?php


namespace App\Api\v1\Controllers\User;

use App\Api\v1\Controllers\ApiController;
use App\Api\v1\Models\User;
use App\Api\v1\Requests\User\EditUserRequest;
use App\Api\v1\Requests\User\StoreUserRequest;
use App\Api\v1\Transformers\UserTransformer;

class UserController extends ApiController
{

    public function index()
    {
        $allUsers = User::query()->paginate(5);
        
        return $this->response->paginator($allUsers, new UserTransformer);
    }

    public function show($id)
    {
        $user = User::query()->find($id);
        
        if($user) {
            return $this->response->item($user, new UserTransformer);
        }
        
        return $this->response->errorNotFound();
    }
    
    public function store(StoreUserRequest $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        
        $user = User::query()->create($data);
        
        if($user) {
            return $this->response->created(route('v1.users.show', $user), $user->toArray());
        }
        
        return $this->response->errorBadRequest();
    }
    
    public function edit(EditUserRequest $request)
    {
        $data = $request->only(['name']);
        /** @var User $user */
        $user = $request->user();
        
        $user->fill([
            'name' => $data['name'],
        ])->save();
        
        return $this->response->item($user, new UserTransformer);
    }
    
    public function destroy($id)
    {
        $user = User::query()->find($id);
        
        if($user) {
            $user->delete();
            return $this->response->noContent();
        }

        return $this->response->errorBadRequest();
    }

}