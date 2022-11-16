<?php

namespace Tests\Feature;

use App\Models\PictureCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_categorys(){
        PictureCategory::create([
            'name'=>'test'
        ]);
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
        ])->get('/api/category')
            ->assertOk()
            ->assertJsonFragment(['name'=>'test']);
    }

    public function test_get_categorys_error(){

        $response = $this->withHeaders([
            'Accept'=>'application/json',
        ])->get('/api/category');
        $response->assertStatus(401);
    }
}
