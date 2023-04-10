<?php

namespace Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;

class UserTest extends TestCase
{
    private static $user = [
        "first_name" => "John",
        "last_name" => "Doe",
        "gender" => "male",
        "date_of_birth" => "1990-01-01",
        "email" => "testuser@example.com",
        "password" => "password",
        "password_confirmation" => "password"
    ];

    /**
     * Test API.
     *
     * @return void
     */
    public function test_test()
    {
        $response = $this->get('/api/test');

        $response->assertStatus(200);
    }

    /**
     * Test user registration.
     *
     * @return void
     */
    public function test_register()
    {
        self::$user['email'] = "testuser" . uniqid() . "@example.com";
        $response = $this->json('POST', '/api/register', self::$user);
        $this->assertIsInt($response->json('user.id'));
        $response
            ->assertStatus(201)
            ->assertJsonFragment([]);


        self::$user['id'] = $response->json('user.id');
    }

    /**
     * Test user login.
     *
     * @return void
     */
    public function test_login()
    {
        $response = $this->json('POST', '/api/login', self::$user);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([]);

        self::$user['access_token'] = $response->json('access_token');
    }

    /**
     * Test View profile
     *
     * @return void
     */
    public function test_view_profile()
    {
        $response = $this->json('POST', '/api/view-profile', array('access_token' => self::$user['access_token']));
        $response
            ->assertStatus(200)
            ->assertJsonFragment([]);
    }

    /**
     * Test Update profile
     *
     * @return void
     */
    public function test_update_profile()
    {
        self::$user['first_name'] = "thusitha";
        $response = $this->json('POST', '/api/update-profile/' . self::$user['id'], Arr::except(self::$user, ['id']));
        $response
            ->assertStatus(200)
            ->assertJsonFragment([]);
        $this->assertSame(self::$user['first_name'], $response->json('user.first_name'));
    }
}
