<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>OpenDK Kecamatan API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/vendor/scribe/css/theme-default.style.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('/vendor/scribe/css/theme-default.print.css') }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
        body .content .bash-example code {
            display: none;
        }

        body .content .javascript-example code {
            display: none;
        }

        body .content .php-example code {
            display: none;
        }
    </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset('/vendor/scribe/js/tryitout-5.11.0.js') }}"></script>

    <script src="{{ asset('/vendor/scribe/js/theme-default-5.11.0.js') }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;,&quot;php&quot;]">

    <a href="#" id="nav-button">
        <span>
            MENU
            <img src="{{ asset('/vendor/scribe/images/navbar.png') }}" alt="navbar-image" />
        </span>
    </a>
    <div class="tocify-wrapper">

        <div class="lang-selector">
            <button type="button" class="lang-button" data-language-name="bash">bash</button>
            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
            <button type="button" class="lang-button" data-language-name="php">php</button>
        </div>

        <div class="search">
            <input type="text" class="search" id="input-search" placeholder="Search">
        </div>

        <div id="toc">
            <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
            </ul>
            <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
            </ul>
            <ul id="tocify-header-autentikasi" class="tocify-header">
                <li class="tocify-item level-1" data-unique="autentikasi">
                    <a href="#autentikasi">Autentikasi</a>
                </li>
                <ul id="tocify-subheader-autentikasi" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="autentikasi-POSTapi-v1-auth-login">
                        <a href="#autentikasi-POSTapi-v1-auth-login">Login</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autentikasi-POSTapi-v1-auth-logout">
                        <a href="#autentikasi-POSTapi-v1-auth-logout">Log the user out (Invalidate the token).</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autentikasi-POSTapi-v1-auth-refresh">
                        <a href="#autentikasi-POSTapi-v1-auth-refresh">Refresh a token.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autentikasi-GETapi-v1-auth-me">
                        <a href="#autentikasi-GETapi-v1-auth-me">Get the authenticated User.</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-test">
                        <a href="#endpoints-GETapi-v1-test">GET api/v1/test</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-penduduk-storedata">
                        <a href="#endpoints-POSTapi-v1-penduduk-storedata">Tambah dan Ubah Data dan Foto Penduduk Sesuai OpenSID</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-penduduk-test">
                        <a href="#endpoints-POSTapi-v1-penduduk-test">Test endpoint untuk verifikasi API berjalan.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-pembangunan">
                        <a href="#endpoints-POSTapi-v1-pembangunan">Tambah Data Pembangunan Sesuai OpenSID</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-artikel">
                        <a href="#endpoints-GETapi-frontend-v1-artikel">Display a listing of articles with advanced filtering and sorting.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-frontend-v1-artikel--id--comments">
                        <a href="#endpoints-POSTapi-frontend-v1-artikel--id--comments">Store a new comment for an article.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-artikel-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-artikel-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-kategori">
                        <a href="#endpoints-GETapi-frontend-v1-kategori">Display a listing of articles with advanced filtering and sorting.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-kategori-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-kategori-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-website-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-website-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-profil">
                        <a href="#endpoints-GETapi-frontend-v1-profil">Display a listing of profiles with advanced filtering and sorting.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-profil-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-profil-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-desa">
                        <a href="#endpoints-GETapi-frontend-v1-desa">Display a listing of desa with advanced filtering and sorting.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-desa-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-desa-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-penduduk">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-penduduk">Display statistik penduduk with dashboard and chart data.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-komplain">
                        <a href="#endpoints-GETapi-frontend-v1-komplain">Display a listing of complaints.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-frontend-v1-komplain">
                        <a href="#endpoints-POSTapi-frontend-v1-komplain">Store a newly created complaint.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-komplain-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-komplain-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-galeri">
                        <a href="#endpoints-GETapi-frontend-v1-galeri">Display a listing of galeri with advanced filtering and sorting.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-galeri-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-galeri-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-album">
                        <a href="#endpoints-GETapi-frontend-v1-album">Display a listing of album with advanced filtering and sorting.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-album-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-album-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-potensi">
                        <a href="#endpoints-GETapi-frontend-v1-potensi">Display a listing of potensi with advanced filtering and sorting.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-potensi-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-potensi-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-form-dokumen">
                        <a href="#endpoints-GETapi-frontend-v1-form-dokumen">Display a listing of form dokumen with advanced filtering and sorting.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-form-dokumen-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-form-dokumen-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-regulasi">
                        <a href="#endpoints-GETapi-frontend-v1-regulasi">Display a listing of regulasi with advanced filtering and sorting.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-regulasi-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-regulasi-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-prosedur-cache--prefix--">
                        <a href="#endpoints-DELETEapi-frontend-v1-prosedur-cache--prefix--">Remove all cache entries with the specified prefix</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan">GET api/frontend/v1/statistik/chart-tingkat-pendidikan</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-putus-sekolah">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-putus-sekolah">GET api/frontend/v1/statistik/chart-putus-sekolah</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-fasilitas-paud">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-fasilitas-paud">GET api/frontend/v1/statistik/chart-fasilitas-paud</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-akiakb">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-akiakb">GET api/frontend/v1/statistik/chart-akiakb</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-imunisasi">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-imunisasi">GET api/frontend/v1/statistik/chart-imunisasi</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-penyakit">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-penyakit">GET api/frontend/v1/statistik/chart-penyakit</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-sanitasi">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-sanitasi">GET api/frontend/v1/statistik/chart-sanitasi</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-penduduk">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-penduduk">GET api/frontend/v1/statistik/chart-penduduk</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-keluarga">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-keluarga">GET api/frontend/v1/statistik/chart-keluarga</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-anggaran-realisasi">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-anggaran-realisasi">GET api/frontend/v1/statistik/chart-anggaran-realisasi</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-chart-anggaran-desa">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-chart-anggaran-desa">GET api/frontend/v1/statistik/chart-anggaran-desa</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-faq">
                        <a href="#endpoints-GETapi-frontend-v1-faq">Display a listing of FAQ with advanced filtering and sorting.</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-opensid-integration" class="tocify-header">
                <li class="tocify-item level-1" data-unique="opensid-integration">
                    <a href="#opensid-integration">OpenSID Integration</a>
                </li>
                <ul id="tocify-subheader-opensid-integration" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-penduduk">
                        <a href="#opensid-integration-POSTapi-v1-penduduk">Sinkronisasi data penduduk dari OpenSID.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-laporan-apbdes">
                        <a href="#opensid-integration-POSTapi-v1-laporan-apbdes">Sinkronisasi data APBDes dari OpenSID.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-laporan-penduduk">
                        <a href="#opensid-integration-POSTapi-v1-laporan-penduduk">Sinkronisasi laporan penduduk dari OpenSID.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-pesan">
                        <a href="#opensid-integration-POSTapi-v1-pesan">Kirim pesan baru atau balas pesan dari OpenSID.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-pesan-getpesan">
                        <a href="#opensid-integration-POSTapi-v1-pesan-getpesan">Ambil daftar pesan untuk desa tertentu.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-GETapi-v1-pesan-detail">
                        <a href="#opensid-integration-GETapi-v1-pesan-detail">Lihat detail percakapan pesan.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-pembangunan-dokumentasi">
                        <a href="#opensid-integration-POSTapi-v1-pembangunan-dokumentasi">Sinkronisasi dokumentasi pembangunan dari OpenSID.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-identitas-desa">
                        <a href="#opensid-integration-POSTapi-v1-identitas-desa">Sinkronisasi identitas desa dari OpenSID.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-program-bantuan">
                        <a href="#opensid-integration-POSTapi-v1-program-bantuan">Sinkronisasi data program bantuan dari OpenSID.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-program-bantuan-peserta">
                        <a href="#opensid-integration-POSTapi-v1-program-bantuan-peserta">Sinkronisasi data peserta program bantuan dari OpenSID.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-GETapi-v1-surat">
                        <a href="#opensid-integration-GETapi-v1-surat">Daftar surat untuk desa tertentu.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-POSTapi-v1-surat-kirim">
                        <a href="#opensid-integration-POSTapi-v1-surat-kirim">Kirim surat dari OpenSID ke OpenDK (TTE).</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="opensid-integration-GETapi-v1-surat-download">
                        <a href="#opensid-integration-GETapi-v1-surat-download">Download file surat dalam format PDF.</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-prosedur" class="tocify-header">
                <li class="tocify-item level-1" data-unique="prosedur">
                    <a href="#prosedur">Prosedur</a>
                </li>
                <ul id="tocify-subheader-prosedur" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="prosedur-GETapi-frontend-v1-prosedur">
                        <a href="#prosedur-GETapi-frontend-v1-prosedur">Daftar prosedur pelayanan.</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-statistik-penduduk" class="tocify-header">
                <li class="tocify-item level-1" data-unique="statistik-penduduk">
                    <a href="#statistik-penduduk">Statistik Penduduk</a>
                </li>
                <ul id="tocify-subheader-statistik-penduduk" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="statistik-penduduk-GETapi-frontend-v1-statistik-penduduk-listYear">
                        <a href="#statistik-penduduk-GETapi-frontend-v1-statistik-penduduk-listYear">Daftar tahun yang tersedia untuk data statistik penduduk.</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-website" class="tocify-header">
                <li class="tocify-item level-1" data-unique="website">
                    <a href="#website">Website</a>
                </li>
                <ul id="tocify-subheader-website" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="website-GETapi-frontend-v1-website">
                        <a href="#website-GETapi-frontend-v1-website">Data website lengkap (profil, desa, events, medsos, navigasi, slides, dll).</a>
                    </li>
                </ul>
            </ul>
        </div>

        <ul class="toc-footer" id="toc-footer">
            <li style="padding-bottom: 5px;"><a href="{{ route('scribe.postman') }}">View Postman collection</a></li>
            <li style="padding-bottom: 5px;"><a href="{{ route('scribe.openapi') }}">View OpenAPI spec</a></li>
            <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
        </ul>

        <ul class="toc-footer" id="last-updated">
            <li>Last updated: July 20, 2026</li>
        </ul>
    </div>

    <div class="page-wrapper">
        <div class="dark-box"></div>
        <div class="content">
            <h1 id="introduction">Introduction</h1>
            <aside>
                <strong>Base URL</strong>: <code>http://opendk.test/</code>
            </aside>
            <pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

            <h1 id="authenticating-requests">Authenticating requests</h1>
            <p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_AUTH_KEY}"</code></strong>.</p>
            <p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
            <p>Anda dapat memperoleh API key dari halaman pengaturan OpenDK. Key dikirim sebagai Bearer token di header Authorization.</p>

            <h1 id="autentikasi">Autentikasi</h1>

            <p>Endpoint untuk login, logout, refresh token JWT, dan informasi user.</p>

            <h2 id="autentikasi-POSTapi-v1-auth-login">Login</h2>

            <p>
            </p>

            <p>Mendapatkan JWT token dengan credentials email dan password.
                Hanya user dengan permission <code>access.data</code>, <code>access.api</code>, atau <code>access.setting</code> yang diizinkan.</p>

            <span id="example-requests-POSTapi-v1-auth-login">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"admin@mail.com\",
    \"password\": \"password\"
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/auth/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "admin@mail.com",
    "password": "password"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/auth/login';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'email' =&gt; 'admin@mail.com',
            'password' =&gt; 'password',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-auth-login">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;access_token&quot;: &quot;eyJ0eXAiOiJKV1Qi...&quot;,
    &quot;token_type&quot;: &quot;bearer&quot;,
    &quot;expires_in&quot;: 3600
}</code>
 </pre>
                <blockquote>
                    <p>Example response (401):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Pengguna tidak dikenali&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-auth-login" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-login"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-auth-login" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-auth-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-auth-login"
                data-method="POST"
                data-path="api/v1/auth/login"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-login', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-login" onclick="tryItOut('POSTapi-v1-auth-login');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-login" onclick="cancelTryOut('POSTapi-v1-auth-login');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-login" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/auth/login</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-auth-login" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-auth-login" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="email" data-endpoint="POSTapi-v1-auth-login" value="admin@mail.com" data-component="body">
                    <br>
                    <p>Email pengguna. Example: <code>admin@mail.com</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="password" data-endpoint="POSTapi-v1-auth-login" value="password" data-component="body">
                    <br>
                    <p>Password pengguna. Example: <code>password</code></p>
                </div>
            </form>

            <h2 id="autentikasi-POSTapi-v1-auth-logout">Log the user out (Invalidate the token).</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-auth-logout">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/auth/logout"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/auth/logout';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-auth-logout">
            </span>
            <span id="execution-results-POSTapi-v1-auth-logout" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-logout"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-auth-logout" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-auth-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-auth-logout"
                data-method="POST"
                data-path="api/v1/auth/logout"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-logout', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-logout" onclick="tryItOut('POSTapi-v1-auth-logout');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-logout" onclick="cancelTryOut('POSTapi-v1-auth-logout');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-logout" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/auth/logout</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-auth-logout" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-auth-logout" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="autentikasi-POSTapi-v1-auth-refresh">Refresh a token.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-auth-refresh">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/refresh" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/auth/refresh"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/auth/refresh';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-auth-refresh">
            </span>
            <span id="execution-results-POSTapi-v1-auth-refresh" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-refresh"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-refresh"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-auth-refresh" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-auth-refresh">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-auth-refresh"
                data-method="POST"
                data-path="api/v1/auth/refresh"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-refresh', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-refresh" onclick="tryItOut('POSTapi-v1-auth-refresh');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-refresh" onclick="cancelTryOut('POSTapi-v1-auth-refresh');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-refresh" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/auth/refresh</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-auth-refresh" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-auth-refresh" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="autentikasi-GETapi-v1-auth-me">Get the authenticated User.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-auth-me">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/auth/me" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/auth/me"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/auth/me';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-auth-me">
                <blockquote>
                    <p>Example response (401):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-auth-me" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-v1-auth-me"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-auth-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-auth-me" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-auth-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-v1-auth-me"
                data-method="GET"
                data-path="api/v1/auth/me"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-me', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-auth-me" onclick="tryItOut('GETapi-v1-auth-me');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-auth-me" onclick="cancelTryOut('GETapi-v1-auth-me');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-auth-me" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/auth/me</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-v1-auth-me" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-v1-auth-me" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h1 id="endpoints">Endpoints</h1>

            <h2 id="endpoints-GETapi-v1-test">GET api/v1/test</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-test">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/test" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/test"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/test';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-test">
                <blockquote>
                    <p>Example response (401):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 58
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-test" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-v1-test"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-test"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-test" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-test">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-v1-test"
                data-method="GET"
                data-path="api/v1/test"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-test', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-test" onclick="tryItOut('GETapi-v1-test');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-test" onclick="cancelTryOut('GETapi-v1-test');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-test" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/test</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-v1-test" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-v1-test" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-v1-penduduk-storedata">Tambah dan Ubah Data dan Foto Penduduk Sesuai OpenSID</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-penduduk-storedata">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/penduduk/storedata" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "file=@/tmp/phpld56co6up7n539cCDp3" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/penduduk/storedata"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('file', document.querySelector('input[name="file"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/penduduk/storedata';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'file',
                'contents' =&gt; fopen('/tmp/phpld56co6up7n539cCDp3', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-penduduk-storedata">
            </span>
            <span id="execution-results-POSTapi-v1-penduduk-storedata" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-penduduk-storedata"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-penduduk-storedata"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-penduduk-storedata" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-penduduk-storedata">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-penduduk-storedata"
                data-method="POST"
                data-path="api/v1/penduduk/storedata"
                data-authed="0"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-penduduk-storedata', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-penduduk-storedata" onclick="tryItOut('POSTapi-v1-penduduk-storedata');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-penduduk-storedata" onclick="cancelTryOut('POSTapi-v1-penduduk-storedata');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-penduduk-storedata" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/penduduk/storedata</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-penduduk-storedata" value="multipart/form-data" data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-penduduk-storedata" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-penduduk-storedata" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 5120 kilobytes. Example: <code>/tmp/phpld56co6up7n539cCDp3</code></p>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-v1-penduduk-test">Test endpoint untuk verifikasi API berjalan.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-penduduk-test">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/penduduk/test" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/penduduk/test"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/penduduk/test';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-penduduk-test">
            </span>
            <span id="execution-results-POSTapi-v1-penduduk-test" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-penduduk-test"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-penduduk-test"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-penduduk-test" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-penduduk-test">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-penduduk-test"
                data-method="POST"
                data-path="api/v1/penduduk/test"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-penduduk-test', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-penduduk-test" onclick="tryItOut('POSTapi-v1-penduduk-test');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-penduduk-test" onclick="cancelTryOut('POSTapi-v1-penduduk-test');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-penduduk-test" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/penduduk/test</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-penduduk-test" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-penduduk-test" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-v1-pembangunan">Tambah Data Pembangunan Sesuai OpenSID</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-pembangunan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/pembangunan" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=architecto"\
    --form "file=@/tmp/phpnvagq1g29o92cIWweLC" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/pembangunan"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('desa_id', 'architecto');
body.append('file', document.querySelector('input[name="file"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/pembangunan';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'desa_id',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'file',
                'contents' =&gt; fopen('/tmp/phpnvagq1g29o92cIWweLC', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-pembangunan">
            </span>
            <span id="execution-results-POSTapi-v1-pembangunan" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-pembangunan"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-pembangunan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-pembangunan" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-pembangunan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-pembangunan"
                data-method="POST"
                data-path="api/v1/pembangunan"
                data-authed="0"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-pembangunan', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-pembangunan" onclick="tryItOut('POSTapi-v1-pembangunan');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-pembangunan" onclick="cancelTryOut('POSTapi-v1-pembangunan');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-pembangunan" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/pembangunan</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-pembangunan" value="multipart/form-data" data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-pembangunan" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-pembangunan" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 5120 kilobytes. Example: <code>/tmp/phpnvagq1g29o92cIWweLC</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-pembangunan" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-artikel">Display a listing of articles with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-artikel">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/artikel" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/artikel"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/artikel';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-artikel">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;consequatur-autem-nemo-doloribus-exercitationem&quot;,
                &quot;judul&quot;: &quot;Consequatur autem nemo doloribus exercitationem.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Et repudiandae maiores dolorem officia sed et. Sit odit fugiat non fugiat. Nam non est eum non incidunt ut impedit. Consequatur inventore qui autem voluptatibus doloribus.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-02-10T04:20:30.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-03-10T22:09:21.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-02-10&quot;,
                &quot;tanggal&quot;: &quot;10 Februari 2025&quot;,
                &quot;link&quot;: &quot;/berita/consequatur-autem-nemo-doloribus-exercitationem&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;2&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;sapiente-amet-laboriosam-sapiente-repudiandae-assumenda-sed&quot;,
                &quot;judul&quot;: &quot;Sapiente amet laboriosam sapiente repudiandae assumenda sed.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Sed aut tempore et corrupti voluptas in molestiae. At nihil dolor explicabo dolor iste dolorem. Delectus ut ipsum ut consequuntur sequi veniam excepturi.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-01-06T19:56:16.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-03T17:36:53.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-01-07&quot;,
                &quot;tanggal&quot;: &quot;07 Januari 2025&quot;,
                &quot;link&quot;: &quot;/berita/sapiente-amet-laboriosam-sapiente-repudiandae-assumenda-sed&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;3&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;sint-nostrum-aut-enim-voluptate-totam-aut-dolorem&quot;,
                &quot;judul&quot;: &quot;Sint nostrum aut enim voluptate totam aut dolorem.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Harum consequuntur rerum aspernatur et voluptatibus nemo aliquam nihil. Possimus qui officia voluptatibus. Necessitatibus et recusandae ab necessitatibus occaecati. Dolores sed et nulla sunt omnis ex adipisci. Quisquam nesciunt ut sed esse fugiat et.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-03-21T05:04:28.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-01-06T09:55:59.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-03-21&quot;,
                &quot;tanggal&quot;: &quot;21 Maret 2025&quot;,
                &quot;link&quot;: &quot;/berita/sint-nostrum-aut-enim-voluptate-totam-aut-dolorem&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;4&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;doloribus-unde-ut-voluptatem-doloribus&quot;,
                &quot;judul&quot;: &quot;Doloribus unde ut voluptatem doloribus.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Itaque eaque beatae ut error nisi fuga iste. Sunt nesciunt qui id aut reprehenderit aut suscipit. Aut omnis id recusandae iure reprehenderit dolorem illo ut.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-01-30T16:36:43.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-02-22T18:14:21.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-01-30&quot;,
                &quot;tanggal&quot;: &quot;30 Januari 2025&quot;,
                &quot;link&quot;: &quot;/berita/doloribus-unde-ut-voluptatem-doloribus&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;5&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;itaque-hic-iste-qui-similique-non&quot;,
                &quot;judul&quot;: &quot;Itaque hic iste qui similique non.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Quia inventore molestiae eum non. Distinctio est blanditiis reprehenderit veritatis dolore rerum beatae. Molestiae commodi eos veritatis blanditiis similique. Qui voluptatem et natus veniam qui praesentium.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-03-27T12:34:49.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-02T20:10:13.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-03-27&quot;,
                &quot;tanggal&quot;: &quot;27 Maret 2025&quot;,
                &quot;link&quot;: &quot;/berita/itaque-hic-iste-qui-similique-non&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;6&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;nulla-quas-omnis-non-quis-eum-quaerat&quot;,
                &quot;judul&quot;: &quot;Nulla quas omnis non quis eum quaerat.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel/uXCspYfmcfwn16mgSksfV1hFoCQBNz4qeecbbDh2.png&quot;,
                &quot;isi&quot;: &quot;&lt;p&gt;Non fuga facilis quia et praesentium. Minus voluptatem adipisci aspernatur mollitia. In et magnam deserunt voluptates eveniet.&lt;/p&gt;&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-04-08T17:33:56.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-07-09T01:34:32.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-04-09&quot;,
                &quot;tanggal&quot;: &quot;09 April 2025&quot;,
                &quot;link&quot;: &quot;/berita/nulla-quas-omnis-non-quis-eum-quaerat&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/storage/artikel/uXCspYfmcfwn16mgSksfV1hFoCQBNz4qeecbbDh2.png&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;7&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;quia-nemo-officiis-iure-voluptatem-iusto-neque&quot;,
                &quot;judul&quot;: &quot;Quia nemo officiis iure voluptatem iusto neque.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Molestiae nihil nihil inventore amet cum voluptas dolores et. Delectus dolores aut omnis. Earum ratione incidunt et aut molestiae.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-02-12T08:39:21.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-01-16T10:01:14.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-02-12&quot;,
                &quot;tanggal&quot;: &quot;12 Februari 2025&quot;,
                &quot;link&quot;: &quot;/berita/quia-nemo-officiis-iure-voluptatem-iusto-neque&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;8&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;veritatis-ipsa-doloremque-deleniti-quisquam-libero&quot;,
                &quot;judul&quot;: &quot;Veritatis ipsa doloremque deleniti quisquam libero.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Dolorem vitae placeat eos et perferendis aut minima beatae. Libero non ut in harum. Perferendis aut autem illum nesciunt asperiores voluptatem.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-03-04T10:26:11.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-01-20T20:53:41.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-03-04&quot;,
                &quot;tanggal&quot;: &quot;04 Maret 2025&quot;,
                &quot;link&quot;: &quot;/berita/veritatis-ipsa-doloremque-deleniti-quisquam-libero&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;9&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;eveniet-nemo-praesentium-et-dolores-dolor-nemo&quot;,
                &quot;judul&quot;: &quot;Eveniet nemo praesentium et dolores dolor nemo.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Modi ut voluptate eaque. Pariatur sed et vitae ex velit asperiores neque. Illo ut cum ipsa maiores aut. Quisquam voluptatem eum eligendi omnis distinctio voluptatem sed ut.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-01-05T14:19:31.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-01-27T08:47:27.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-01-05&quot;,
                &quot;tanggal&quot;: &quot;05 Januari 2025&quot;,
                &quot;link&quot;: &quot;/berita/eveniet-nemo-praesentium-et-dolores-dolor-nemo&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;10&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;perspiciatis-nostrum-nihil-vitae-mollitia-ea&quot;,
                &quot;judul&quot;: &quot;Perspiciatis nostrum nihil vitae mollitia ea.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Animi magnam asperiores est ut. Repellat quos assumenda mollitia quod voluptatem molestias neque perferendis. Doloremque autem dolorem ducimus omnis.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-04-03T07:25:58.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-08T06:00:44.000000Z&quot;,
                &quot;tanggal_terbit&quot;: &quot;2025-04-03&quot;,
                &quot;tanggal&quot;: &quot;03 April 2025&quot;,
                &quot;link&quot;: &quot;/berita/perspiciatis-nostrum-nihil-vitae-mollitia-ea&quot;,
                &quot;gambar_src&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 20,
            &quot;count&quot;: 10,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 2
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/artikel?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/artikel?page%5Bnumber%5D=1&quot;,
        &quot;next&quot;: &quot;http://localhost:8000/api/frontend/v1/artikel?page%5Bnumber%5D=2&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/artikel?page%5Bnumber%5D=2&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-artikel" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-artikel"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-artikel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-artikel" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-artikel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-artikel"
                data-method="GET"
                data-path="api/frontend/v1/artikel"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-artikel', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-artikel" onclick="tryItOut('GETapi-frontend-v1-artikel');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-artikel" onclick="cancelTryOut('GETapi-frontend-v1-artikel');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-artikel" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/artikel</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-artikel" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-artikel" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-frontend-v1-artikel--id--comments">Store a new comment for an article.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-frontend-v1-artikel--id--comments">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/frontend/v1/artikel/1/comments" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"nama\": \"b\",
    \"email\": \"zbailey@example.net\",
    \"body\": \"architecto\",
    \"comment_id\": 16
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/artikel/1/comments"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "nama": "b",
    "email": "zbailey@example.net",
    "body": "architecto",
    "comment_id": 16
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/artikel/1/comments';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'nama' =&gt; 'b',
            'email' =&gt; 'zbailey@example.net',
            'body' =&gt; 'architecto',
            'comment_id' =&gt; 16,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-frontend-v1-artikel--id--comments">
            </span>
            <span id="execution-results-POSTapi-frontend-v1-artikel--id--comments" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-frontend-v1-artikel--id--comments"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-frontend-v1-artikel--id--comments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-frontend-v1-artikel--id--comments" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-frontend-v1-artikel--id--comments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-frontend-v1-artikel--id--comments"
                data-method="POST"
                data-path="api/frontend/v1/artikel/{id}/comments"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-frontend-v1-artikel--id--comments', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-frontend-v1-artikel--id--comments" onclick="tryItOut('POSTapi-frontend-v1-artikel--id--comments');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-frontend-v1-artikel--id--comments" onclick="cancelTryOut('POSTapi-frontend-v1-artikel--id--comments');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-frontend-v1-artikel--id--comments" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/frontend/v1/artikel/{id}/comments</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-frontend-v1-artikel--id--comments" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-frontend-v1-artikel--id--comments" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="id"
                        data-endpoint="POSTapi-frontend-v1-artikel--id--comments"
                        value="1"
                        data-component="url"
                    >
                    <br>
                    <p>The ID of the artikel. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nama</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nama" data-endpoint="POSTapi-frontend-v1-artikel--id--comments" value="b" data-component="body">
                    <br>
                    <p>Isian value seharusnya tidak lebih dari 191 karakter. Example: <code>b</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="email" data-endpoint="POSTapi-frontend-v1-artikel--id--comments" value="zbailey@example.net" data-component="body">
                    <br>
                    <p>Isian value harus berupa alamat surel yang valid. Isian value seharusnya tidak lebih dari 191 karakter. Example: <code>zbailey@example.net</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>body</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="body" data-endpoint="POSTapi-frontend-v1-artikel--id--comments" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>comment_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="comment_id"
                        data-endpoint="POSTapi-frontend-v1-artikel--id--comments"
                        value="16"
                        data-component="body"
                    >
                    <br>
                    <p>Must match an existing stored value. Example: <code>16</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-artikel-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-artikel-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/artikel/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/artikel/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/artikel/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-artikel-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-artikel-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-artikel-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-artikel-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-artikel-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-artikel-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-artikel-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/artikel/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-artikel-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-artikel-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-artikel-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-artikel-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-artikel-cache--prefix--');" hidden>Cancel
                        🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-artikel-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/artikel/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-artikel-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-artikel-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-artikel-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-kategori">Display a listing of articles with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-kategori">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/kategori" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/kategori"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/kategori';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-kategori">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 118
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;kategori&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: 1,
                &quot;nama_kategori&quot;: &quot;Pengumuman&quot;,
                &quot;slug&quot;: &quot;pengumuman&quot;,
                &quot;status&quot;: &quot;Ya&quot;,
                &quot;created_at&quot;: &quot;2026-07-10T00:21:21.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-07-10T00:21:21.000000Z&quot;,
                &quot;type&quot;: &quot;kategori&quot;,
                &quot;link&quot;: &quot;http://localhost:8000/kategori/pengumuman&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 1,
            &quot;count&quot;: 1,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/kategori?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/kategori?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/kategori?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-kategori" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-kategori"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-kategori"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-kategori" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-kategori">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-kategori"
                data-method="GET"
                data-path="api/frontend/v1/kategori"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-kategori', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-kategori" onclick="tryItOut('GETapi-frontend-v1-kategori');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-kategori" onclick="cancelTryOut('GETapi-frontend-v1-kategori');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-kategori" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/kategori</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-kategori" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-kategori" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-kategori-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-kategori-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/kategori/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/kategori/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/kategori/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-kategori-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-kategori-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-kategori-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-kategori-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-kategori-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-kategori-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-kategori-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/kategori/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-kategori-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-kategori-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-kategori-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-kategori-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-kategori-cache--prefix--');"
                        hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-kategori-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/kategori/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-kategori-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-kategori-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-kategori-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-website-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-website-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/website/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/website/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/website/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-website-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-website-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-website-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-website-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-website-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-website-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-website-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/website/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-website-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-website-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-website-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-website-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-website-cache--prefix--');" hidden>Cancel
                        🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-website-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/website/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-website-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-website-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-website-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-profil">Display a listing of profiles with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-profil">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/profil" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/profil"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/profil';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-profil">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 117
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;23&quot;,
            &quot;attributes&quot;: {
                &quot;provinsi_id&quot;: &quot;51&quot;,
                &quot;nama_provinsi&quot;: &quot;Test Provinsi&quot;,
                &quot;kabupaten_id&quot;: &quot;51.02&quot;,
                &quot;nama_kabupaten&quot;: &quot;Test Kabupaten&quot;,
                &quot;kecamatan_id&quot;: &quot;51.02.06&quot;,
                &quot;nama_kecamatan&quot;: &quot;Test Kecamatan&quot;,
                &quot;alamat&quot;: &quot;fdsfasfas&quot;,
                &quot;kode_pos&quot;: &quot;76677&quot;,
                &quot;telepon&quot;: &quot;085733659400&quot;,
                &quot;email&quot;: &quot;guru_piket@example.com&quot;,
                &quot;tahun_pembentukan&quot;: 2000,
                &quot;dasar_pembentukan&quot;: &quot;SK/9000&quot;,
                &quot;nama_camat&quot;: null,
                &quot;sekretaris_camat&quot;: null,
                &quot;kepsek_pemerintahan_umum&quot;: null,
                &quot;kepsek_kesejahteraan_masyarakat&quot;: null,
                &quot;kepsek_pemberdayaan_masyarakat&quot;: null,
                &quot;kepsek_pelayanan_umum&quot;: null,
                &quot;kepsek_trantib&quot;: null,
                &quot;file_struktur_organisasi&quot;: &quot;storage/profil/struktur_organisasi/1783580654_6OBKP123W0tTktD3.png&quot;,
                &quot;file_logo&quot;: null,
                &quot;visi&quot;: null,
                &quot;misi&quot;: null,
                &quot;foto_kepala_wilayah&quot;: null,
                &quot;created_at&quot;: &quot;2026-05-19T23:17:56.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-07-09T07:04:14.000000Z&quot;,
                &quot;sambutan&quot;: null,
                &quot;file_struktur_organisasi_path&quot;: &quot;http://localhost:8000/storage/profil/struktur_organisasi/1783580654_6OBKP123W0tTktD3.png&quot;,
                &quot;foto_kepala_wilayah_path&quot;: &quot;http://localhost:8000/img/no-profile.png&quot;,
                &quot;file_logo_path&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 1,
            &quot;count&quot;: 1,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/profil?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/profil?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/profil?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-profil" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-profil"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-profil"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-profil" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-profil">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-profil"
                data-method="GET"
                data-path="api/frontend/v1/profil"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-profil', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-profil" onclick="tryItOut('GETapi-frontend-v1-profil');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-profil" onclick="cancelTryOut('GETapi-frontend-v1-profil');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-profil" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/profil</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-profil" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-profil" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-profil-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-profil-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/profil/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/profil/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/profil/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-profil-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-profil-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-profil-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-profil-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-profil-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-profil-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-profil-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/profil/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-profil-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-profil-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-profil-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-profil-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-profil-cache--prefix--');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-profil-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/profil/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-profil-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-profil-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-profil-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-desa">Display a listing of desa with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-desa">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/desa" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/desa"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/desa';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-desa">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 116
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;39&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;11.01.01.2015&quot;,
                &quot;kode_desa&quot;: &quot;11.01.01.2015&quot;,
                &quot;nama&quot;: &quot;Darul Ikhsan&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Darul Ikhsan&quot;,
                &quot;website&quot;: null,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;11.01.01.2015&quot;,
                    &quot;nama&quot;: &quot;Desa Darul Ikhsan&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;40&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;11.01.01.2017&quot;,
                &quot;kode_desa&quot;: &quot;11.01.01.2017&quot;,
                &quot;nama&quot;: &quot;Gampong Baro&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Gampong Baro&quot;,
                &quot;website&quot;: null,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;11.01.01.2017&quot;,
                    &quot;nama&quot;: &quot;Desa Gampong Baro&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;41&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;11.01.01.2004&quot;,
                &quot;kode_desa&quot;: &quot;11.01.01.2004&quot;,
                &quot;nama&quot;: &quot;Gampong Drien&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Gampong Drien&quot;,
                &quot;website&quot;: null,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;11.01.01.2004&quot;,
                    &quot;nama&quot;: &quot;Desa Gampong Drien&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;42&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;11.01.01.2001&quot;,
                &quot;kode_desa&quot;: &quot;11.01.01.2001&quot;,
                &quot;nama&quot;: &quot;Keude Bakongan&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Keude Bakongan&quot;,
                &quot;website&quot;: &quot;https://berembeng.desa.id/&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;11.01.01.2001&quot;,
                    &quot;nama&quot;: &quot;Desa Keude Bakongan&quot;,
                    &quot;website&quot;: &quot;https://berembeng.desa.id/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;43&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;11.01.01.2016&quot;,
                &quot;kode_desa&quot;: &quot;11.01.01.2016&quot;,
                &quot;nama&quot;: &quot;Padang Beurahan&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Padang Beurahan&quot;,
                &quot;website&quot;: null,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;11.01.01.2016&quot;,
                    &quot;nama&quot;: &quot;Desa Padang Beurahan&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;44&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;11.01.01.2002&quot;,
                &quot;kode_desa&quot;: &quot;11.01.01.2002&quot;,
                &quot;nama&quot;: &quot;Ujong Mangki&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Ujong Mangki&quot;,
                &quot;website&quot;: null,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;11.01.01.2002&quot;,
                    &quot;nama&quot;: &quot;Desa Ujong Mangki&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;45&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;11.01.01.2003&quot;,
                &quot;kode_desa&quot;: &quot;11.01.01.2003&quot;,
                &quot;nama&quot;: &quot;Ujong Padang&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Ujong Padang&quot;,
                &quot;website&quot;: null,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;11.01.01.2003&quot;,
                    &quot;nama&quot;: &quot;Desa Ujong Padang&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-09T02:53:17.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;46&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;3301011234567&quot;,
                &quot;kode_desa&quot;: &quot;3301011234567&quot;,
                &quot;nama&quot;: &quot;Desa Contoh&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Desa Contoh&quot;,
                &quot;website&quot;: &quot;https://example.com&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;3301011234567&quot;,
                    &quot;nama&quot;: &quot;Desa Desa Contoh&quot;,
                    &quot;website&quot;: &quot;https://example.com/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 10.5,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2026-07-19T23:43:35.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-07-19T23:43:35.000000Z&quot;
            }
        }
    ]
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-desa" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-desa"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-desa"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-desa" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-desa">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-desa"
                data-method="GET"
                data-path="api/frontend/v1/desa"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-desa', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-desa" onclick="tryItOut('GETapi-frontend-v1-desa');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-desa" onclick="cancelTryOut('GETapi-frontend-v1-desa');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-desa" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/desa</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-desa" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-desa" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-desa-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-desa-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/desa/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/desa/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/desa/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-desa-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-desa-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-desa-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-desa-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-desa-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-desa-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-desa-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/desa/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-desa-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-desa-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-desa-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-desa-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-desa-cache--prefix--');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-desa-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/desa/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-desa-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-desa-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-desa-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-penduduk">Display statistik penduduk with dashboard and chart data.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-penduduk">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik-penduduk" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik-penduduk"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik-penduduk';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-penduduk">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 115
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;statistik-penduduk&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;dashboard&quot;: {
                    &quot;total_penduduk&quot;: 96,
                    &quot;total_lakilaki&quot;: 45,
                    &quot;total_perempuan&quot;: 51,
                    &quot;total_disabilitas&quot;: 0,
                    &quot;ktp_wajib&quot;: 87,
                    &quot;ktp_terpenuhi&quot;: 0,
                    &quot;ktp_persen_terpenuhi&quot;: &quot;0,00&quot;,
                    &quot;akta_terpenuhi&quot;: 0,
                    &quot;akta_persen_terpenuhi&quot;: &quot;0,00&quot;,
                    &quot;aktanikah_wajib&quot;: 50,
                    &quot;aktanikah_terpenuhi&quot;: 0,
                    &quot;aktanikah_persen_terpenuhi&quot;: &quot;0,00&quot;
                },
                &quot;chart&quot;: {
                    &quot;penduduk&quot;: [
                        {
                            &quot;year&quot;: &quot;2019&quot;,
                            &quot;value_lk&quot;: 45,
                            &quot;value_pr&quot;: 51
                        },
                        {
                            &quot;year&quot;: 2020,
                            &quot;value_lk&quot;: 45,
                            &quot;value_pr&quot;: 51
                        },
                        {
                            &quot;year&quot;: 2021,
                            &quot;value_lk&quot;: 45,
                            &quot;value_pr&quot;: 51
                        },
                        {
                            &quot;year&quot;: 2022,
                            &quot;value_lk&quot;: 45,
                            &quot;value_pr&quot;: 51
                        },
                        {
                            &quot;year&quot;: 2023,
                            &quot;value_lk&quot;: 45,
                            &quot;value_pr&quot;: 51
                        },
                        {
                            &quot;year&quot;: 2024,
                            &quot;value_lk&quot;: 45,
                            &quot;value_pr&quot;: 51
                        },
                        {
                            &quot;year&quot;: 2025,
                            &quot;value_lk&quot;: 45,
                            &quot;value_pr&quot;: 51
                        },
                        {
                            &quot;year&quot;: 2026,
                            &quot;value_lk&quot;: 45,
                            &quot;value_pr&quot;: 51
                        }
                    ],
                    &quot;penduduk-usia&quot;: [
                        {
                            &quot;umur&quot;: &quot;Bayi (0 - 5 tahun)&quot;,
                            &quot;value&quot;: 0,
                            &quot;color&quot;: &quot;#09ffdc&quot;
                        },
                        {
                            &quot;umur&quot;: &quot;Anak-anak (6 - 14 tahun)&quot;,
                            &quot;value&quot;: 7,
                            &quot;color&quot;: &quot;#09faff&quot;
                        },
                        {
                            &quot;umur&quot;: &quot;Remaja (15 - 24 tahun)&quot;,
                            &quot;value&quot;: 13,
                            &quot;color&quot;: &quot;#09e5ff&quot;
                        },
                        {
                            &quot;umur&quot;: &quot;Dewasa (25 - 44 tahun)&quot;,
                            &quot;value&quot;: 48,
                            &quot;color&quot;: &quot;#09d1ff&quot;
                        },
                        {
                            &quot;umur&quot;: &quot;Tua (45 - 74 tahun)&quot;,
                            &quot;value&quot;: 25,
                            &quot;color&quot;: &quot;#09bcff&quot;
                        },
                        {
                            &quot;umur&quot;: &quot;Lansia (75 - 130 tahun)&quot;,
                            &quot;value&quot;: 3,
                            &quot;color&quot;: &quot;#09a8ff&quot;
                        }
                    ],
                    &quot;penduduk-pendidikan&quot;: [
                        {
                            &quot;year&quot;: &quot;2026&quot;,
                            &quot;SD&quot;: 15,
                            &quot;SLTP&quot;: 26,
                            &quot;SLTA&quot;: 28,
                            &quot;DIPLOMA&quot;: 1,
                            &quot;SARJANA&quot;: 0
                        }
                    ],
                    &quot;penduduk-golongan-darah&quot;: [
                        {
                            &quot;blod_type&quot;: &quot;A&quot;,
                            &quot;total&quot;: 2,
                            &quot;color&quot;: &quot;#f97d7d&quot;
                        },
                        {
                            &quot;blod_type&quot;: &quot;B&quot;,
                            &quot;total&quot;: 2,
                            &quot;color&quot;: &quot;#f86565&quot;
                        },
                        {
                            &quot;blod_type&quot;: &quot;AB&quot;,
                            &quot;total&quot;: 0,
                            &quot;color&quot;: &quot;#f74d4d&quot;
                        },
                        {
                            &quot;blod_type&quot;: &quot;O&quot;,
                            &quot;total&quot;: 0,
                            &quot;color&quot;: &quot;#f63434&quot;
                        },
                        {
                            &quot;blod_type&quot;: &quot;TIDAK TAHU&quot;,
                            &quot;total&quot;: 92,
                            &quot;color&quot;: &quot;#f51c1c&quot;
                        }
                    ],
                    &quot;penduduk-kawin&quot;: [
                        {
                            &quot;status&quot;: &quot;Belum kawin&quot;,
                            &quot;total&quot;: 40,
                            &quot;color&quot;: &quot;#d365f8&quot;
                        },
                        {
                            &quot;status&quot;: &quot;Kawin&quot;,
                            &quot;total&quot;: 50,
                            &quot;color&quot;: &quot;#c534f6&quot;
                        },
                        {
                            &quot;status&quot;: &quot;Cerai hidup&quot;,
                            &quot;total&quot;: 2,
                            &quot;color&quot;: &quot;#b40aed&quot;
                        },
                        {
                            &quot;status&quot;: &quot;Cerai mati&quot;,
                            &quot;total&quot;: 4,
                            &quot;color&quot;: &quot;#8f08bc&quot;
                        }
                    ],
                    &quot;penduduk-agama&quot;: [
                        {
                            &quot;religion&quot;: &quot;Islam&quot;,
                            &quot;total&quot;: 89,
                            &quot;color&quot;: &quot;#dcaf1e&quot;
                        },
                        {
                            &quot;religion&quot;: &quot;Kristen&quot;,
                            &quot;total&quot;: 0,
                            &quot;color&quot;: &quot;#dc9f1e&quot;
                        },
                        {
                            &quot;religion&quot;: &quot;Katholik&quot;,
                            &quot;total&quot;: 0,
                            &quot;color&quot;: &quot;#dc8f1e&quot;
                        },
                        {
                            &quot;religion&quot;: &quot;Hindu&quot;,
                            &quot;total&quot;: 7,
                            &quot;color&quot;: &quot;#dc7f1e&quot;
                        },
                        {
                            &quot;religion&quot;: &quot;Budha&quot;,
                            &quot;total&quot;: 0,
                            &quot;color&quot;: &quot;#dc6f1e&quot;
                        },
                        {
                            &quot;religion&quot;: &quot;Khonghucu&quot;,
                            &quot;total&quot;: 0,
                            &quot;color&quot;: &quot;#dc5f1e&quot;
                        },
                        {
                            &quot;religion&quot;: &quot;Lainnya&quot;,
                            &quot;total&quot;: 0,
                            &quot;color&quot;: &quot;#dc4f1e&quot;
                        }
                    ]
                }
            }
        }
    ]
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-penduduk" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-penduduk"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-penduduk"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-penduduk" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-penduduk">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-penduduk"
                data-method="GET"
                data-path="api/frontend/v1/statistik-penduduk"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-penduduk', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-penduduk" onclick="tryItOut('GETapi-frontend-v1-statistik-penduduk');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-penduduk" onclick="cancelTryOut('GETapi-frontend-v1-statistik-penduduk');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-penduduk" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik-penduduk</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-penduduk" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-penduduk" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/statistik-penduduk/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik-penduduk/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik-penduduk/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/statistik-penduduk/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
                        onclick="tryItOut('DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
                        onclick="cancelTryOut('DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--');" hidden
                    >Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..."
                        hidden
                    >Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/statistik-penduduk/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-komplain">Display a listing of complaints.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-komplain">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/komplain" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/komplain"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/komplain';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-komplain">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 114
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;komplain&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;komplain_id&quot;: 679399,
                &quot;judul&quot;: &quot;komplain&quot;,
                &quot;slug&quot;: &quot;komplain-679399&quot;,
                &quot;laporan&quot;: &quot;komlain fsadfdsa&quot;,
                &quot;status&quot;: &quot;PROSES&quot;,
                &quot;kategori&quot;: &quot;1&quot;,
                &quot;nik&quot;: &quot;5201142003136994&quot;,
                &quot;nama&quot;: &quot;WAYAN EKA PRAWATA&quot;,
                &quot;anonim&quot;: 1,
                &quot;dilihat&quot;: 0,
                &quot;lampiran1&quot;: null,
                &quot;lampiran2&quot;: null,
                &quot;lampiran3&quot;: null,
                &quot;lampiran4&quot;: null,
                &quot;detail_penduduk&quot;: &quot;{\&quot;id\&quot;:2,\&quot;nama\&quot;:\&quot;WAYAN EKA PRAWATA\&quot;,\&quot;nik\&quot;:\&quot;5201142003136994\&quot;,\&quot;id_kk\&quot;:null,\&quot;kk_level\&quot;:1,\&quot;id_rtm\&quot;:null,\&quot;rtm_level\&quot;:null,\&quot;sex\&quot;:1,\&quot;tempat_lahir\&quot;:\&quot;GUNUNG SARI\&quot;,\&quot;tanggal_lahir\&quot;:\&quot;2012-03-20\&quot;,\&quot;agama_id\&quot;:1,\&quot;pendidikan_kk_id\&quot;:1,\&quot;pendidikan_id\&quot;:null,\&quot;pendidikan_sedang_id\&quot;:18,\&quot;pekerjaan_id\&quot;:1,\&quot;status_kawin\&quot;:2,\&quot;warga_negara_id\&quot;:1,\&quot;dokumen_pasport\&quot;:null,\&quot;dokumen_kitas\&quot;:null,\&quot;ayah_nik\&quot;:null,\&quot;ibu_nik\&quot;:null,\&quot;nama_ayah\&quot;:\&quot;WAHID ALIAS H. MAHSUN\&quot;,\&quot;nama_ibu\&quot;:\&quot;ULFA WIDIAWATI\&quot;,\&quot;foto\&quot;:\&quot;5201142003136994-96.jpg\&quot;,\&quot;golongan_darah_id\&quot;:13,\&quot;id_cluster\&quot;:null,\&quot;status\&quot;:null,\&quot;alamat_sebelumnya\&quot;:null,\&quot;alamat_sekarang\&quot;:null,\&quot;status_dasar\&quot;:1,\&quot;hamil\&quot;:null,\&quot;cacat_id\&quot;:null,\&quot;sakit_menahun_id\&quot;:null,\&quot;akta_lahir\&quot;:null,\&quot;akta_perkawinan\&quot;:null,\&quot;tanggal_perkawinan\&quot;:null,\&quot;akta_perceraian\&quot;:null,\&quot;tanggal_perceraian\&quot;:null,\&quot;cara_kb_id\&quot;:null,\&quot;telepon\&quot;:null,\&quot;tanggal_akhir_pasport\&quot;:null,\&quot;no_kk\&quot;:\&quot;0\&quot;,\&quot;no_kk_sebelumnya\&quot;:null,\&quot;ktp_el\&quot;:null,\&quot;status_rekam\&quot;:null,\&quot;alamat\&quot;:null,\&quot;dusun\&quot;:\&quot;SENGGIGI\&quot;,\&quot;rw\&quot;:\&quot;-\&quot;,\&quot;rt\&quot;:\&quot;002\&quot;,\&quot;desa_id\&quot;:\&quot;53.06.13.2001\&quot;,\&quot;tahun\&quot;:null,\&quot;created_at\&quot;:\&quot;2019-05-28T15:45:28.000000Z\&quot;,\&quot;updated_at\&quot;:\&quot;2020-12-22T10:12:01.000000Z\&quot;,\&quot;id_pend_desa\&quot;:null,\&quot;imported_at\&quot;:\&quot;2025-04-23 08:02:14\&quot;}&quot;,
                &quot;created_at&quot;: &quot;2026-06-09T04:23:13.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-09T04:23:32.000000Z&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 1,
            &quot;count&quot;: 1,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/komplain?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/komplain?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/komplain?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-komplain" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-komplain"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-komplain"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-komplain" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-komplain">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-komplain"
                data-method="GET"
                data-path="api/frontend/v1/komplain"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-komplain', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-komplain" onclick="tryItOut('GETapi-frontend-v1-komplain');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-komplain" onclick="cancelTryOut('GETapi-frontend-v1-komplain');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-komplain" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/komplain</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-komplain" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-komplain" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-frontend-v1-komplain">Store a newly created complaint.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-frontend-v1-komplain">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/frontend/v1/komplain" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "nik=4326.41688"\
    --form "judul=m"\
    --form "kategori=architecto"\
    --form "laporan=architecto"\
    --form "tanggal_lahir=2026-07-20T08:09:29"\
    --form "anonim=1"\
    --form "lampiran1=@/tmp/phpibides5c6o72aSm64xJ" \
    --form "lampiran2=@/tmp/phpv79v82046enb7O8ye0p" \
    --form "lampiran3=@/tmp/phpga9k0mi9gohgdtATC5l" \
    --form "lampiran4=@/tmp/phpgr4blhpsgmpmeEI5LeX" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/komplain"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('nik', '4326.41688');
body.append('judul', 'm');
body.append('kategori', 'architecto');
body.append('laporan', 'architecto');
body.append('tanggal_lahir', '2026-07-20T08:09:29');
body.append('anonim', '1');
body.append('lampiran1', document.querySelector('input[name="lampiran1"]').files[0]);
body.append('lampiran2', document.querySelector('input[name="lampiran2"]').files[0]);
body.append('lampiran3', document.querySelector('input[name="lampiran3"]').files[0]);
body.append('lampiran4', document.querySelector('input[name="lampiran4"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/komplain';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'nik',
                'contents' =&gt; '4326.41688'
            ],
            [
                'name' =&gt; 'judul',
                'contents' =&gt; 'm'
            ],
            [
                'name' =&gt; 'kategori',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'laporan',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'tanggal_lahir',
                'contents' =&gt; '2026-07-20T08:09:29'
            ],
            [
                'name' =&gt; 'anonim',
                'contents' =&gt; '1'
            ],
            [
                'name' =&gt; 'lampiran1',
                'contents' =&gt; fopen('/tmp/phpibides5c6o72aSm64xJ', 'r')
            ],
            [
                'name' =&gt; 'lampiran2',
                'contents' =&gt; fopen('/tmp/phpv79v82046enb7O8ye0p', 'r')
            ],
            [
                'name' =&gt; 'lampiran3',
                'contents' =&gt; fopen('/tmp/phpga9k0mi9gohgdtATC5l', 'r')
            ],
            [
                'name' =&gt; 'lampiran4',
                'contents' =&gt; fopen('/tmp/phpgr4blhpsgmpmeEI5LeX', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-frontend-v1-komplain">
            </span>
            <span id="execution-results-POSTapi-frontend-v1-komplain" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-frontend-v1-komplain"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-frontend-v1-komplain"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-frontend-v1-komplain" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-frontend-v1-komplain">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-frontend-v1-komplain"
                data-method="POST"
                data-path="api/frontend/v1/komplain"
                data-authed="0"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-frontend-v1-komplain', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-frontend-v1-komplain" onclick="tryItOut('POSTapi-frontend-v1-komplain');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-frontend-v1-komplain" onclick="cancelTryOut('POSTapi-frontend-v1-komplain');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-frontend-v1-komplain" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/frontend/v1/komplain</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-frontend-v1-komplain" value="multipart/form-data" data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-frontend-v1-komplain" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="nik"
                        data-endpoint="POSTapi-frontend-v1-komplain"
                        value="4326.41688"
                        data-component="body"
                    >
                    <br>
                    <p>Example: <code>4326.41688</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="judul" data-endpoint="POSTapi-frontend-v1-komplain" value="m" data-component="body">
                    <br>
                    <p>Isian value seharusnya tidak lebih dari 255 karakter. Example: <code>m</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="kategori" data-endpoint="POSTapi-frontend-v1-komplain" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>laporan</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="laporan" data-endpoint="POSTapi-frontend-v1-komplain" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>tanggal_lahir</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="tanggal_lahir" data-endpoint="POSTapi-frontend-v1-komplain" value="2026-07-20T08:09:29" data-component="body">
                    <br>
                    <p>Isian value bukan tanggal yang valid. Example: <code>2026-07-20T08:09:29</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>anonim</code></b>&nbsp;&nbsp;
                    <small>boolean</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <label data-endpoint="POSTapi-frontend-v1-komplain" style="display: none">
                        <input type="radio" name="anonim" value="true" data-endpoint="POSTapi-frontend-v1-komplain" data-component="body">
                        <code>true</code>
                    </label>
                    <label data-endpoint="POSTapi-frontend-v1-komplain" style="display: none">
                        <input type="radio" name="anonim" value="false" data-endpoint="POSTapi-frontend-v1-komplain" data-component="body">
                        <code>false</code>
                    </label>
                    <br>
                    <p>Example: <code>true</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lampiran1</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="lampiran1" data-endpoint="POSTapi-frontend-v1-komplain" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>/tmp/phpibides5c6o72aSm64xJ</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lampiran2</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="lampiran2" data-endpoint="POSTapi-frontend-v1-komplain" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>/tmp/phpv79v82046enb7O8ye0p</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lampiran3</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="lampiran3" data-endpoint="POSTapi-frontend-v1-komplain" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>/tmp/phpga9k0mi9gohgdtATC5l</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lampiran4</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="lampiran4" data-endpoint="POSTapi-frontend-v1-komplain" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>/tmp/phpgr4blhpsgmpmeEI5LeX</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-komplain-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-komplain-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/komplain/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/komplain/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/komplain/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-komplain-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-komplain-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-komplain-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-komplain-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-komplain-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-komplain-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-komplain-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/komplain/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-komplain-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-komplain-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-komplain-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-komplain-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-komplain-cache--prefix--');"
                        hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-komplain-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/komplain/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-komplain-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-komplain-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-komplain-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-galeri">Display a listing of galeri with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-galeri">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/galeri" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/galeri"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/galeri';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-galeri">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 113
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;galeri&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;album_id&quot;: 1,
                &quot;judul&quot;: &quot;Asperiores et voluptatem quia ducimus.&quot;,
                &quot;gambar&quot;: [
                    &quot;zC6YMWM4HBZ0tN8tdg8zqaw3w647roITyWOTqcU8.png&quot;
                ],
                &quot;link&quot;: null,
                &quot;status&quot;: true,
                &quot;jenis&quot;: &quot;file&quot;,
                &quot;slug&quot;: &quot;asperiores-et-voluptatem-quia-ducimus&quot;,
                &quot;created_at&quot;: &quot;2026-07-09T06:53:31.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-07-10T00:16:44.000000Z&quot;,
                &quot;gambar_path&quot;: &quot;http://localhost:8000/storage/publikasi/galeri/zC6YMWM4HBZ0tN8tdg8zqaw3w647roITyWOTqcU8.png&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 1,
            &quot;count&quot;: 1,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/galeri?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/galeri?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/galeri?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-galeri" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-galeri"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-galeri"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-galeri" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-galeri">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-galeri"
                data-method="GET"
                data-path="api/frontend/v1/galeri"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-galeri', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-galeri" onclick="tryItOut('GETapi-frontend-v1-galeri');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-galeri" onclick="cancelTryOut('GETapi-frontend-v1-galeri');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-galeri" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/galeri</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-galeri" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-galeri" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-galeri-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-galeri-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/galeri/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/galeri/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/galeri/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-galeri-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-galeri-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-galeri-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-galeri-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-galeri-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-galeri-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-galeri-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/galeri/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-galeri-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-galeri-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-galeri-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-galeri-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-galeri-cache--prefix--');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-galeri-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/galeri/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-galeri-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-galeri-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-galeri-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-album">Display a listing of album with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-album">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/album" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/album"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/album';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-album">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 112
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;album&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;judul&quot;: &quot;Utama&quot;,
                &quot;gambar&quot;: &quot;AJYMKzrbUwReS1NvJkiR6sOkQ5IoXjgC2vZdFFo7.png&quot;,
                &quot;status&quot;: true,
                &quot;slug&quot;: &quot;utama&quot;,
                &quot;created_at&quot;: &quot;2026-07-09T06:52:54.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-07-10T00:13:13.000000Z&quot;,
                &quot;gambar_path&quot;: &quot;http://localhost:8000/storage/publikasi/album/AJYMKzrbUwReS1NvJkiR6sOkQ5IoXjgC2vZdFFo7.png&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 1,
            &quot;count&quot;: 1,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/album?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/album?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/album?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-album" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-album"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-album"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-album" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-album">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-album"
                data-method="GET"
                data-path="api/frontend/v1/album"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-album', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-album" onclick="tryItOut('GETapi-frontend-v1-album');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-album" onclick="cancelTryOut('GETapi-frontend-v1-album');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-album" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/album</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-album" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-album" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-album-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-album-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/album/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/album/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/album/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-album-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-album-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-album-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-album-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-album-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-album-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-album-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/album/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-album-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-album-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-album-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-album-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-album-cache--prefix--');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-album-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/album/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-album-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-album-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-album-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-potensi">Display a listing of potensi with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-potensi">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/potensi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/potensi"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/potensi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-potensi">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 111
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;potensi&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;kategori_id&quot;: 1,
                &quot;nama_potensi&quot;: &quot;Potensi 1&quot;,
                &quot;deskripsi&quot;: &quot;Deskripsi potensi 1&quot;,
                &quot;lokasi&quot;: &quot;Lokasi potensi 1&quot;,
                &quot;file_gambar&quot;: &quot;/img/no-image.png&quot;,
                &quot;long&quot;: null,
                &quot;lat&quot;: null,
                &quot;created_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;file_gambar_path&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        },
        {
            &quot;type&quot;: &quot;potensi&quot;,
            &quot;id&quot;: &quot;2&quot;,
            &quot;attributes&quot;: {
                &quot;kategori_id&quot;: 1,
                &quot;nama_potensi&quot;: &quot;Potensi 2&quot;,
                &quot;deskripsi&quot;: &quot;Deskripsi potensi 2&quot;,
                &quot;lokasi&quot;: &quot;Lokasi potensi 2&quot;,
                &quot;file_gambar&quot;: &quot;/img/no-image.png&quot;,
                &quot;long&quot;: null,
                &quot;lat&quot;: null,
                &quot;created_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;file_gambar_path&quot;: &quot;http://localhost:8000/img/no-image.png&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 2,
            &quot;count&quot;: 2,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/potensi?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/potensi?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/potensi?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-potensi" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-potensi"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-potensi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-potensi" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-potensi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-potensi"
                data-method="GET"
                data-path="api/frontend/v1/potensi"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-potensi', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-potensi" onclick="tryItOut('GETapi-frontend-v1-potensi');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-potensi" onclick="cancelTryOut('GETapi-frontend-v1-potensi');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-potensi" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/potensi</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-potensi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-potensi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-potensi-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-potensi-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/potensi/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/potensi/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/potensi/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-potensi-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-potensi-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-potensi-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-potensi-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-potensi-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-potensi-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-potensi-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/potensi/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-potensi-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-potensi-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-potensi-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-potensi-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-potensi-cache--prefix--');" hidden>Cancel
                        🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-potensi-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/potensi/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-potensi-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-potensi-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-potensi-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-form-dokumen">Display a listing of form dokumen with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-form-dokumen">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/form-dokumen" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/form-dokumen"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/form-dokumen';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-form-dokumen">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 110
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;formdokumen&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;nama_dokumen&quot;: &quot;Panduan OpenDK&quot;,
                &quot;description&quot;: &quot;dfafda afadsfas&quot;,
                &quot;jenis_dokumen&quot;: {
                    &quot;id&quot;: 1,
                    &quot;nama&quot;: &quot;Tersedia setiap saat&quot;
                },
                &quot;is_published&quot;: 1,
                &quot;published_at&quot;: &quot;2026-06-24 13:43:56&quot;,
                &quot;retention_days&quot;: 0,
                &quot;expired_at&quot;: null,
                &quot;file_dokumen_path&quot;: &quot;http://localhost:8000/storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf&quot;,
                &quot;mime_type&quot;: &quot;application/pdf&quot;,
                &quot;created_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-24T06:43:56.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;formdokumen&quot;,
            &quot;id&quot;: &quot;2&quot;,
            &quot;attributes&quot;: {
                &quot;nama_dokumen&quot;: &quot;Langsung tampil ubah&quot;,
                &quot;description&quot;: &quot;langsung tammpil&quot;,
                &quot;jenis_dokumen&quot;: {
                    &quot;id&quot;: 1,
                    &quot;nama&quot;: &quot;Tersedia setiap saat&quot;
                },
                &quot;is_published&quot;: 1,
                &quot;published_at&quot;: &quot;2026-06-24 13:35:11&quot;,
                &quot;retention_days&quot;: 0,
                &quot;expired_at&quot;: null,
                &quot;file_dokumen_path&quot;: &quot;http://localhost:8000/storage/form_dokumen/zS2m7L1cy8n24vMfRCpSH8eVmlib3QDOCE5rYqvW.png&quot;,
                &quot;mime_type&quot;: &quot;image/png&quot;,
                &quot;created_at&quot;: &quot;2026-06-24T06:22:07.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-06-24T06:35:11.000000Z&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 2,
            &quot;count&quot;: 2,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/form-dokumen?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/form-dokumen?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/form-dokumen?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-form-dokumen" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-form-dokumen"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-form-dokumen"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-form-dokumen" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-form-dokumen">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-form-dokumen"
                data-method="GET"
                data-path="api/frontend/v1/form-dokumen"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-form-dokumen', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-form-dokumen" onclick="tryItOut('GETapi-frontend-v1-form-dokumen');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-form-dokumen" onclick="cancelTryOut('GETapi-frontend-v1-form-dokumen');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-form-dokumen" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/form-dokumen</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-form-dokumen" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-form-dokumen" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-form-dokumen-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-form-dokumen-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/form-dokumen/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/form-dokumen/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/form-dokumen/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-form-dokumen-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-form-dokumen-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-form-dokumen-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-form-dokumen-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-form-dokumen-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-form-dokumen-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-form-dokumen-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/form-dokumen/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-form-dokumen-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-form-dokumen-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-form-dokumen-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-form-dokumen-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-form-dokumen-cache--prefix--');"
                        hidden
                    >Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-form-dokumen-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/form-dokumen/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-form-dokumen-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-form-dokumen-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-form-dokumen-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-regulasi">Display a listing of regulasi with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-regulasi">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/regulasi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/regulasi"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/regulasi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-regulasi">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 109
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;regulasi&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;profil_id&quot;: 1,
                &quot;tipe_regulasi&quot;: &quot;2&quot;,
                &quot;judul&quot;: &quot;Regulasi 1&quot;,
                &quot;deskripsi&quot;: &quot;Deskripsi regulasi 1&quot;,
                &quot;file_regulasi&quot;: &quot;storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf&quot;,
                &quot;mime_type&quot;: &quot;pdf&quot;,
                &quot;created_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;file_regulasi_path&quot;: &quot;http://localhost:8000/storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf&quot;,
                &quot;path_download&quot;: &quot;http://localhost:8000/unduhan/regulasi/1/download&quot;
            }
        },
        {
            &quot;type&quot;: &quot;regulasi&quot;,
            &quot;id&quot;: &quot;2&quot;,
            &quot;attributes&quot;: {
                &quot;profil_id&quot;: 1,
                &quot;tipe_regulasi&quot;: &quot;2&quot;,
                &quot;judul&quot;: &quot;Regulasi 2&quot;,
                &quot;deskripsi&quot;: &quot;Deskripsi regulasi 2&quot;,
                &quot;file_regulasi&quot;: &quot;storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf&quot;,
                &quot;mime_type&quot;: &quot;pdf&quot;,
                &quot;created_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;file_regulasi_path&quot;: &quot;http://localhost:8000/storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf&quot;,
                &quot;path_download&quot;: &quot;http://localhost:8000/unduhan/regulasi/2/download&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 2,
            &quot;count&quot;: 2,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/regulasi?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/regulasi?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/regulasi?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-regulasi" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-regulasi"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-regulasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-regulasi" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-regulasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-regulasi"
                data-method="GET"
                data-path="api/frontend/v1/regulasi"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-regulasi', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-regulasi" onclick="tryItOut('GETapi-frontend-v1-regulasi');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-regulasi" onclick="cancelTryOut('GETapi-frontend-v1-regulasi');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-regulasi" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/regulasi</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-regulasi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-regulasi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-regulasi-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-regulasi-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/regulasi/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/regulasi/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/regulasi/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-regulasi-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-regulasi-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-regulasi-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-regulasi-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-regulasi-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-regulasi-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-regulasi-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/regulasi/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-regulasi-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-regulasi-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-regulasi-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-regulasi-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-regulasi-cache--prefix--');"
                        hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-regulasi-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/regulasi/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-regulasi-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-regulasi-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-regulasi-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-prosedur-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-prosedur-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/frontend/v1/prosedur/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/prosedur/cache/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/prosedur/cache/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-frontend-v1-prosedur-cache--prefix--">
            </span>
            <span id="execution-results-DELETEapi-frontend-v1-prosedur-cache--prefix--" hidden>
                <blockquote>Received response<span id="execution-response-status-DELETEapi-frontend-v1-prosedur-cache--prefix--"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-frontend-v1-prosedur-cache--prefix--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-frontend-v1-prosedur-cache--prefix--" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-frontend-v1-prosedur-cache--prefix--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-DELETEapi-frontend-v1-prosedur-cache--prefix--"
                data-method="DELETE"
                data-path="api/frontend/v1/prosedur/cache/{prefix?}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-prosedur-cache--prefix--', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-frontend-v1-prosedur-cache--prefix--" onclick="tryItOut('DELETEapi-frontend-v1-prosedur-cache--prefix--');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-frontend-v1-prosedur-cache--prefix--" onclick="cancelTryOut('DELETEapi-frontend-v1-prosedur-cache--prefix--');"
                        hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-frontend-v1-prosedur-cache--prefix--" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/frontend/v1/prosedur/cache/{prefix?}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="DELETEapi-frontend-v1-prosedur-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="DELETEapi-frontend-v1-prosedur-cache--prefix--" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="prefix" data-endpoint="DELETEapi-frontend-v1-prosedur-cache--prefix--" value="architecto" data-component="url">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan">GET api/frontend/v1/statistik/chart-tingkat-pendidikan</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-tingkat-pendidikan" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-tingkat-pendidikan"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-tingkat-pendidikan';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;grafik&quot;: [
        {
            &quot;year&quot;: &quot;Semester 1&quot;,
            &quot;tidak_tamat_sekolah&quot;: 0,
            &quot;tamat_sd&quot;: 0,
            &quot;tamat_smp&quot;: 0,
            &quot;tamat_sma&quot;: 0,
            &quot;tamat_diploma_sederajat&quot;: 0
        },
        {
            &quot;year&quot;: &quot;Semester 2&quot;,
            &quot;tidak_tamat_sekolah&quot;: 0,
            &quot;tamat_sd&quot;: 0,
            &quot;tamat_smp&quot;: 0,
            &quot;tamat_sma&quot;: 0,
            &quot;tamat_diploma_sederajat&quot;: 0
        }
    ],
    &quot;tabel&quot;: []
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-tingkat-pendidikan"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-tingkat-pendidikan', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-tingkat-pendidikan');">Try it
                        out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan"
                        onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-tingkat-pendidikan');" hidden
                    >Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-tingkat-pendidikan</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-tingkat-pendidikan" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-tingkat-pendidikan" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-putus-sekolah">GET api/frontend/v1/statistik/chart-putus-sekolah</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-putus-sekolah">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-putus-sekolah" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-putus-sekolah"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-putus-sekolah';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-putus-sekolah">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;grafik&quot;: [
        {
            &quot;year&quot;: &quot;Semester 1&quot;,
            &quot;siswa_paud&quot;: 0,
            &quot;anak_usia_paud&quot;: 0,
            &quot;siswa_sd&quot;: 0,
            &quot;anak_usia_sd&quot;: 0,
            &quot;siswa_smp&quot;: 0,
            &quot;anak_usia_smp&quot;: 0,
            &quot;siswa_sma&quot;: 0,
            &quot;anak_usia_sma&quot;: 0
        },
        {
            &quot;year&quot;: &quot;Semester 2&quot;,
            &quot;siswa_paud&quot;: 0,
            &quot;anak_usia_paud&quot;: 0,
            &quot;siswa_sd&quot;: 0,
            &quot;anak_usia_sd&quot;: 0,
            &quot;siswa_smp&quot;: 0,
            &quot;anak_usia_smp&quot;: 0,
            &quot;siswa_sma&quot;: 0,
            &quot;anak_usia_sma&quot;: 0
        }
    ],
    &quot;tabel&quot;: []
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-putus-sekolah" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-putus-sekolah"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-putus-sekolah"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-putus-sekolah" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-putus-sekolah">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-putus-sekolah"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-putus-sekolah"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-putus-sekolah', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-putus-sekolah" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-putus-sekolah');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-putus-sekolah" onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-putus-sekolah');"
                        hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-putus-sekolah" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-putus-sekolah</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-putus-sekolah" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-putus-sekolah" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-fasilitas-paud">GET api/frontend/v1/statistik/chart-fasilitas-paud</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-fasilitas-paud">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-fasilitas-paud" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-fasilitas-paud"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-fasilitas-paud';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-fasilitas-paud">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;grafik&quot;: [
        {
            &quot;year&quot;: &quot;Semester 1&quot;,
            &quot;jumlah_paud&quot;: 0,
            &quot;jumlah_guru_paud&quot;: 0,
            &quot;jumlah_siswa_paud&quot;: 0
        },
        {
            &quot;year&quot;: &quot;Semester 2&quot;,
            &quot;jumlah_paud&quot;: 0,
            &quot;jumlah_guru_paud&quot;: 0,
            &quot;jumlah_siswa_paud&quot;: 0
        }
    ],
    &quot;tabel&quot;: []
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-fasilitas-paud" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-fasilitas-paud"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-fasilitas-paud"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-fasilitas-paud" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-fasilitas-paud">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-fasilitas-paud"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-fasilitas-paud"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-fasilitas-paud', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-fasilitas-paud" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-fasilitas-paud');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-fasilitas-paud" onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-fasilitas-paud');"
                        hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-fasilitas-paud" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-fasilitas-paud</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-fasilitas-paud" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-fasilitas-paud" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-akiakb">GET api/frontend/v1/statistik/chart-akiakb</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-akiakb">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-akiakb" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-akiakb"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-akiakb';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-akiakb">
                <blockquote>
                    <p>Example response (500):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 59
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-akiakb" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-akiakb"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-akiakb"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-akiakb" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-akiakb">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-akiakb"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-akiakb"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-akiakb', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-akiakb" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-akiakb');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-akiakb" onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-akiakb');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-akiakb" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request
                        💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-akiakb</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-akiakb" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-akiakb" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-imunisasi">GET api/frontend/v1/statistik/chart-imunisasi</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-imunisasi">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-imunisasi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-imunisasi"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-imunisasi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-imunisasi">
                <blockquote>
                    <p>Example response (500):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-imunisasi" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-imunisasi"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-imunisasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-imunisasi" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-imunisasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-imunisasi"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-imunisasi"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-imunisasi', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-imunisasi" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-imunisasi');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-imunisasi" onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-imunisasi');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-imunisasi" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-imunisasi</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-imunisasi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-imunisasi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-penyakit">GET api/frontend/v1/statistik/chart-penyakit</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-penyakit">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-penyakit" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-penyakit"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-penyakit';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-penyakit">
                <blockquote>
                    <p>Example response (500):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-penyakit" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-penyakit"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-penyakit"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-penyakit" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-penyakit">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-penyakit"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-penyakit"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-penyakit', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-penyakit" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-penyakit');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-penyakit" onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-penyakit');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-penyakit" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-penyakit</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-penyakit" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-penyakit" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-sanitasi">GET api/frontend/v1/statistik/chart-sanitasi</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-sanitasi">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-sanitasi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-sanitasi"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-sanitasi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-sanitasi">
                <blockquote>
                    <p>Example response (500):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-sanitasi" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-sanitasi"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-sanitasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-sanitasi" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-sanitasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-sanitasi"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-sanitasi"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-sanitasi', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-sanitasi" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-sanitasi');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-sanitasi" onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-sanitasi');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-sanitasi" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-sanitasi</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-sanitasi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-sanitasi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-penduduk">GET api/frontend/v1/statistik/chart-penduduk</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-penduduk">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-penduduk" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-penduduk"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-penduduk';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-penduduk">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;program&quot;: &quot;JAMKESMAS&quot;,
        &quot;value&quot;: 0
    }
]</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-penduduk" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-penduduk"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-penduduk"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-penduduk" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-penduduk">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-penduduk"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-penduduk"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-penduduk', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-penduduk" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-penduduk');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-penduduk" onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-penduduk');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-penduduk" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-penduduk</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-penduduk" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-penduduk" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-keluarga">GET api/frontend/v1/statistik/chart-keluarga</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-keluarga">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-keluarga" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-keluarga"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-keluarga';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-keluarga">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;program&quot;: &quot;BPNT&quot;,
        &quot;value&quot;: 0
    },
    {
        &quot;program&quot;: &quot;BLSM&quot;,
        &quot;value&quot;: 0
    },
    {
        &quot;program&quot;: &quot;PKH&quot;,
        &quot;value&quot;: 0
    },
    {
        &quot;program&quot;: &quot;Bedah Rumah&quot;,
        &quot;value&quot;: 0
    }
]</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-keluarga" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-keluarga"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-keluarga"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-keluarga" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-keluarga">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-keluarga"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-keluarga"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-keluarga', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-keluarga" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-keluarga');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-keluarga" onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-keluarga');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-keluarga" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-keluarga</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-keluarga" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-keluarga" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-anggaran-realisasi">GET api/frontend/v1/statistik/chart-anggaran-realisasi</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-anggaran-realisasi">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-anggaran-realisasi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-anggaran-realisasi"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-anggaran-realisasi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-anggaran-realisasi">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;sum&quot;: {
        &quot;total_belanja&quot;: 0,
        &quot;total_belanja_persen&quot;: 0,
        &quot;selisih_anggaran_realisasi&quot;: 0,
        &quot;selisih_anggaran_realisasi_persen&quot;: 0,
        &quot;belanja_pegawai&quot;: 0,
        &quot;belanja_pegawai_persen&quot;: 0,
        &quot;belanja_barang_jasa&quot;: 0,
        &quot;belanja_barang_jasa_persen&quot;: 0,
        &quot;belanja_modal&quot;: 0,
        &quot;belanja_modal_persen&quot;: 0,
        &quot;belanja_tidak_langsung&quot;: 0,
        &quot;belanja_tidak_langsung_persen&quot;: 0
    },
    &quot;chart&quot;: [
        {
            &quot;anggaran&quot;: &quot;Belanja Pegawai&quot;,
            &quot;value&quot;: 0
        },
        {
            &quot;anggaran&quot;: &quot;Belanja Barang dan Jasa&quot;,
            &quot;value&quot;: 0
        },
        {
            &quot;anggaran&quot;: &quot;Belanja Modal&quot;,
            &quot;value&quot;: 0
        },
        {
            &quot;anggaran&quot;: &quot;Belanja Tidak Langsung&quot;,
            &quot;value&quot;: 0
        }
    ]
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-anggaran-realisasi" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-anggaran-realisasi"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-anggaran-realisasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-anggaran-realisasi" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-anggaran-realisasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-anggaran-realisasi"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-anggaran-realisasi"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-anggaran-realisasi', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-anggaran-realisasi" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-anggaran-realisasi');">Try it
                        out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-anggaran-realisasi"
                        onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-anggaran-realisasi');" hidden
                    >Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-anggaran-realisasi" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-anggaran-realisasi</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-anggaran-realisasi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-anggaran-realisasi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-anggaran-desa">GET api/frontend/v1/statistik/chart-anggaran-desa</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-anggaran-desa">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik/chart-anggaran-desa" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik/chart-anggaran-desa"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik/chart-anggaran-desa';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-anggaran-desa">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 119
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;grafik&quot;: [
        {
            &quot;anggaran&quot;: &quot;4 - PENDAPATAN&quot;,
            &quot;jumlah&quot;: 0
        },
        {
            &quot;anggaran&quot;: &quot;5 - BELANJA&quot;,
            &quot;jumlah&quot;: 0
        },
        {
            &quot;anggaran&quot;: &quot;6 - PEMBIAYAAN&quot;,
            &quot;jumlah&quot;: 0
        }
    ],
    &quot;detail&quot;: &quot;&lt;div class=\&quot;box box-primary\&quot;&gt;\n    &lt;div class=\&quot;box-header with-border\&quot;&gt;\n        &lt;h3 class=\&quot;box-title\&quot;&gt;Detail Anggaran Desa (APBDes)&lt;/h3&gt;\n    &lt;/div&gt;\n    &lt;!-- /.box-header --&gt;\n    &lt;div class=\&quot;box-body\&quot;&gt;\n        &lt;div class=\&quot;box-group\&quot; id=\&quot;accordion\&quot;&gt;\n                            &lt;div class=\&quot;panel box box-primary\&quot;&gt;\n                    &lt;div class=\&quot;box-header with-border\&quot;&gt;\n                        &lt;h4 class=\&quot;box-title\&quot;&gt;\n                            &lt;a data-toggle=\&quot;collapse\&quot; data-parent=\&quot;#accordion\&quot; href=\&quot;#collapseOne\&quot;&gt;\n                                4 - PENDAPATAN\n                            &lt;/a&gt;\n                        &lt;/h4&gt;\n                        &lt;div class=\&quot;box-tools pull-right\&quot;&gt;\n                            &lt;a data-toggle=\&quot;collapse\&quot; data-parent=\&quot;#accordion\&quot; href=\&quot;#collapseOne\&quot;&gt;\n                                &lt;h4&gt;0,00&lt;/h4&gt;\n                            &lt;/a&gt;\n                        &lt;/div&gt;\n                    &lt;/div&gt;\n                    &lt;div id=\&quot;collapseOne\&quot; class=\&quot;panel-collapse collapse\&quot;&gt;\n                        &lt;div class=\&quot;box-body\&quot;&gt;\n                            &lt;table class=\&quot;table table-striped table-bordered\&quot; id=\&quot;data-coa\&quot;&gt;\n                                &lt;thead&gt;\n                                    &lt;tr&gt;\n                                        &lt;th&gt;#&lt;/th&gt;\n                                        &lt;th style=\&quot;width: 100px\&quot; colspan=\&quot;4\&quot;&gt;Nomor Akun&lt;/th&gt;\n                                        &lt;th&gt;Nama Akun&lt;/th&gt;\n                                        &lt;th style=\&quot;width: 150px; text-align: center\&quot;&gt;Jumlah&lt;/th&gt;\n                                    &lt;/tr&gt;\n                                &lt;/thead&gt;\n                                &lt;tbody&gt;\n                                                                            &lt;tr&gt;\n                                            &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td colspan=\&quot;3\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;Pendapatan Asli Desa\n&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td align=\&quot;right\&quot;&gt;\n                                                &lt;strong&gt;0,00&lt;/strong&gt;\n                                            &lt;/td&gt;\n                                        &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Hasil Usaha&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Hasil Aset&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Swadaya&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Lain-lain Pendapatan Asli Desa&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                                                    &lt;tr&gt;\n                                            &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td colspan=\&quot;3\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;Transfer&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td align=\&quot;right\&quot;&gt;\n                                                &lt;strong&gt;0,00&lt;/strong&gt;\n                                            &lt;/td&gt;\n                                        &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Dana Desa&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/kota&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Alokasi Dana Desa&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Bantuan Keuangan Provinsi&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Bantuan Keuangan APBD Kabupaten/Kota&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                                                    &lt;tr&gt;\n                                            &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td colspan=\&quot;3\&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;Pendapatan Lain-lain&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td align=\&quot;right\&quot;&gt;\n                                                &lt;strong&gt;0,00&lt;/strong&gt;\n                                            &lt;/td&gt;\n                                        &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Penerimaan dari Hasil Kerjasama antar Desa &lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Penerimaan dari Hasil Kerjasama Desa dengan Pihak Ketiga&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Penerimaan dari Bantuan Perusahaan yang berlokasi di Desa&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Hibah dan sumbangan dari Pihak Ketiga&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Koreksi kesalahan belanja tahun-tahun anggaran sebelumnya yang mengakibatkan penerimaan di kas Desa pada tahun anggaran berjalan&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Bunga Bank&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;9&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Lain-lain pendapatan Desa yang sah&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                                            &lt;/tbody&gt;\n                            &lt;/table&gt;\n                        &lt;/div&gt;\n                    &lt;/div&gt;\n                &lt;/div&gt;\n                            &lt;div class=\&quot;panel box box-primary\&quot;&gt;\n                    &lt;div class=\&quot;box-header with-border\&quot;&gt;\n                        &lt;h4 class=\&quot;box-title\&quot;&gt;\n                            &lt;a data-toggle=\&quot;collapse\&quot; data-parent=\&quot;#accordion\&quot; href=\&quot;#collapseTwo\&quot;&gt;\n                                5 - BELANJA\n                            &lt;/a&gt;\n                        &lt;/h4&gt;\n                        &lt;div class=\&quot;box-tools pull-right\&quot;&gt;\n                            &lt;a data-toggle=\&quot;collapse\&quot; data-parent=\&quot;#accordion\&quot; href=\&quot;#collapseTwo\&quot;&gt;\n                                &lt;h4&gt;0,00&lt;/h4&gt;\n                            &lt;/a&gt;\n                        &lt;/div&gt;\n                    &lt;/div&gt;\n                    &lt;div id=\&quot;collapseTwo\&quot; class=\&quot;panel-collapse collapse\&quot;&gt;\n                        &lt;div class=\&quot;box-body\&quot;&gt;\n                            &lt;table class=\&quot;table table-striped table-bordered\&quot; id=\&quot;data-coa\&quot;&gt;\n                                &lt;thead&gt;\n                                    &lt;tr&gt;\n                                        &lt;th&gt;#&lt;/th&gt;\n                                        &lt;th style=\&quot;width: 100px\&quot; colspan=\&quot;4\&quot;&gt;Nomor Akun&lt;/th&gt;\n                                        &lt;th&gt;Nama Akun&lt;/th&gt;\n                                        &lt;th style=\&quot;width: 150px; text-align: center\&quot;&gt;Jumlah&lt;/th&gt;\n                                    &lt;/tr&gt;\n                                &lt;/thead&gt;\n                                &lt;tbody&gt;\n                                                                            &lt;tr&gt;\n                                            &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td colspan=\&quot;3\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;Belanja Pegawai&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td align=\&quot;right\&quot;&gt;\n                                                &lt;strong&gt;0,00&lt;/strong&gt;\n                                            &lt;/td&gt;\n                                        &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Penghasilan Tetap dan Tunjangan Kepala Desa&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Penghasilan Tetap dan Tunjangan Perangkat Desa&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Jaminan Sosial Kepala Desa dan Perangkat Desa&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Tunjangan BPD&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                                                    &lt;tr&gt;\n                                            &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td colspan=\&quot;3\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;Belanja Barang dan Jasa&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td align=\&quot;right\&quot;&gt;\n                                                &lt;strong&gt;0,00&lt;/strong&gt;\n                                            &lt;/td&gt;\n                                        &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Jasa Honorarium&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Perjalanan Dinas&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Jasa Sewa&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Operasional Perkantoran&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Pemeliharaan&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;7&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Barang dan Jasa yang Diserahkan kepada Masyarakat&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                                                    &lt;tr&gt;\n                                            &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td colspan=\&quot;3\&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;Belanja Modal&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td align=\&quot;right\&quot;&gt;\n                                                &lt;strong&gt;0,00&lt;/strong&gt;\n                                            &lt;/td&gt;\n                                        &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Modal Pengadaan Tanah&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Modal Peralatan, Mesin, dan Alat Berat&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Modal Kendaraan &lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Modal Gedung, Bangunan dan Taman&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Modal Jalan/Prasarana Jalan&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Modal Jembatan&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;7&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Modal Irigasi/Embung/Air Sungai/Drainase/Air Limbah/Persampahan&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;8&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Modal Jaringan/Instalasi&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;9&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Modal lainnya&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                                                    &lt;tr&gt;\n                                            &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td colspan=\&quot;3\&quot;&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;Belanja Tak Terduga&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td align=\&quot;right\&quot;&gt;\n                                                &lt;strong&gt;0,00&lt;/strong&gt;\n                                            &lt;/td&gt;\n                                        &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;4&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Belanja Tak Terduga&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                                            &lt;/tbody&gt;\n                            &lt;/table&gt;\n                        &lt;/div&gt;\n                    &lt;/div&gt;\n                &lt;/div&gt;\n                            &lt;div class=\&quot;panel box box-primary\&quot;&gt;\n                    &lt;div class=\&quot;box-header with-border\&quot;&gt;\n                        &lt;h4 class=\&quot;box-title\&quot;&gt;\n                            &lt;a data-toggle=\&quot;collapse\&quot; data-parent=\&quot;#accordion\&quot; href=\&quot;#collapseThree\&quot;&gt;\n                                6 - PEMBIAYAAN\n                            &lt;/a&gt;\n                        &lt;/h4&gt;\n                        &lt;div class=\&quot;box-tools pull-right\&quot;&gt;\n                            &lt;a data-toggle=\&quot;collapse\&quot; data-parent=\&quot;#accordion\&quot; href=\&quot;#collapseThree\&quot;&gt;\n                                &lt;h4&gt;0,00&lt;/h4&gt;\n                            &lt;/a&gt;\n                        &lt;/div&gt;\n                    &lt;/div&gt;\n                    &lt;div id=\&quot;collapseThree\&quot; class=\&quot;panel-collapse collapse\&quot;&gt;\n                        &lt;div class=\&quot;box-body\&quot;&gt;\n                            &lt;table class=\&quot;table table-striped table-bordered\&quot; id=\&quot;data-coa\&quot;&gt;\n                                &lt;thead&gt;\n                                    &lt;tr&gt;\n                                        &lt;th&gt;#&lt;/th&gt;\n                                        &lt;th style=\&quot;width: 100px\&quot; colspan=\&quot;4\&quot;&gt;Nomor Akun&lt;/th&gt;\n                                        &lt;th&gt;Nama Akun&lt;/th&gt;\n                                        &lt;th style=\&quot;width: 150px; text-align: center\&quot;&gt;Jumlah&lt;/th&gt;\n                                    &lt;/tr&gt;\n                                &lt;/thead&gt;\n                                &lt;tbody&gt;\n                                                                            &lt;tr&gt;\n                                            &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td colspan=\&quot;3\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;Penerimaan Pembiayaan&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td align=\&quot;right\&quot;&gt;\n                                                &lt;strong&gt;0,00&lt;/strong&gt;\n                                            &lt;/td&gt;\n                                        &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;SILPA Tahun Sebelumya&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Pencairan Dana Cadangan&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Hasil Penjualan Kekayaan Desa yang Dipisahkan&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;9&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Penerimaan Pembiayaan Lainnya&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                                                    &lt;tr&gt;\n                                            &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td colspan=\&quot;3\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td&gt;&lt;strong&gt;Pengeluaran Pembiayaan&lt;/strong&gt;&lt;/td&gt;\n                                            &lt;td align=\&quot;right\&quot;&gt;\n                                                &lt;strong&gt;0,00&lt;/strong&gt;\n                                            &lt;/td&gt;\n                                        &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Pembentukan Dana Cadangan&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Penyertaan Modal Desa&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                    &lt;tr&gt;\n                                                &lt;td class=\&quot;icon-class\&quot;&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;2&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td colspan=\&quot;2\&quot;&gt;&lt;strong&gt;9&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td&gt;&lt;strong&gt;&amp;emsp;&amp;emsp;Pengeluaran Pembiayaan lainnya&lt;/strong&gt;&lt;/td&gt;\n                                                &lt;td align=\&quot;right\&quot;&gt;\n                                                    &lt;strong&gt;0,00&lt;/strong&gt;\n                                                &lt;/td&gt;\n                                            &lt;/tr&gt;\n                                                                                                            &lt;/tbody&gt;\n                            &lt;/table&gt;\n                        &lt;/div&gt;\n                    &lt;/div&gt;\n                &lt;/div&gt;\n                    &lt;/div&gt;\n    &lt;/div&gt;\n&lt;/div&gt;\n&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-chart-anggaran-desa" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-chart-anggaran-desa"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-chart-anggaran-desa"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-chart-anggaran-desa" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-chart-anggaran-desa">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-chart-anggaran-desa"
                data-method="GET"
                data-path="api/frontend/v1/statistik/chart-anggaran-desa"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-chart-anggaran-desa', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-chart-anggaran-desa" onclick="tryItOut('GETapi-frontend-v1-statistik-chart-anggaran-desa');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-chart-anggaran-desa" onclick="cancelTryOut('GETapi-frontend-v1-statistik-chart-anggaran-desa');"
                        hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-chart-anggaran-desa" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik/chart-anggaran-desa</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-chart-anggaran-desa" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-chart-anggaran-desa" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-frontend-v1-faq">Display a listing of FAQ with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-faq">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/faq" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/faq"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/faq';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-faq">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 120
x-ratelimit-remaining: 108
x-ratelimit-reset: 59
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;faq&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;question&quot;: &quot;Apa itu OpenDK?&quot;,
                &quot;answer&quot;: &quot;&lt;p&gt;OpenDK [Dashboard Kecamatan Terbuka] adalah aplikasi yang bisa digunakan oleh Pemerintah Kecamatan di Seluruh Indonesia. Aplikasi ini sangat berguna untuk menampilkan statistik di wilayah Kecamatan, diantaranya adalah statistik Penduduk, statistik Kesehatan, Statistik Pendidikan dan Statistik lainnya. Upaya ini adalah sebagai bentuk transparansi dan Keterbukaan Informasi Publik yang dilakukan Pemerintah Kecamatan kepada seluruh rakyat di wilayahnya.&lt;/p&gt;&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:03:26.000000Z&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 1,
            &quot;count&quot;: 1,
            &quot;per_page&quot;: 10,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/faq?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/faq?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/faq?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-faq" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-faq"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-faq"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-faq" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-faq">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-faq"
                data-method="GET"
                data-path="api/frontend/v1/faq"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-faq', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-faq" onclick="tryItOut('GETapi-frontend-v1-faq');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-faq" onclick="cancelTryOut('GETapi-frontend-v1-faq');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-faq" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/faq</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-faq" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-faq" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h1 id="opensid-integration">OpenSID Integration</h1>

            <h2 id="opensid-integration-POSTapi-v1-penduduk">Sinkronisasi data penduduk dari OpenSID.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-penduduk">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/penduduk" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "hapus_penduduk[][id_pend_desa]=16"\
    --form "hapus_penduduk[][desa_id]=architecto"\
    --form "desa_id=3201012001"\
    --form "file=@/tmp/php173hg3o829u002xyQ7y" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/penduduk"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('hapus_penduduk[][id_pend_desa]', '16');
body.append('hapus_penduduk[][desa_id]', 'architecto');
body.append('desa_id', '3201012001');
body.append('file', document.querySelector('input[name="file"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/penduduk';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'hapus_penduduk[][id_pend_desa]',
                'contents' =&gt; '16'
            ],
            [
                'name' =&gt; 'hapus_penduduk[][desa_id]',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'desa_id',
                'contents' =&gt; '3201012001'
            ],
            [
                'name' =&gt; 'file',
                'contents' =&gt; fopen('/tmp/php173hg3o829u002xyQ7y', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-penduduk">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Proses sync Data Penduduk OpenSID sedang berjalan&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-penduduk" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-penduduk"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-penduduk"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-penduduk" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-penduduk">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-penduduk"
                data-method="POST"
                data-path="api/v1/penduduk"
                data-authed="0"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-penduduk', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-penduduk" onclick="tryItOut('POSTapi-v1-penduduk');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-penduduk" onclick="cancelTryOut('POSTapi-v1-penduduk');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-penduduk" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/penduduk</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-penduduk" value="multipart/form-data" data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-penduduk" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <details>
                        <summary style="padding-bottom: 10px;">
                            <b style="line-height: 2;"><code>hapus_penduduk</code></b>&nbsp;&nbsp;
                            <small>object[]</small>&nbsp;
                            <i>optional</i> &nbsp;
                            &nbsp;
                            <br>

                        </summary>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>id_pend_desa</code></b>&nbsp;&nbsp;
                            <small>integer</small>&nbsp;
                            <i>optional</i> &nbsp;
                            &nbsp;
                            <input
                                type="number"
                                style="display: none"
                                step="any"
                                name="hapus_penduduk.0.id_pend_desa"
                                data-endpoint="POSTapi-v1-penduduk"
                                value="16"
                                data-component="body"
                            >
                            <br>
                            <p>Example: <code>16</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>foto</code></b>&nbsp;&nbsp;
                            <small>string</small>&nbsp;
                            <i>optional</i> &nbsp;
                            &nbsp;
                            <input type="text" style="display: none" name="hapus_penduduk.0.foto" data-endpoint="POSTapi-v1-penduduk" value="" data-component="body">
                            <br>

                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                            <small>string</small>&nbsp;
                            <i>optional</i> &nbsp;
                            &nbsp;
                            <input type="text" style="display: none" name="hapus_penduduk.0.desa_id" data-endpoint="POSTapi-v1-penduduk" value="architecto" data-component="body">
                            <br>
                            <p>Must match an existing stored value. Example: <code>architecto</code></p>
                        </div>
                    </details>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-penduduk" value="3201012001" data-component="body">
                    <br>
                    <p>Kode desa. Example: <code>3201012001</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-penduduk" value="" data-component="body">
                    <br>
                    <p>File ZIP berisi data penduduk. Example: <code>/tmp/php173hg3o829u002xyQ7y</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-POSTapi-v1-laporan-apbdes">Sinkronisasi data APBDes dari OpenSID.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-laporan-apbdes">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/laporan-apbdes" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"desa_id\": \"3201012001\",
    \"laporan_apbdes\": [
        \"architecto\"
    ]
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/laporan-apbdes"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "desa_id": "3201012001",
    "laporan_apbdes": [
        "architecto"
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/laporan-apbdes';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'desa_id' =&gt; '3201012001',
            'laporan_apbdes' =&gt; [
                'architecto',
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-laporan-apbdes">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Proses sync data Laporan Apbdes OpenSID sedang berjalan&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-laporan-apbdes" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-laporan-apbdes"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-laporan-apbdes"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-laporan-apbdes" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-laporan-apbdes">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-laporan-apbdes"
                data-method="POST"
                data-path="api/v1/laporan-apbdes"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-laporan-apbdes', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-laporan-apbdes" onclick="tryItOut('POSTapi-v1-laporan-apbdes');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-laporan-apbdes" onclick="cancelTryOut('POSTapi-v1-laporan-apbdes');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-laporan-apbdes" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/laporan-apbdes</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-laporan-apbdes" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-laporan-apbdes" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-laporan-apbdes" value="3201012001" data-component="body">
                    <br>
                    <p>Kode desa. Example: <code>3201012001</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <details>
                        <summary style="padding-bottom: 10px;">
                            <b style="line-height: 2;"><code>laporan_apbdes</code></b>&nbsp;&nbsp;
                            <small>string[]</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <br>
                            <p>Data laporan APBDes.</p>
                        </summary>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                            <small>integer</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input
                                type="number"
                                style="display: none"
                                step="any"
                                name="laporan_apbdes.0.id"
                                data-endpoint="POSTapi-v1-laporan-apbdes"
                                value="16"
                                data-component="body"
                            >
                            <br>
                            <p>Example: <code>16</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
                            <small>string</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input type="text" style="display: none" name="laporan_apbdes.0.judul" data-endpoint="POSTapi-v1-laporan-apbdes" value="architecto" data-component="body">
                            <br>
                            <p>Example: <code>architecto</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>tahun</code></b>&nbsp;&nbsp;
                            <small>integer</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input
                                type="number"
                                style="display: none"
                                step="any"
                                name="laporan_apbdes.0.tahun"
                                data-endpoint="POSTapi-v1-laporan-apbdes"
                                value="16"
                                data-component="body"
                            >
                            <br>
                            <p>Example: <code>16</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>semester</code></b>&nbsp;&nbsp;
                            <small>integer</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input
                                type="number"
                                style="display: none"
                                step="any"
                                name="laporan_apbdes.0.semester"
                                data-endpoint="POSTapi-v1-laporan-apbdes"
                                value="16"
                                data-component="body"
                            >
                            <br>
                            <p>Example: <code>16</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>nama_file</code></b>&nbsp;&nbsp;
                            <small>string</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input type="text" style="display: none" name="laporan_apbdes.0.nama_file" data-endpoint="POSTapi-v1-laporan-apbdes" value="architecto" data-component="body">
                            <br>
                            <p>Example: <code>architecto</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                            <small>string</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input type="text" style="display: none" name="laporan_apbdes.0.file" data-endpoint="POSTapi-v1-laporan-apbdes" value="architecto" data-component="body">
                            <br>
                            <p>Example: <code>architecto</code></p>
                        </div>
                    </details>
                </div>
            </form>

            <h2 id="opensid-integration-POSTapi-v1-laporan-penduduk">Sinkronisasi laporan penduduk dari OpenSID.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-laporan-penduduk">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/laporan-penduduk" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"desa_id\": \"3201012001\",
    \"laporan_penduduk\": [
        \"architecto\"
    ]
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/laporan-penduduk"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "desa_id": "3201012001",
    "laporan_penduduk": [
        "architecto"
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/laporan-penduduk';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'desa_id' =&gt; '3201012001',
            'laporan_penduduk' =&gt; [
                'architecto',
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-laporan-penduduk">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Proses sync data Laporan Penduduk OpenSID sedang berjalan&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-laporan-penduduk" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-laporan-penduduk"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-laporan-penduduk"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-laporan-penduduk" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-laporan-penduduk">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-laporan-penduduk"
                data-method="POST"
                data-path="api/v1/laporan-penduduk"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-laporan-penduduk', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-laporan-penduduk" onclick="tryItOut('POSTapi-v1-laporan-penduduk');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-laporan-penduduk" onclick="cancelTryOut('POSTapi-v1-laporan-penduduk');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-laporan-penduduk" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/laporan-penduduk</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-laporan-penduduk" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-laporan-penduduk" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-laporan-penduduk" value="3201012001" data-component="body">
                    <br>
                    <p>Kode desa. Example: <code>3201012001</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <details>
                        <summary style="padding-bottom: 10px;">
                            <b style="line-height: 2;"><code>laporan_penduduk</code></b>&nbsp;&nbsp;
                            <small>string[]</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <br>
                            <p>Data laporan penduduk.</p>
                        </summary>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                            <small>integer</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input
                                type="number"
                                style="display: none"
                                step="any"
                                name="laporan_penduduk.0.id"
                                data-endpoint="POSTapi-v1-laporan-penduduk"
                                value="16"
                                data-component="body"
                            >
                            <br>
                            <p>Example: <code>16</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
                            <small>string</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input type="text" style="display: none" name="laporan_penduduk.0.judul" data-endpoint="POSTapi-v1-laporan-penduduk" value="architecto" data-component="body">
                            <br>
                            <p>Example: <code>architecto</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>bulan</code></b>&nbsp;&nbsp;
                            <small>integer</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input
                                type="number"
                                style="display: none"
                                step="any"
                                name="laporan_penduduk.0.bulan"
                                data-endpoint="POSTapi-v1-laporan-penduduk"
                                value="16"
                                data-component="body"
                            >
                            <br>
                            <p>Example: <code>16</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>tahun</code></b>&nbsp;&nbsp;
                            <small>integer</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input
                                type="number"
                                style="display: none"
                                step="any"
                                name="laporan_penduduk.0.tahun"
                                data-endpoint="POSTapi-v1-laporan-penduduk"
                                value="16"
                                data-component="body"
                            >
                            <br>
                            <p>Example: <code>16</code></p>
                        </div>
                        <div style="margin-left: 14px; clear: unset;">
                            <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                            <small>string</small>&nbsp;
                            &nbsp;
                            &nbsp;
                            <input type="text" style="display: none" name="laporan_penduduk.0.file" data-endpoint="POSTapi-v1-laporan-penduduk" value="architecto" data-component="body">
                            <br>
                            <p>Example: <code>architecto</code></p>
                        </div>
                    </details>
                </div>
            </form>

            <h2 id="opensid-integration-POSTapi-v1-pesan">Kirim pesan baru atau balas pesan dari OpenSID.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-pesan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/pesan" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"pesan\": \"Berikut kami kirimkan laporan bulanan.\",
    \"judul\": \"Laporan Bulanan\",
    \"kode_desa\": \"3201012001\",
    \"pengirim\": \"operator@desa.id\",
    \"nama_pengirim\": \"Ahmad\",
    \"pesan_id\": 5
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/pesan"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "pesan": "Berikut kami kirimkan laporan bulanan.",
    "judul": "Laporan Bulanan",
    "kode_desa": "3201012001",
    "pengirim": "operator@desa.id",
    "nama_pengirim": "Ahmad",
    "pesan_id": 5
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/pesan';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'pesan' =&gt; 'Berikut kami kirimkan laporan bulanan.',
            'judul' =&gt; 'Laporan Bulanan',
            'kode_desa' =&gt; '3201012001',
            'pengirim' =&gt; 'operator@desa.id',
            'nama_pengirim' =&gt; 'Ahmad',
            'pesan_id' =&gt; 5,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-pesan">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: true,
    &quot;message&quot;: &quot;Berhasil mengirim pesan&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-pesan" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-pesan"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-pesan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-pesan" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-pesan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-pesan"
                data-method="POST"
                data-path="api/v1/pesan"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-pesan', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-pesan" onclick="tryItOut('POSTapi-v1-pesan');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-pesan" onclick="cancelTryOut('POSTapi-v1-pesan');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-pesan" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/pesan</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-pesan" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-pesan" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>pesan</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="pesan" data-endpoint="POSTapi-v1-pesan" value="Berikut kami kirimkan laporan bulanan." data-component="body">
                    <br>
                    <p>Isi pesan. Example: <code>Berikut kami kirimkan laporan bulanan.</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="judul" data-endpoint="POSTapi-v1-pesan" value="Laporan Bulanan" data-component="body">
                    <br>
                    <p>Judul pesan (wajib untuk pesan baru). Example: <code>Laporan Bulanan</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>kode_desa</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="kode_desa" data-endpoint="POSTapi-v1-pesan" value="3201012001" data-component="body">
                    <br>
                    <p>Kode desa pengirim. Example: <code>3201012001</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>pengirim</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="pengirim" data-endpoint="POSTapi-v1-pesan" value="operator@desa.id" data-component="body">
                    <br>
                    <p>Pengirim pesan. Example: <code>operator@desa.id</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nama_pengirim</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nama_pengirim" data-endpoint="POSTapi-v1-pesan" value="Ahmad" data-component="body">
                    <br>
                    <p>Nama pengirim. Example: <code>Ahmad</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>pesan_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="pesan_id"
                        data-endpoint="POSTapi-v1-pesan"
                        value="5"
                        data-component="body"
                    >
                    <br>
                    <p>ID pesan untuk membalas percakapan yang sudah ada. Example: <code>5</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-POSTapi-v1-pesan-getpesan">Ambil daftar pesan untuk desa tertentu.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-pesan-getpesan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/pesan/getpesan" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"kode_desa\": \"3201012001\",
    \"id\": 0
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/pesan/getpesan"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "kode_desa": "3201012001",
    "id": 0
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/pesan/getpesan';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'kode_desa' =&gt; '3201012001',
            'id' =&gt; 0,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-pesan-getpesan">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: true,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;judul&quot;: &quot;Laporan&quot;,
            &quot;detailPesan&quot;: []
        }
    ]
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-pesan-getpesan" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-pesan-getpesan"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-pesan-getpesan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-pesan-getpesan" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-pesan-getpesan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-pesan-getpesan"
                data-method="POST"
                data-path="api/v1/pesan/getpesan"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-pesan-getpesan', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-pesan-getpesan" onclick="tryItOut('POSTapi-v1-pesan-getpesan');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-pesan-getpesan" onclick="cancelTryOut('POSTapi-v1-pesan-getpesan');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-pesan-getpesan" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/pesan/getpesan</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-pesan-getpesan" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-pesan-getpesan" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>kode_desa</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="kode_desa" data-endpoint="POSTapi-v1-pesan-getpesan" value="3201012001" data-component="body">
                    <br>
                    <p>Kode desa. Example: <code>3201012001</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="id"
                        data-endpoint="POSTapi-v1-pesan-getpesan"
                        value="0"
                        data-component="body"
                    >
                    <br>
                    <p>ID pesan terakhir yang diterima (untuk pagination). Example: <code>0</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-GETapi-v1-pesan-detail">Lihat detail percakapan pesan.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-pesan-detail">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/pesan/detail?id=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/pesan/detail"
);

const params = {
    "id": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/pesan/detail';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'id' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-pesan-detail">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: true,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;judul&quot;: &quot;Laporan&quot;,
        &quot;detailPesan&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;text&quot;: &quot;Isi pesan&quot;
            }
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-pesan-detail" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-v1-pesan-detail"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-pesan-detail"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-pesan-detail" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-pesan-detail">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-v1-pesan-detail"
                data-method="GET"
                data-path="api/v1/pesan/detail"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-pesan-detail', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-pesan-detail" onclick="tryItOut('GETapi-v1-pesan-detail');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-pesan-detail" onclick="cancelTryOut('GETapi-v1-pesan-detail');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-pesan-detail" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/pesan/detail</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-v1-pesan-detail" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-v1-pesan-detail" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="id"
                        data-endpoint="GETapi-v1-pesan-detail"
                        value="1"
                        data-component="query"
                    >
                    <br>
                    <p>ID pesan. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-POSTapi-v1-pembangunan-dokumentasi">Sinkronisasi dokumentasi pembangunan dari OpenSID.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-pembangunan-dokumentasi">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/pembangunan/dokumentasi" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=architecto"\
    --form "file=@/tmp/phpfhd5n3b9rmru3rWiB1M" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/pembangunan/dokumentasi"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('desa_id', 'architecto');
body.append('file', document.querySelector('input[name="file"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/pembangunan/dokumentasi';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'desa_id',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'file',
                'contents' =&gt; fopen('/tmp/phpfhd5n3b9rmru3rWiB1M', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-pembangunan-dokumentasi">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Proses Sinkronisasi Data Pembangunan OpenSID sedang berjalan&quot;,
    &quot;status&quot;: &quot;success&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-pembangunan-dokumentasi" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-pembangunan-dokumentasi"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-pembangunan-dokumentasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-pembangunan-dokumentasi" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-pembangunan-dokumentasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-pembangunan-dokumentasi"
                data-method="POST"
                data-path="api/v1/pembangunan/dokumentasi"
                data-authed="0"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-pembangunan-dokumentasi', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-pembangunan-dokumentasi" onclick="tryItOut('POSTapi-v1-pembangunan-dokumentasi');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-pembangunan-dokumentasi" onclick="cancelTryOut('POSTapi-v1-pembangunan-dokumentasi');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-pembangunan-dokumentasi" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/pembangunan/dokumentasi</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-pembangunan-dokumentasi" value="multipart/form-data" data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-pembangunan-dokumentasi" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-pembangunan-dokumentasi" value="" data-component="body">
                    <br>
                    <p>File ZIP berisi data dokumentasi (csv/xlsx). Example: <code>/tmp/phpfhd5n3b9rmru3rWiB1M</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-pembangunan-dokumentasi" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-POSTapi-v1-identitas-desa">Sinkronisasi identitas desa dari OpenSID.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-identitas-desa">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/identitas-desa" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"kode_desa\": \"3201012001\",
    \"sebutan_desa\": \"Kampung\",
    \"website\": \"https:\\/\\/desa.example.com\",
    \"path\": \"profil\\/desa\"
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/identitas-desa"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "kode_desa": "3201012001",
    "sebutan_desa": "Kampung",
    "website": "https:\/\/desa.example.com",
    "path": "profil\/desa"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/identitas-desa';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'kode_desa' =&gt; '3201012001',
            'sebutan_desa' =&gt; 'Kampung',
            'website' =&gt; 'https://desa.example.com',
            'path' =&gt; 'profil/desa',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-identitas-desa">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Proses sinkronisasi identitas desa sudah selesai&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-identitas-desa" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-identitas-desa"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-identitas-desa"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-identitas-desa" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-identitas-desa">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-identitas-desa"
                data-method="POST"
                data-path="api/v1/identitas-desa"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-identitas-desa', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-identitas-desa" onclick="tryItOut('POSTapi-v1-identitas-desa');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-identitas-desa" onclick="cancelTryOut('POSTapi-v1-identitas-desa');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-identitas-desa" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/identitas-desa</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-identitas-desa" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-identitas-desa" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>kode_desa</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="kode_desa" data-endpoint="POSTapi-v1-identitas-desa" value="3201012001" data-component="body">
                    <br>
                    <p>Kode desa. Example: <code>3201012001</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sebutan_desa</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="sebutan_desa" data-endpoint="POSTapi-v1-identitas-desa" value="Kampung" data-component="body">
                    <br>
                    <p>Sebutan desa. Example: <code>Kampung</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>website</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="website" data-endpoint="POSTapi-v1-identitas-desa" value="https://desa.example.com" data-component="body">
                    <br>
                    <p>URL website desa. Example: <code>https://desa.example.com</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>path</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="path" data-endpoint="POSTapi-v1-identitas-desa" value="profil/desa" data-component="body">
                    <br>
                    <p>Path menu profil. Example: <code>profil/desa</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-POSTapi-v1-program-bantuan">Sinkronisasi data program bantuan dari OpenSID.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-program-bantuan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/program-bantuan" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=architecto"\
    --form "file=@/tmp/phpbp0odcq4o67edpl4Dhl" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/program-bantuan"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('desa_id', 'architecto');
body.append('file', document.querySelector('input[name="file"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/program-bantuan';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'desa_id',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'file',
                'contents' =&gt; fopen('/tmp/phpbp0odcq4o67edpl4Dhl', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-program-bantuan">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Data Bantuan Sedang di Sinkronkan&quot;,
    &quot;status&quot;: &quot;success&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-program-bantuan" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-program-bantuan"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-program-bantuan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-program-bantuan" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-program-bantuan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-program-bantuan"
                data-method="POST"
                data-path="api/v1/program-bantuan"
                data-authed="0"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-program-bantuan', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-program-bantuan" onclick="tryItOut('POSTapi-v1-program-bantuan');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-program-bantuan" onclick="cancelTryOut('POSTapi-v1-program-bantuan');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-program-bantuan" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/program-bantuan</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-program-bantuan" value="multipart/form-data" data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-program-bantuan" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-program-bantuan" value="" data-component="body">
                    <br>
                    <p>File ZIP berisi data bantuan (csv/xlsx). Example: <code>/tmp/phpbp0odcq4o67edpl4Dhl</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-program-bantuan" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-POSTapi-v1-program-bantuan-peserta">Sinkronisasi data peserta program bantuan dari OpenSID.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-program-bantuan-peserta">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/program-bantuan/peserta" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=architecto"\
    --form "file=@/tmp/phpts143mokgt0n7PPdlMA" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/program-bantuan/peserta"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('desa_id', 'architecto');
body.append('file', document.querySelector('input[name="file"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/program-bantuan/peserta';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'desa_id',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'file',
                'contents' =&gt; fopen('/tmp/phpts143mokgt0n7PPdlMA', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-program-bantuan-peserta">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Data Bantuan Sedang di Sinkronkan&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-program-bantuan-peserta" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-program-bantuan-peserta"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-program-bantuan-peserta"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-program-bantuan-peserta" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-program-bantuan-peserta">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-program-bantuan-peserta"
                data-method="POST"
                data-path="api/v1/program-bantuan/peserta"
                data-authed="0"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-program-bantuan-peserta', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-program-bantuan-peserta" onclick="tryItOut('POSTapi-v1-program-bantuan-peserta');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-program-bantuan-peserta" onclick="cancelTryOut('POSTapi-v1-program-bantuan-peserta');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-program-bantuan-peserta" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/program-bantuan/peserta</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-program-bantuan-peserta" value="multipart/form-data" data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-program-bantuan-peserta" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-program-bantuan-peserta" value="" data-component="body">
                    <br>
                    <p>File ZIP berisi data peserta bantuan (csv/xlsx). Example: <code>/tmp/phpts143mokgt0n7PPdlMA</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-program-bantuan-peserta" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-GETapi-v1-surat">Daftar surat untuk desa tertentu.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-surat">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/surat?desa_id=3201012001" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"desa_id\": \"architecto\"
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/surat"
);

const params = {
    "desa_id": "3201012001",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "desa_id": "architecto"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/surat';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'desa_id' =&gt; '3201012001',
        ],
        'json' =&gt; [
            'desa_id' =&gt; 'architecto',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-surat">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: true,
    &quot;message&quot;: &quot;Daftar Surat&quot;,
    &quot;data&quot;: [
        {
            &quot;nomor&quot;: &quot;001/SK/2024&quot;,
            &quot;file&quot;: &quot;surat.pdf&quot;,
            &quot;nama&quot;: &quot;SK Kepala Desa&quot;
        }
    ]
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-surat" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-v1-surat"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-surat"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-surat" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-surat">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-v1-surat"
                data-method="GET"
                data-path="api/v1/surat"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-surat', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-surat" onclick="tryItOut('GETapi-v1-surat');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-surat" onclick="cancelTryOut('GETapi-v1-surat');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-surat" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/surat</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-v1-surat" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-v1-surat" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="GETapi-v1-surat" value="3201012001" data-component="query">
                    <br>
                    <p>Kode desa. Example: <code>3201012001</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="GETapi-v1-surat" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-POSTapi-v1-surat-kirim">Kirim surat dari OpenSID ke OpenDK (TTE).</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-surat-kirim">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/surat/kirim" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=3201012001"\
    --form "nik=3201012001000001"\
    --form "tanggal=2024-01-15"\
    --form "nomor=001/SK/2024"\
    --form "nama=SK Kepala Desa"\
    --form "file=@/tmp/phpe0d42jtikvku8hMULTw" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/surat/kirim"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('desa_id', '3201012001');
body.append('nik', '3201012001000001');
body.append('tanggal', '2024-01-15');
body.append('nomor', '001/SK/2024');
body.append('nama', 'SK Kepala Desa');
body.append('file', document.querySelector('input[name="file"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/surat/kirim';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'desa_id',
                'contents' =&gt; '3201012001'
            ],
            [
                'name' =&gt; 'nik',
                'contents' =&gt; '3201012001000001'
            ],
            [
                'name' =&gt; 'tanggal',
                'contents' =&gt; '2024-01-15'
            ],
            [
                'name' =&gt; 'nomor',
                'contents' =&gt; '001/SK/2024'
            ],
            [
                'name' =&gt; 'nama',
                'contents' =&gt; 'SK Kepala Desa'
            ],
            [
                'name' =&gt; 'file',
                'contents' =&gt; fopen('/tmp/phpe0d42jtikvku8hMULTw', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-surat-kirim">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: true,
    &quot;message&quot;: &quot;Surat Berhasil Dikirim!&quot;,
    &quot;data&quot;: {
        &quot;nomor&quot;: &quot;001/SK/2024&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-surat-kirim" hidden>
                <blockquote>Received response<span id="execution-response-status-POSTapi-v1-surat-kirim"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-surat-kirim"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-surat-kirim" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-surat-kirim">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-POSTapi-v1-surat-kirim"
                data-method="POST"
                data-path="api/v1/surat/kirim"
                data-authed="0"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-surat-kirim', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-surat-kirim" onclick="tryItOut('POSTapi-v1-surat-kirim');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-surat-kirim" onclick="cancelTryOut('POSTapi-v1-surat-kirim');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-surat-kirim" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/surat/kirim</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-surat-kirim" value="multipart/form-data" data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="POSTapi-v1-surat-kirim" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-surat-kirim" value="3201012001" data-component="body">
                    <br>
                    <p>Kode desa. Example: <code>3201012001</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nik" data-endpoint="POSTapi-v1-surat-kirim" value="3201012001000001" data-component="body">
                    <br>
                    <p>NIK penduduk (16 digit). Example: <code>3201012001000001</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>tanggal</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="tanggal" data-endpoint="POSTapi-v1-surat-kirim" value="2024-01-15" data-component="body">
                    <br>
                    <p>Tanggal surat (Y-m-d). Example: <code>2024-01-15</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nomor</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nomor" data-endpoint="POSTapi-v1-surat-kirim" value="001/SK/2024" data-component="body">
                    <br>
                    <p>Nomor surat (unique). Example: <code>001/SK/2024</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nama</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nama" data-endpoint="POSTapi-v1-surat-kirim" value="SK Kepala Desa" data-component="body">
                    <br>
                    <p>Nama surat. Example: <code>SK Kepala Desa</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-surat-kirim" value="" data-component="body">
                    <br>
                    <p>File PDF surat (max 2MB). Example: <code>/tmp/phpe0d42jtikvku8hMULTw</code></p>
                </div>
            </form>

            <h2 id="opensid-integration-GETapi-v1-surat-download">Download file surat dalam format PDF.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-surat-download">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/surat/download?desa_id=3201012001&amp;nomor=001%2FSK%2F2024" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"desa_id\": \"architecto\",
    \"nomor\": \"architecto\"
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/surat/download"
);

const params = {
    "desa_id": "3201012001",
    "nomor": "001/SK/2024",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "desa_id": "architecto",
    "nomor": "architecto"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/v1/surat/download';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'desa_id' =&gt; '3201012001',
            'nomor' =&gt; '001/SK/2024',
        ],
        'json' =&gt; [
            'desa_id' =&gt; 'architecto',
            'nomor' =&gt; 'architecto',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-surat-download">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;Content-Type&quot;: &quot;application/pdf&quot;,
    &quot;Content-Disposition&quot;: &quot;inline; filename=\&quot;surat.pdf\&quot;&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-surat-download" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-v1-surat-download"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-surat-download"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-surat-download" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-surat-download">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-v1-surat-download"
                data-method="GET"
                data-path="api/v1/surat/download"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-surat-download', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-surat-download" onclick="tryItOut('GETapi-v1-surat-download');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-surat-download" onclick="cancelTryOut('GETapi-v1-surat-download');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-surat-download" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/surat/download</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-v1-surat-download" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-v1-surat-download" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="GETapi-v1-surat-download" value="3201012001" data-component="query">
                    <br>
                    <p>Kode desa. Example: <code>3201012001</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>nomor</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nomor" data-endpoint="GETapi-v1-surat-download" value="001/SK/2024" data-component="query">
                    <br>
                    <p>Nomor surat. Example: <code>001/SK/2024</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>desa_id</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="desa_id" data-endpoint="GETapi-v1-surat-download" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nomor</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nomor" data-endpoint="GETapi-v1-surat-download" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h1 id="prosedur">Prosedur</h1>

            <h2 id="prosedur-GETapi-frontend-v1-prosedur">Daftar prosedur pelayanan.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-prosedur">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/prosedur?page=1&amp;per_page=10" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/prosedur"
);

const params = {
    "page": "1",
    "per_page": "10",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/prosedur';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'page' =&gt; '1',
            'per_page' =&gt; '10',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-prosedur">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;judul&quot;: &quot;Prosedur Pembuatan KTP&quot;,
            &quot;konten&quot;: &quot;...&quot;
        }
    ]
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-prosedur" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-prosedur"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-prosedur"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-prosedur" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-prosedur">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-prosedur"
                data-method="GET"
                data-path="api/frontend/v1/prosedur"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-prosedur', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-prosedur" onclick="tryItOut('GETapi-frontend-v1-prosedur');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-prosedur" onclick="cancelTryOut('GETapi-frontend-v1-prosedur');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-prosedur" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/prosedur</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-prosedur" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-prosedur" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="page"
                        data-endpoint="GETapi-frontend-v1-prosedur"
                        value="1"
                        data-component="query"
                    >
                    <br>
                    <p>Halaman. Example: <code>1</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="per_page"
                        data-endpoint="GETapi-frontend-v1-prosedur"
                        value="10"
                        data-component="query"
                    >
                    <br>
                    <p>Item per halaman. Example: <code>10</code></p>
                </div>
            </form>

            <h1 id="statistik-penduduk">Statistik Penduduk</h1>

            <h2 id="statistik-penduduk-GETapi-frontend-v1-statistik-penduduk-listYear">Daftar tahun yang tersedia untuk data statistik penduduk.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-penduduk-listYear">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik-penduduk/listYear" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/statistik-penduduk/listYear"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/statistik-penduduk/listYear';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-penduduk-listYear">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;tahun&quot;,
            &quot;attributes&quot;: [
                2019,
                2020,
                2021,
                2022,
                2023,
                2024,
                2025
            ]
        }
    ]
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-statistik-penduduk-listYear" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-statistik-penduduk-listYear"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-statistik-penduduk-listYear"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-statistik-penduduk-listYear" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-statistik-penduduk-listYear">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-statistik-penduduk-listYear"
                data-method="GET"
                data-path="api/frontend/v1/statistik-penduduk/listYear"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-penduduk-listYear', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-statistik-penduduk-listYear" onclick="tryItOut('GETapi-frontend-v1-statistik-penduduk-listYear');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-statistik-penduduk-listYear" onclick="cancelTryOut('GETapi-frontend-v1-statistik-penduduk-listYear');"
                        hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-statistik-penduduk-listYear" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send
                        Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/statistik-penduduk/listYear</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-statistik-penduduk-listYear" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-statistik-penduduk-listYear" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h1 id="website">Website</h1>

            <h2 id="website-GETapi-frontend-v1-website">Data website lengkap (profil, desa, events, medsos, navigasi, slides, dll).</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-website">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/website?page=1&amp;per_page=10" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/frontend/v1/website"
);

const params = {
    "page": "1",
    "per_page": "10",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

                <div class="php-example">
                    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/frontend/v1/website';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'page' =&gt; '1',
            'per_page' =&gt; '10',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-frontend-v1-website">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;profile&quot;,
            &quot;attributes&quot;: {
                &quot;nama_kecamatan&quot;: &quot;...&quot;
            }
        }
    ]
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-frontend-v1-website" hidden>
                <blockquote>Received response<span id="execution-response-status-GETapi-frontend-v1-website"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-frontend-v1-website"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-frontend-v1-website" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-frontend-v1-website">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form
                id="form-GETapi-frontend-v1-website"
                data-method="GET"
                data-path="api/frontend/v1/website"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-website', this);"
            >
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-frontend-v1-website" onclick="tryItOut('GETapi-frontend-v1-website');">Try it out ⚡
                    </button>
                    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-frontend-v1-website" onclick="cancelTryOut('GETapi-frontend-v1-website');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-frontend-v1-website" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/frontend/v1/website</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="GETapi-frontend-v1-website" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="Accept" data-endpoint="GETapi-frontend-v1-website" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="page"
                        data-endpoint="GETapi-frontend-v1-website"
                        value="1"
                        data-component="query"
                    >
                    <br>
                    <p>Halaman. Example: <code>1</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input
                        type="number"
                        style="display: none"
                        step="any"
                        name="per_page"
                        data-endpoint="GETapi-frontend-v1-website"
                        value="10"
                        data-component="query"
                    >
                    <br>
                    <p>Item per halaman. Example: <code>10</code></p>
                </div>
            </form>

        </div>
        <div class="dark-box">
            <div class="lang-selector">
                <button type="button" class="lang-button" data-language-name="bash">bash</button>
                <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                <button type="button" class="lang-button" data-language-name="php">php</button>
            </div>
        </div>
    </div>
</body>

</html>
