<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;
    public function test_auth_register()
    {
        $response = $this->post('/api/register',[
            'name'=>'test',
            'email'=>'test@test.com',
            'password'=>'123456',
            'password_confirmation'=>'123456',
        ]);
        $response->assertStatus(201);
    }

    public function test_auth_register_error()
    {
        $response = $this->post('/api/register',[]);
        $response->assertStatus(302);
    }

    public function test_auth_login(){
         User::create([
            'name'=>'test',
            'email'=>'test@test.com',
            'password'=>bcrypt('123456')
        ]);

        $response = $this->post('/api/login',[
            'email'=>'test@test.com',
            'password'=>'123456',
        ]);
        $response->assertStatus(200);
    }

    public function test_auth_login_error(){

        $response = $this->post('/api/login',[]);
        $response->assertStatus(302);
    }

    public function test_auth_remember(){
        User::create([
            'name'=>'test',
            'email'=>'test@test.com',
            'password'=>bcrypt('123456')
        ]);
        $response = $this->post('/api/remember',[
            'email'=>'test@test.com',
        ]);
        $response->assertStatus(200);
    }

    public function test_auth_remember_error(){

        $response = $this->post('/api/remember',[]);
        $response->assertStatus(302);
    }
}
