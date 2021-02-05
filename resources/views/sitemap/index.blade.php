<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('sitemap/profil') }}</loc>
        <lastmod>{{ $profil->created_at->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap/profil/sejarah') }}</loc>
        <lastmod>{{ $profil->created_at->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap/profil/letak-geografis') }}</loc>
        <lastmod>{{ $profil->created_at->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap/profil/struktur-pemerintahan') }}</loc>
        <lastmod>{{ $profil->created_at->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap/profil/visi-dan-misi') }}</loc>
        <lastmod>{{ $profil->created_at->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap/unduhan/prosedur') }}</loc>
        <lastmod>{{ $prosedur->created_at->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap/unduhan/regulasi') }}</loc>
        <lastmod>{{ $regulasi->created_at->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap/unduhan/form-dokumen') }}</loc>
        <lastmod>{{ $dokumen->created_at->toAtomString() }}</lastmod>
    </sitemap>
</sitemapindex>