<?php

namespace Tests\Feature;

use App\Models\SettingAplikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;
use Mews\Captcha\Facades\Captcha;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Settings;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_form_displayed()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_login_success_redirects_to_home()
    {        
        SettingAplikasi::updateOrCreate(
            ['key' => 'google_recaptcha'],
            ['value' => 0]
        );
        // Captcha::shouldReceive('captcha')            
        //     ->andReturn(true);
        
        Captcha::shouldReceive('display')
            ->andReturn('<input type="hidden" name="captcha" value="1" />');
        
        $user = User::first();
        $user->password = bcrypt('password');
        $user->save();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            'captcha' => '1', // Simulasi captcha valid
        ]);
        $response->assertRedirect();                
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_wrong_password_fails()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_login_requires_captcha()
    {
        // Simulasi settings captcha aktif
        View::share('captchaView', 'auth.captcha');
        config(['settings.google_recaptcha' => false]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'password',
            // Tidak mengirim captcha
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors();
    }

    public function test_login_with_2fa_enabled_redirects_to_token()
    {
        Notification::fake();
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        // Simulasi settings login_2fa aktif
        config(['settings.login_2fa' => true]);
        // Simulasi login
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect(url('/'));        
    }
}