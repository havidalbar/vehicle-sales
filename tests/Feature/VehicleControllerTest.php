<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Car;
use App\Models\Motorcycle;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class VehicleControllerTest extends TestCase
{

    protected $user;

    /**
     * Create user and get token
     * @return string
     */
    protected function authenticate(){
        $data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
        ];
        
        $responseRegister = $this->json('POST', route('api.register'), $data);
        
        $responseRegister->assertStatus(200);

        $this->assertArrayHasKey('data', $responseRegister->json());
        $user = User::where('email','test@gmail.com')->first();
        $token = JWTAuth::fromUser($user);
        return $token;
    }
    
    public function test_create_motorcycle()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('api.vehicle'),[
                    'year_release' => '2002',   
                    'color' => 'Black',
                    'price' => '100',
                    'suspension_type' => 'suspension',   
                    'transmissi_type' => 'transmissi',
                    'engine' => 'AT',
                    'vehicle_type' => 'motorcycle'
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());

        User::where('email', 'test@gmail.com')->first()->delete();
        Motorcycle::where('_id',$response->json()['data']['_id'])->first()->delete();
    }

    public function test_create_car()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('api.vehicle'),[
                    'year_release' => '2002',   
                    'color' => 'Blue',
                    'price' => '100',
                    'passenger_capacity' => '4',   
                    'type' => 'i8',
                    'engine' => 'AT',
                    'vehicle_type' => 'car'
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());

        User::where('email', 'test@gmail.com')->first()->delete();
        Car::where('_id', $response->json()['data']['_id'])->first()->delete();
    }

    public function test_get_quota_vehicle()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->get(route('api.quota.vehicle'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('car', $response->json()['data']);
        $this->assertArrayHasKey('motorcycle', $response->json()['data']);

        User::where('email', 'test@gmail.com')->first()->delete();
    }

    public function test_get_leftover_quota_vehicle()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->get(route('api.leftover.quota.vehicle'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('car', $response->json()['data']);
        $this->assertArrayHasKey('motorcycle', $response->json()['data']);

        User::where('email', 'test@gmail.com')->first()->delete();
    }

    public function test_get_vehicle_by_id()
    {
        $token = $this->authenticate();

        $responseVehicle = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('api.vehicle'),[
                    'year_release' => '2002',   
                    'color' => 'Blue',
                    'price' => '100',
                    'passenger_capacity' => '4',   
                    'type' => 'i8',
                    'engine' => 'AT',
                    'vehicle_type' => 'car'
        ]);
        $responseVehicle->assertStatus(200);
        $this->assertArrayHasKey('data', $responseVehicle->json());
        $idVehicle = $responseVehicle->json()['data']['_id'];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->get(route('api.vehicle.id',['id' => $idVehicle]));
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('_id', $response->json()['data']);

        User::where('email', 'test@gmail.com')->first()->delete();
        Car::where('_id',$idVehicle)->first()->delete();
    }
}
