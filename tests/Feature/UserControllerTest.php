<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use JWTAuth;


class UserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_register()
    {
        $data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
        ];

        $response = $this->json('POST', route('api.register'), $data);
        
        $response->assertStatus(200);

        $this->assertArrayHasKey('data', $response->json());
        
        User::where('email', 'test@gmail.com')->delete();
    }

    public function test_login()
    {
        $data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
        ];

        $responseRegister = $this->json('POST', route('api.register'), $data);
        
        $responseRegister->assertStatus(200);

        $this->assertArrayHasKey('data', $responseRegister->json());
        

        $responseLogin = $this->json('POST', route('api.login'),
        [
            'email' => 'test@gmail.com',
            'password' => '123456',
        ]);
        
        $responseLogin->assertStatus(200);
        $this->assertArrayHasKey('token', $responseLogin->json());
        
        User::where('email', 'test@gmail.com')->delete();
    }  
    
    public function test_logout()
    {
        $data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
        ];
        
        $responseRegister = $this->json('POST', route('api.register'), $data);
        
        $responseRegister->assertStatus(200);

        $this->assertArrayHasKey('data', $responseRegister->json());
        

        $responseLogin = $this->json('POST', route('api.login'),
        [
            'email' => 'test@gmail.com',
            'password' => '123456',
        ]);
        
        $responseLogin->assertStatus(200);
        $this->assertArrayHasKey('token', $responseLogin->json());
        
        $user = User::where('email','test@gmail.com')->first();
        $token = JWTAuth::fromUser($user);

        $this->json('POST', route('api.logout'),
        [
            'token' => $token
        ])->assertStatus(200);    
        
        User::where('email', 'test@gmail.com')->delete();
    }

    public function test_get_info_user()
    {
        $data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
        ];
        
        $responseRegister = $this->json('POST', route('api.register'), $data);
        
        $responseRegister->assertStatus(200);

        $this->assertArrayHasKey('data', $responseRegister->json());
        

        $responseLogin = $this->json('POST', route('api.login'),
        [
            'email' => 'test@gmail.com',
            'password' => '123456',
        ]);
        
        $responseLogin->assertStatus(200);
        $this->assertArrayHasKey('token', $responseLogin->json());
        
        $user = User::where('email','test@gmail.com')->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->get(route('api.info'))->assertStatus(200);  
        
        $this->assertArrayHasKey('user', $response->json());

        User::where('email', 'test@gmail.com')->delete();
    }
}
