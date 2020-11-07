<?php


namespace App\Api\v1\Transformers;


use App\Api\v1\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    public function transform(User $user)
    {
        return [
            'id'    => (int) $user->id,
            'name'  => (string) $user->name,
            'email' => (string) $user->email,
            'links'   => [
                'rel' => 'self',
                'uri' => route('v1.users.show', $user),
            ]
        ];
    }

}