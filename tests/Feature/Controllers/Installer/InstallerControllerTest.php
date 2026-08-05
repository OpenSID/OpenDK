<?php

namespace Tests\Feature\Controllers\Installer;

use Tests\TestCase;
use App\Http\Middleware\InstallerCheck;
use App\Http\Requests\Installer\EnvironmentWizardSaveRequest;
use App\Http\Requests\Installer\EnvironmentClassicSaveRequest;
use App\Http\Requests\Installer\PerformInstallationRequest;
use Illuminate\Http\Request;

describe('Installer Controller', function () {
    test('middleware installer.check redirects to / when sudahInstal() is true', function () {
        $middleware = new InstallerCheck();

        $request = Request::create('/test', 'GET');
        $response = $middleware->handle($request, function () {
            return response('OK');
        });

        expect($response->getStatusCode())->toBe(302);
        expect($response->getTargetUrl())->toBe(app('url')->to('/'));
    });

    test('EnvironmentWizardSaveRequest fails when required fields are missing', function () {
        $request = new EnvironmentWizardSaveRequest();

        $validator = $this->app['validator']->make(
            [],
            $request->rules()
        );

        expect($validator->fails())->toBeTrue();
        expect($validator->errors()->has('app_name'))->toBeTrue();
        expect($validator->errors()->has('app_environment'))->toBeTrue();
        expect($validator->errors()->has('app_debug'))->toBeTrue();
        expect($validator->errors()->has('app_url'))->toBeTrue();
        expect($validator->errors()->has('database_connection'))->toBeTrue();
        expect($validator->errors()->has('database_hostname'))->toBeTrue();
        expect($validator->errors()->has('database_port'))->toBeTrue();
        expect($validator->errors()->has('database_name'))->toBeTrue();
        expect($validator->errors()->has('database_username'))->toBeTrue();
    });

    test('EnvironmentWizardSaveRequest passes with valid data', function () {
        $request = new EnvironmentWizardSaveRequest();

        $validator = $this->app['validator']->make(
            [
                'app_name'            => 'Test App',
                'app_environment'     => 'local',
                'app_debug'           => 'true',
                'app_url'             => 'http://localhost',
                'database_connection' => 'mysql',
                'database_hostname'   => '127.0.0.1',
                'database_port'       => '3306',
                'database_name'       => 'test_db',
                'database_username'   => 'root',
            ],
            $request->rules()
        );

        expect($validator->passes())->toBeTrue();
    });

    test('EnvironmentClassicSaveRequest fails when envConfig is missing', function () {
        $request = new EnvironmentClassicSaveRequest();

        $validator = $this->app['validator']->make(
            [],
            $request->rules()
        );

        expect($validator->fails())->toBeTrue();
        expect($validator->errors()->has('envConfig'))->toBeTrue();
    });

    test('EnvironmentClassicSaveRequest passes with valid envConfig', function () {
        $request = new EnvironmentClassicSaveRequest();

        $validator = $this->app['validator']->make(
            ['envConfig' => 'APP_NAME="Test"'],
            $request->rules()
        );

        expect($validator->passes())->toBeTrue();
    });

    test('PerformInstallationRequest has empty rules', function () {
        $request = new PerformInstallationRequest();

        expect($request->rules())->toBeArray();
        expect($request->rules())->toBeEmpty();
    });
});
