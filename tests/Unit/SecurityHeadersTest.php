<?php

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

function handleWithSecurityHeaders(string $env = 'production'): Response
{
    app()['env'] = $env;

    return (new SecurityHeaders())->handle(
        Request::create('/', 'GET'),
        fn () => new Response('<html><body>Hello</body></html>')
    );
}

describe('SecurityHeaders nosniff', function () {
    test('menambahkan X-Content-Type-Options nosniff di production', function () {
        $response = handleWithSecurityHeaders('production');

        expect($response->headers->get('X-Content-Type-Options'))->toBe('nosniff');
    });

    test('nosniff tetap dipasang di luar production', function () {
        try {
            $response = handleWithSecurityHeaders('testing');

            expect($response->headers->get('X-Content-Type-Options'))->toBe('nosniff');
        } finally {
            app()['env'] = 'testing';
        }
    });

    test('tidak membocorkan versi server', function () {
        $response = handleWithSecurityHeaders('production');

        expect($response->headers->has('X-Powered-By'))->toBeFalse();
    });
});
