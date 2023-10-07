<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if ($profil !== null)
        <sitemap>
            <loc>{{ url('profil/sejarah') }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($profil->created_at)->toAtomString() }}</lastmod>
        </sitemap>
        <sitemap>
            <loc>{{ url('profil/letak-geografis') }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($profil->created_at)->toAtomString() }}</lastmod>
        </sitemap>
        <sitemap>
            <loc>{{ url('profil/struktur-pemerintahan') }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($profil->created_at)->toAtomString() }}</lastmod>
        </sitemap>
        <sitemap>
            <loc>{{ url('profil/visi-dan-misi') }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($profil->created_at)->toAtomString() }}</lastmod>
        </sitemap>
    @endif

    @if ($prosedur !== null)
        <sitemap>
            <loc>{{ url('unduhan/prosedur') }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($prosedur->created_at)->toAtomString() }}</lastmod>
        </sitemap>
    @endif

    @if ($regulasi !== null)
        <sitemap>
            <loc>{{ url('unduhan/regulasi') }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($regulasi->created_at)->toAtomString() }}</lastmod>
        </sitemap>
    @endif

    @if ($dokumen !== null)
        <sitemap>
            <loc>{{ url('unduhan/form-dokumen') }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($dokumen->created_at)->toAtomString() }}</lastmod>
        </sitemap>
    @endif
</sitemapindex>
