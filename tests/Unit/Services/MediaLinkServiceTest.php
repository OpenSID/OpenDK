<?php

use App\Services\MediaLinkService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Media Link Service
|--------------------------------------------------------------------------
|
| Menguji resolusi link media (Google Drive, YouTube, Vimeo, file langsung)
| menjadi tipe media + URL yang bisa langsung ditampilkan di web/admin.
|
*/

beforeEach(function () {
    $this->service = new MediaLinkService();
});

describe('youtube', function () {
    it('resolves a regular watch url', function () {
        $media = $this->service->resolve('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        expect($media->type)->toBe('youtube')
            ->and($media->id)->toBe('dQw4w9WgXcQ')
            ->and($media->embedUrl)->toBe('https://www.youtube.com/embed/dQw4w9WgXcQ')
            ->and($media->thumbnail)->toBe('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
    });

    it('resolves short, embed, shorts and live urls', function (string $url, string $id) {
        expect($this->service->resolve($url)->type)->toBe('youtube')
            ->and($this->service->resolve($url)->id)->toBe($id);
    })->with([
        ['https://youtu.be/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
        ['https://www.youtube.com/embed/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
        ['https://www.youtube.com/shorts/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
        ['https://www.youtube.com/live/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
        ['https://m.youtube.com/watch?v=dQw4w9WgXcQ&t=30s', 'dQw4w9WgXcQ'],
    ]);

    it('ignores a non youtube url', function () {
        expect($this->service->resolve('https://opendesa.id')->type)->not->toBe('youtube');
    });
});

describe('google drive', function () {
    it('resolves the file view url to a direct image url', function () {
        $media = $this->service->resolve('https://drive.google.com/file/d/1AbCdEf123/view?usp=sharing');

        expect($media->type)->toBe('image')
            ->and($media->id)->toBe('1AbCdEf123')
            ->and($media->url)->toBe('https://drive.google.com/uc?export=view&id=1AbCdEf123')
            ->and($media->embedUrl)->toBeNull();
    });

    it('resolves the uc export url', function () {
        $media = $this->service->resolve('https://drive.google.com/uc?export=view&id=1AbCdEf123');

        expect($media->id)->toBe('1AbCdEf123')
            ->and($media->url)->toBe('https://drive.google.com/uc?export=view&id=1AbCdEf123');
    });

    it('resolves the open id url', function () {
        $media = $this->service->resolve('https://drive.google.com/open?id=1AbCdEf123');

        expect($media->id)->toBe('1AbCdEf123')
            ->and($media->url)->toBe('https://drive.google.com/uc?export=view&id=1AbCdEf123');
    });

    it('resolves a drive video to a playable preview embed', function () {
        $media = $this->service->resolve('https://drive.google.com/file/d/1AbCdEf123/view', 'video');

        expect($media->type)->toBe('video')
            ->and($media->embedUrl)->toBe('https://drive.google.com/file/d/1AbCdEf123/preview')
            ->and($media->url)->toBe('https://drive.google.com/uc?export=download&id=1AbCdEf123');
    });

    it('resolves the docs.google.com viewer url', function () {
        expect($this->service->resolve('https://docs.google.com/file/d/1AbCdEf123/view')->id)->toBe('1AbCdEf123');
    });

    it('marks a drive folder as unknown', function () {
        $media = $this->service->resolve('https://drive.google.com/drive/folders/1AbCdEf123');

        expect($media->type)->toBe('unknown')
            ->and($media->id)->toBeNull();
    });
});

describe('direct files', function () {
    it('detects image urls by extension', function (string $url) {
        $media = $this->service->resolve($url);

        expect($media->type)->toBe('image')
            ->and($media->url)->toBe($url)
            ->and($media->embedUrl)->toBeNull();
    })->with([
        ['https://contoh.sitim.go.id/foto/kegiatan.jpg'],
        ['https://contoh.sitim.go.id/foto/kegiatan.JPEG'],
        ['https://contoh.sitim.go.id/foto/kegiatan.png'],
        ['https://contoh.sitim.go.id/foto/kegiatan.webp'],
        ['https://contoh.sitim.go.id/foto/kegiatan.gif'],
    ]);

    it('detects video urls by extension', function (string $url) {
        $media = $this->service->resolve($url);

        expect($media->type)->toBe('video')
            ->and($media->url)->toBe($url);
    })->with([
        ['https://contoh.sitim.go.id/video/kegiatan.mp4'],
        ['https://contoh.sitim.go.id/video/kegiatan.webm'],
        ['https://contoh.sitim.go.id/video/kegiatan.ogv'],
        ['https://contoh.sitim.go.id/video/kegiatan.mov'],
    ]);

    it('resolves a vimeo url to an embed', function () {
        $media = $this->service->resolve('https://vimeo.com/123456789');

        expect($media->type)->toBe('video')
            ->and($media->id)->toBe('123456789')
            ->and($media->embedUrl)->toBe('https://player.vimeo.com/video/123456789');
    });
});

describe('fallback', function () {
    it('returns unknown for a plain url without media hints', function () {
        $media = $this->service->resolve('https://opendesa.id');

        expect($media->type)->toBe('unknown')
            ->and($media->url)->toBe('https://opendesa.id')
            ->and($media->embedUrl)->toBeNull()
            ->and($media->thumbnail)->toBeNull();
    });

    it('returns unknown for empty or invalid values', function (?string $url) {
        $media = $this->service->resolve($url);

        expect($media->type)->toBe('unknown')
            ->and($media->url)->toBeNull();
    })->with([null, '', '   ', 'bukan-url']);

    it('respects the manual type hint for unknown urls', function () {
        expect($this->service->resolve('https://opendesa.id', 'image')->type)->toBe('image')
            ->and($this->service->resolve('https://opendesa.id', 'video')->type)->toBe('video');
    });

    it('never trusts a type hint that contradicts the provider', function () {
        expect($this->service->resolve('https://youtu.be/dQw4w9WgXcQ', 'image')->type)->toBe('youtube');
    });
});

describe('probing', function () {
    it('returns the media type from the content-type header', function (string $contentType, string $expected) {
        Http::fake(['*' => Http::response('', 200, ['Content-Type' => $contentType])]);

        expect($this->service->probe('https://contoh.sitim.go.id/media/berkas'))->toBe($expected);
    })->with([
        ['image/jpeg', 'image'],
        ['image/png', 'image'],
        ['video/mp4', 'video'],
        ['application/octet-stream', 'unknown'],
    ]);

    it('falls back to the filename extension when content type is generic', function () {
        Http::fake(['*' => Http::response('', 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="dokumentasi.mp4"',
        ])]);

        expect($this->service->probe('https://contoh.sitim.go.id/media/berkas'))->toBe('video');
    });

    it('resolves the drive download url before probing', function () {
        Http::fake([
            'drive.google.com/*' => Http::response('', 200, ['Content-Type' => 'video/mp4']),
            '*' => Http::response('', 500),
        ]);

        expect($this->service->probe('https://drive.google.com/file/d/1AbCdEf123/view'))->toBe('video');

        Http::assertSent(function (Request $request) {
            return str_contains($request->url(), 'uc?export=download&id=1AbCdEf123');
        });
    });

    it('returns null when the request fails', function () {
        Http::fake(['*' => Http::response('', 500)]);

        expect($this->service->probe('https://contoh.sitim.go.id/media/berkas'))->toBeNull();
    });

    it('does not probe empty urls', function () {
        Http::preventStrayRequests();

        expect($this->service->probe(null))->toBeNull()
            ->and($this->service->probe('bukan-url'))->toBeNull();
    });
});

describe('detectType', function () {
    it('uses the resolved type without touching the network', function () {
        Http::preventStrayRequests();

        expect($this->service->detectType('https://youtu.be/dQw4w9WgXcQ'))->toBe('youtube')
            ->and($this->service->detectType('https://contoh.sitim.go.id/foto.jpg'))->toBe('image')
            ->and($this->service->detectType('https://contoh.sitim.go.id/video.mp4'))->toBe('video');
    });

    it('probes only when the url is inconclusive', function () {
        Http::fake(['*' => Http::response('', 200, ['Content-Type' => 'video/mp4'])]);

        expect($this->service->detectType('https://contoh.sitim.go.id/media/berkas'))->toBe('video');

        Http::assertSentCount(1);
    });

    it('probes google drive links because they carry no file extension', function () {
        Http::fake([
            'drive.google.com/*' => Http::response('', 200, ['Content-Type' => 'video/mp4']),
            '*' => Http::response('', 500),
        ]);

        expect($this->service->detectType('https://drive.google.com/file/d/1AbCdEf123/view'))->toBe('video');

        Http::assertSentCount(1);
    });

    it('falls back to the default drive type when probing fails', function () {
        Http::fake(['*' => Http::response('', 500)]);

        expect($this->service->detectType('https://drive.google.com/file/d/1AbCdEf123/view'))->toBe('image');
    });

    it('never probes when the user picked the type manually', function () {
        Http::preventStrayRequests();

        expect($this->service->detectType('https://contoh.sitim.go.id/media/berkas', 'image'))->toBe('image')
            ->and($this->service->detectType('https://drive.google.com/file/d/1AbCdEf123/view', 'video'))->toBe('video');
    });

    it('returns unknown for a missing link', function () {
        Http::preventStrayRequests();

        expect($this->service->detectType(null))->toBe('unknown');
    });
});
