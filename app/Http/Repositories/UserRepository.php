<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    public function create($data)
    {
        $user = new User();
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = bcrypt($data->password);
        $user->save();
        return $user;
    }
    
}

