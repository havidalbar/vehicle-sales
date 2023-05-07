<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Car;
use App\Models\Motorcycle;
use App\Models\Sales;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class SalesControllerTest extends TestCase
{
    protected $user;

    /**
     * Create user and get token
     * @return string
     */
    protected function authenticate(){
        $data = [
            'name' => 'test',
            'email' => 'test2@gmail.com',
            'password' => '123456',
        ];
        
        $responseRegister = $this->json('POST', route('api.register'), $data);
        
        $responseRegister->assertStatus(200);

        $this->assertArrayHasKey('data', $responseRegister->json());
        $user = User::where('email','test2@gmail.com')->first();
        return $user;
    }

    protected function create_car($token)
    {

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

        $idVehicle = $response->json()['data']['_id'];

        return $idVehicle;
    }

    public function test_post_order()
    {
        $user = $this->authenticate();
        $token = JWTAuth::fromUser($user);
        $idVehicle = $this->create_car($token);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('api.order'),[
                    'vehicle_id' => $idVehicle,   
                    'user_id' => $user->_id,
                    'region' => 'surabaya',
                    'price' => '10000',   
                    'sales_person' => 'budi'
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('_id', $response->json()['data']);

        User::where('email', 'test2@gmail.com')->first()->delete();
        Car::where('_id',$idVehicle)->first()->delete();
        Sales::where('_id',$response->json()['data']['_id'])->first()->delete();
    }

    public function test_get_order()
    {
        $user = $this->authenticate();
        $token = JWTAuth::fromUser($user);
        $idVehicle = $this->create_car($token);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->get(route('api.order'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('total price', $response->json()['data']);
        $this->assertArrayHasKey('total group region', $response->json()['data']);
        $this->assertArrayHasKey('total price each region', $response->json()['data']);
        $this->assertArrayHasKey('total sales of each marketer', $response->json()['data']);
        $this->assertArrayHasKey('invoice', $response->json()['data']);

        User::where('email', 'test2@gmail.com')->first()->delete();
        Car::where('_id',$idVehicle)->first()->delete();
    }

    public function test_get_order_sold()
    {
        $user = $this->authenticate();
        $token = JWTAuth::fromUser($user);
        $idVehicle = $this->create_car($token);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->get(route('api.order.sold'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('total price', $response->json()['data']['car']);
        $this->assertArrayHasKey('total each region', $response->json()['data']['car']);
        $this->assertArrayHasKey('total price each region', $response->json()['data']['car']);
        $this->assertArrayHasKey('total sales of each marketer', $response->json()['data']['car']);
        $this->assertArrayHasKey('invoice', $response->json()['data']['car']);

        User::where('email', 'test2@gmail.com')->first()->delete();
        Car::where('_id',$idVehicle)->first()->delete();
    }

    public function test_get_order_id()
    {
        $user = $this->authenticate();
        $token = JWTAuth::fromUser($user);
        $idVehicle = $this->create_car($token);

        $responseSales = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('api.order'),[
                    'vehicle_id' => $idVehicle,   
                    'user_id' => $user->_id,
                    'region' => 'surabaya',
                    'price' => '10000',   
                    'sales_person' => 'budi'
        ]);
        $responseSales->assertStatus(200);
        $this->assertArrayHasKey('data', $responseSales->json());
        $this->assertArrayHasKey('_id', $responseSales->json()['data']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->get(route('api.order.id', ['id' => $responseSales->json()['data']['_id']]));
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('vehicle_id', $response->json()['data']);
        $this->assertArrayHasKey('user_id', $response->json()['data']);

        User::where('email', 'test2@gmail.com')->first()->delete();
        Car::where('_id',$idVehicle)->first()->delete();
        Sales::where('_id',$responseSales->json()['data']['_id'])->first()->delete();
    }
}

