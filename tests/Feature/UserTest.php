<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_new_user(): void
    {
        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('create')->with([
            'first_name' => 'kevin',
            'last_name' => 'MONNET',
            'email' => 'test@test.fr',
            'password' => bcrypt('azerty'),

        ])
            ->andReturn((object) [
                'first_name' => 'kevin',
                'last_name' => 'MONNET',
                'email' => 'test@test.fr',
            ]);
        $this->app->instance(User::class, $mockUser);

        $request = new Request([
            'first_name' => 'kevin',
            'last_name' => 'MONNET',
            'email' => 'test@test.fr',
            'password' => 'azerty',
        ]);
        $controller = new AuthController($mockUser);
        $response = $controller->register($request);

        $this->assertEquals(200, $response->status());
    }
}
