<?php

$nginxConf = dirname(__DIR__, 2).'/.docker/nginx/conf.d/default.conf';

describe('Nginx upload guard', function () use ($nginxConf) {
    test('memblokir eksekusi skrip di bawah /storage/', function () use ($nginxConf) {
        $config = file_get_contents($nginxConf);

        expect($config)->toContain('^/storage/');
        expect($config)->toMatch('/location\s+~\*\s+\^\/storage\/.*phtml/');
    });

    test('guard /storage/ ditulis sebelum location .php agar tidak ditimpa', function () use ($nginxConf) {
        $config = file_get_contents($nginxConf);

        $storageGuard = strpos($config, '^/storage/');
        $phpHandler = strpos($config, 'location ~ \.php$');

        expect($storageGuard)->not->toBeFalse();
        expect($phpHandler)->not->toBeFalse();
        expect($storageGuard)->toBeLessThan($phpHandler);
    });

    test('blokir mencakup daftar ekstensi eksekutable yang umum', function () use ($nginxConf) {
        $config = file_get_contents($nginxConf);

        foreach (['php', 'phtml', 'phar', 'cgi', 'jsp', 'asp'] as $extension) {
            expect($config)->toContain($extension);
        }
    });
});
