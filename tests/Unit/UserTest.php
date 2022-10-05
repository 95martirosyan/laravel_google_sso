<?php

namespace Tests\Unit;

use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Tests\CreatesApplication;

class UserTest extends BaseTestCase
{
    use CreatesApplication;
    protected $baseUrl = 'http://localhost:8000';
    /**
     * A basic test example.
     *
     * @return void
     */
    /**
     * Mock the Socialite Factory, so we can hijack the OAuth Request.
     * @param  string  $email
     * @param  string  $token
     * @param  int $id
     * @return void
     */
    public function mockSocialiteFacade($email = '95martirosyan@gmail.com', $token = 'foo', $id = 1)
    {
        $socialiteUser = $this->createMock(\Laravel\Socialite\Two\User::class);
        $socialiteUser->token = $token;
        $socialiteUser->id = $id;
        $socialiteUser->email = $email;
        $socialiteUser->name = 'Ani Martirosyan';

        $provider = $this->createMock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->expects($this->any())
            ->method('user')
            ->willReturn($socialiteUser);

        $stub = $this->createMock(Socialite::class);
        $stub->expects($this->any())
            ->method('driver')
            ->willReturn($provider);

        // Replace Socialite Instance with our mock
        $this->app->instance(Socialite::class, $stub);
    }
    /** @test */
    public function is_google_login()
    {
        $response = $this->call('GET', '/redirect');

        $this->assertStringContainsString('accounts.google.com/o/oauth2/auth', $response->getTargetUrl());
    }

    /** @test */
    public function it_retrieves_google_request_and_creates_a_new_user()
    {
        // Mock the Facade and return a User Object with the email 'foo@bar.com'
        $email = '95martirosyannn@gmail.com';

        $this->mockSocialiteFacade($email);

        $this->visit('/auth/google/callback')
            ->seePageIs('/home');

        $this->seeInDatabase('users', [
            'name' => 'Ani Martirosyan',
            'email' => $email,
        ]);
    }
}
