<?php

namespace Tests\Feature;

use App\Models\Picture;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiPictureTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_get_pictures_empty()
    {
        $user = User::create([
            'name'=>'test',
            'email'=>'test@test.com',
            'password'=>bcrypt('123456'),
            'email_verified'=>1
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = $this->withHeaders([
            'Accept'=>'application/json',
            'Authorization'=>'Bearer '.$token
        ])
            ->get('/api/pictures');

        $response->assertStatus(200);
    }

    public function test_get_pictures_errors()
    {
        $response = $this->withHeaders(['Accept'=>'application/json'])->get('/api/pictures');
        $response->assertStatus(401);
    }

    public function test_create_picture(){

        Storage::fake('public/pictures');

        $user = User::create([
            'name'=>'test',
            'email'=>'test123@test.com',
            'password'=>bcrypt('123456'),
            'email_verified'=>1
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = $this->withHeaders([
            'Accept'=>'application/json',
            'Authorization'=>'Bearer '.$token
        ])
            ->post('/api/pictures',[
                'name'=>'test',
                'image' => UploadedFile::fake()->image('public/pictures/avatar.jpg')
            ]);
        $response->assertStatus(201);
    }

    public function test_create_picture_error(){

        $user = User::create([
            'name'=>'test',
            'email'=>'test123@test.com',
            'password'=>bcrypt('123456'),
            'email_verified'=>1
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = $this->withHeaders([
            'Accept'=>'application/json',
            'Authorization'=>'Bearer '.$token
        ])->post('/api/pictures',[]);
        $response->assertStatus(422);
    }

    public function test_create_picture_error_no_auth(){
        $response = $this->withHeaders([
            'Accept'=>'application/json',
        ])->post('/api/pictures',[
            'name'=>'test',
            'image' => UploadedFile::fake()->image('public/pictures/avatar.jpg')
        ]);
        $response->assertStatus(401);
    }
}
