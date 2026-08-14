<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>OpenDK API Documentation</title>

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
    </style>

    <script>
        var tryItOutBaseUrl = "http://opendk.test/";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset('/vendor/scribe/js/tryitout-5.6.0.js') }}"></script>

    <script src="{{ asset('/vendor/scribe/js/theme-default-5.6.0.js') }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

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
            <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-login">
                        <a href="#endpoints-POSTapi-v1-auth-login">Get a JWT via given credentials.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-logout">
                        <a href="#endpoints-POSTapi-v1-auth-logout">Log the user out (Invalidate the token).</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-refresh">
                        <a href="#endpoints-POSTapi-v1-auth-refresh">Refresh a token.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-auth-me">
                        <a href="#endpoints-GETapi-v1-auth-me">Get the authenticated User.</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-test">
                        <a href="#endpoints-GETapi-v1-test">GET api/v1/test</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-penduduk">
                        <a href="#endpoints-POSTapi-v1-penduduk">Hapus Data Penduduk Sesuai OpenSID</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-penduduk-storedata">
                        <a href="#endpoints-POSTapi-v1-penduduk-storedata">Tambah dan Ubah Data dan Foto Penduduk Sesuai OpenSID</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-laporan-apbdes">
                        <a href="#endpoints-POSTapi-v1-laporan-apbdes">Tambah / Ubah Data Apbdes Sesuai OpenSID</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-laporan-penduduk">
                        <a href="#endpoints-POSTapi-v1-laporan-penduduk">Tambah / Ubah Data Laporan Penduduk Dari OpenSID</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-pesan">
                        <a href="#endpoints-POSTapi-v1-pesan">POST api/v1/pesan</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-pesan-getpesan">
                        <a href="#endpoints-POSTapi-v1-pesan-getpesan">POST api/v1/pesan/getpesan</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-pesan-detail">
                        <a href="#endpoints-GETapi-v1-pesan-detail">GET api/v1/pesan/detail</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-pembangunan">
                        <a href="#endpoints-POSTapi-v1-pembangunan">Tambah Data Pembangunan Sesuai OpenSID</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-pembangunan-dokumentasi">
                        <a href="#endpoints-POSTapi-v1-pembangunan-dokumentasi">POST api/v1/pembangunan/dokumentasi</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-identitas-desa">
                        <a href="#endpoints-POSTapi-v1-identitas-desa">POST api/v1/identitas-desa</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-program-bantuan">
                        <a href="#endpoints-POSTapi-v1-program-bantuan">POST api/v1/program-bantuan</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-program-bantuan-peserta">
                        <a href="#endpoints-POSTapi-v1-program-bantuan-peserta">POST api/v1/program-bantuan/peserta</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-surat">
                        <a href="#endpoints-GETapi-v1-surat">index</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-surat-kirim">
                        <a href="#endpoints-POSTapi-v1-surat-kirim">store</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-surat-download">
                        <a href="#endpoints-GETapi-v1-surat-download">index</a>
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
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-website">
                        <a href="#endpoints-GETapi-frontend-v1-website">GET api/frontend/v1/website</a>
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
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-statistik-penduduk-listYear">
                        <a href="#endpoints-GETapi-frontend-v1-statistik-penduduk-listYear">GET api/frontend/v1/statistik-penduduk/listYear</a>
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
        </div>

        <ul class="toc-footer" id="toc-footer">
            <li style="padding-bottom: 5px;"><a href="{{ route('scribe.postman') }}">View Postman collection</a></li>
            <li style="padding-bottom: 5px;"><a href="{{ route('scribe.openapi') }}">View OpenAPI spec</a></li>
            <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
        </ul>

        <ul class="toc-footer" id="last-updated">
            <li>Last updated: February 18, 2026</li>
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
            <p>This API is not authenticated.</p>

            <h1 id="endpoints">Endpoints</h1>

            <h2 id="endpoints-POSTapi-v1-auth-login">Get a JWT via given credentials.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-auth-login">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"gbailey@example.net\",
    \"password\": \"architecto\"
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/auth/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "gbailey@example.net",
    "password": "architecto"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-auth-login">
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
                    <input type="text" style="display: none" name="email" data-endpoint="POSTapi-v1-auth-login" value="gbailey@example.net" data-component="body">
                    <br>
                    <p>Isian value harus berupa alamat surel yang valid. Example: <code>gbailey@example.net</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="password" data-endpoint="POSTapi-v1-auth-login" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-v1-auth-logout">Log the user out (Invalidate the token).</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-auth-logout">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/auth/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/auth/logout"
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

            <h2 id="endpoints-POSTapi-v1-auth-refresh">Refresh a token.</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-auth-refresh">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/auth/refresh" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/auth/refresh"
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

            <h2 id="endpoints-GETapi-v1-auth-me">Get the authenticated User.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-auth-me">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://opendk.test/api/v1/auth/me" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/auth/me"
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

            </span>

            <span id="example-responses-GETapi-v1-auth-me">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/v1/auth/me could not be found.&quot;
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

            <h2 id="endpoints-GETapi-v1-test">GET api/v1/test</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-test">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://opendk.test/api/v1/test" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/test"
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

            </span>

            <span id="example-responses-GETapi-v1-test">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/v1/test could not be found.&quot;
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

            <h2 id="endpoints-POSTapi-v1-penduduk">Hapus Data Penduduk Sesuai OpenSID</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-penduduk">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/penduduk" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"hapus_penduduk\": [
        {
            \"id_pend_desa\": 16,
            \"desa_id\": \"architecto\"
        }
    ]
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/penduduk"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "hapus_penduduk": [
        {
            "id_pend_desa": 16,
            "desa_id": "architecto"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-penduduk">
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
                data-hasfiles="0"
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
                    <input type="text" style="display: none" name="Content-Type" data-endpoint="POSTapi-v1-penduduk" value="application/json" data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
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
                            <p>The <code>desa_id</code> of an existing record in the das_data_desa table. Example: <code>architecto</code></p>
                        </div>
                    </details>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-v1-penduduk-storedata">Tambah dan Ubah Data dan Foto Penduduk Sesuai OpenSID</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-penduduk-storedata">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/penduduk/storedata" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "file=@C:\Users\habib\AppData\Local\Temp\php20E0.tmp" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/penduduk/storedata"
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
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 5120 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php20E0.tmp</code></p>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-v1-laporan-apbdes">Tambah / Ubah Data Apbdes Sesuai OpenSID</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-laporan-apbdes">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/laporan-apbdes" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"desa_id\": \"architecto\",
    \"laporan_apbdes\": [
        {
            \"id\": 16,
            \"judul\": \"architecto\",
            \"tahun\": 16,
            \"semester\": 16,
            \"nama_file\": \"architecto\",
            \"file\": \"architecto\"
        }
    ]
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/laporan-apbdes"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "desa_id": "architecto",
    "laporan_apbdes": [
        {
            "id": 16,
            "judul": "architecto",
            "tahun": 16,
            "semester": 16,
            "nama_file": "architecto",
            "file": "architecto"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-laporan-apbdes">
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
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-laporan-apbdes" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <details>
                        <summary style="padding-bottom: 10px;">
                            <b style="line-height: 2;"><code>laporan_apbdes</code></b>&nbsp;&nbsp;
                            <small>object[]</small>&nbsp;
                            <i>optional</i> &nbsp;
                            &nbsp;
                            <br>

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

            <h2 id="endpoints-POSTapi-v1-laporan-penduduk">Tambah / Ubah Data Laporan Penduduk Dari OpenSID</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-laporan-penduduk">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/laporan-penduduk" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"desa_id\": \"architecto\",
    \"laporan_penduduk\": [
        {
            \"id\": 16,
            \"judul\": \"architecto\",
            \"bulan\": 16,
            \"tahun\": 16,
            \"file\": \"architecto\"
        }
    ]
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/laporan-penduduk"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "desa_id": "architecto",
    "laporan_penduduk": [
        {
            "id": 16,
            "judul": "architecto",
            "bulan": 16,
            "tahun": 16,
            "file": "architecto"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-laporan-penduduk">
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
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-laporan-penduduk" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <details>
                        <summary style="padding-bottom: 10px;">
                            <b style="line-height: 2;"><code>laporan_penduduk</code></b>&nbsp;&nbsp;
                            <small>object[]</small>&nbsp;
                            <i>optional</i> &nbsp;
                            &nbsp;
                            <br>

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

            <h2 id="endpoints-POSTapi-v1-pesan">POST api/v1/pesan</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-pesan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/pesan" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"pesan\": \"architecto\",
    \"judul\": \"architecto\",
    \"kode_desa\": \"ng\"
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/pesan"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "pesan": "architecto",
    "judul": "architecto",
    "kode_desa": "ng"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-pesan">
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
                    <input type="text" style="display: none" name="pesan" data-endpoint="POSTapi-v1-pesan" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="judul" data-endpoint="POSTapi-v1-pesan" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>kode_desa</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="kode_desa" data-endpoint="POSTapi-v1-pesan" value="ng" data-component="body">
                    <br>
                    <p>Isian value harus minimal 13 karakter. Isian value seharusnya tidak lebih dari 13 karakter. Example: <code>ng</code></p>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-v1-pesan-getpesan">POST api/v1/pesan/getpesan</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-pesan-getpesan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/pesan/getpesan" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"kode_desa\": \"bn\"
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/pesan/getpesan"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "kode_desa": "bn"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-pesan-getpesan">
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
                    <input type="text" style="display: none" name="kode_desa" data-endpoint="POSTapi-v1-pesan-getpesan" value="bn" data-component="body">
                    <br>
                    <p>Isian value harus minimal 13 karakter. Isian value seharusnya tidak lebih dari 13 karakter. Example: <code>bn</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-v1-pesan-detail">GET api/v1/pesan/detail</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-pesan-detail">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://opendk.test/api/v1/pesan/detail" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/pesan/detail"
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

            </span>

            <span id="example-responses-GETapi-v1-pesan-detail">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/v1/pesan/detail could not be found.&quot;
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
            </form>

            <h2 id="endpoints-POSTapi-v1-pembangunan">Tambah Data Pembangunan Sesuai OpenSID</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-pembangunan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/pembangunan" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=architecto"\
    --form "file=@C:\Users\habib\AppData\Local\Temp\php21CB.tmp" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/pembangunan"
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
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 5120 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php21CB.tmp</code></p>
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

            <h2 id="endpoints-POSTapi-v1-pembangunan-dokumentasi">POST api/v1/pembangunan/dokumentasi</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-pembangunan-dokumentasi">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/pembangunan/dokumentasi" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=architecto"\
    --form "file=@C:\Users\habib\AppData\Local\Temp\php21DC.tmp" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/pembangunan/dokumentasi"
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

            </span>

            <span id="example-responses-POSTapi-v1-pembangunan-dokumentasi">
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
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-pembangunan-dokumentasi" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 5120 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php21DC.tmp</code></p>
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

            <h2 id="endpoints-POSTapi-v1-identitas-desa">POST api/v1/identitas-desa</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-identitas-desa">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/identitas-desa" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"kode_desa\": \"architecto\",
    \"sebutan_desa\": \"architecto\",
    \"website\": \"http:\\/\\/bailey.com\\/\",
    \"path\": \"architecto\"
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/identitas-desa"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "kode_desa": "architecto",
    "sebutan_desa": "architecto",
    "website": "http:\/\/bailey.com\/",
    "path": "architecto"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-identitas-desa">
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
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="kode_desa" data-endpoint="POSTapi-v1-identitas-desa" value="architecto" data-component="body">
                    <br>
                    <p>The <code>desa_id</code> of an existing record in the das_data_desa table. Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sebutan_desa</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="sebutan_desa" data-endpoint="POSTapi-v1-identitas-desa" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>website</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="website" data-endpoint="POSTapi-v1-identitas-desa" value="http://bailey.com/" data-component="body">
                    <br>
                    <p>Must be a valid URL. Example: <code>http://bailey.com/</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>path</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="path" data-endpoint="POSTapi-v1-identitas-desa" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
            </form>

            <h2 id="endpoints-POSTapi-v1-program-bantuan">POST api/v1/program-bantuan</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-program-bantuan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/program-bantuan" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=architecto"\
    --form "file=@C:\Users\habib\AppData\Local\Temp\php21ED.tmp" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/program-bantuan"
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

            </span>

            <span id="example-responses-POSTapi-v1-program-bantuan">
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
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-program-bantuan" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 5120 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php21ED.tmp</code></p>
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

            <h2 id="endpoints-POSTapi-v1-program-bantuan-peserta">POST api/v1/program-bantuan/peserta</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-program-bantuan-peserta">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/program-bantuan/peserta" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=architecto"\
    --form "file=@C:\Users\habib\AppData\Local\Temp\php21EE.tmp" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/program-bantuan/peserta"
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

            </span>

            <span id="example-responses-POSTapi-v1-program-bantuan-peserta">
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
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-program-bantuan-peserta" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 5120 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php21EE.tmp</code></p>
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

            <h2 id="endpoints-GETapi-v1-surat">index</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-surat">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://opendk.test/api/v1/surat" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"desa_id\": \"architecto\"
}"
</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/surat"
);

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

            </span>

            <span id="example-responses-GETapi-v1-surat">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/v1/surat could not be found.&quot;
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

            <h2 id="endpoints-POSTapi-v1-surat-kirim">store</h2>

            <p>
            </p>

            <span id="example-requests-POSTapi-v1-surat-kirim">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://opendk.test/api/v1/surat/kirim" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "desa_id=architecto"\
    --form "nik=8225697757449171"\
    --form "tanggal=2026-02-18T16:39:16"\
    --form "nomor=architecto"\
    --form "nama=architecto"\
    --form "file=@C:\Users\habib\AppData\Local\Temp\php21FE.tmp" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/v1/surat/kirim"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('desa_id', 'architecto');
body.append('nik', '8225697757449171');
body.append('tanggal', '2026-02-18T16:39:16');
body.append('nomor', 'architecto');
body.append('nama', 'architecto');
body.append('file', document.querySelector('input[name="file"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-surat-kirim">
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
                    <input type="text" style="display: none" name="desa_id" data-endpoint="POSTapi-v1-surat-kirim" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nik" data-endpoint="POSTapi-v1-surat-kirim" value="8225697757449171" data-component="body">
                    <br>
                    <p>Isian value harus berupa angka sebanyak 16 digit. Example: <code>8225697757449171</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>tanggal</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="tanggal" data-endpoint="POSTapi-v1-surat-kirim" value="2026-02-18T16:39:16" data-component="body">
                    <br>
                    <p>Isian value bukan tanggal yang valid. Example: <code>2026-02-18T16:39:16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nomor</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nomor" data-endpoint="POSTapi-v1-surat-kirim" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>nama</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none" name="nama" data-endpoint="POSTapi-v1-surat-kirim" value="architecto" data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="file" data-endpoint="POSTapi-v1-surat-kirim" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 2048 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php21FE.tmp</code></p>
                </div>
            </form>

            <h2 id="endpoints-GETapi-v1-surat-download">index</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-v1-surat-download">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://opendk.test/api/v1/surat/download" \
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
    "http://opendk.test/api/v1/surat/download"
);

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

            </span>

            <span id="example-responses-GETapi-v1-surat-download">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/v1/surat/download could not be found.&quot;
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

            <h2 id="endpoints-GETapi-frontend-v1-artikel">Display a listing of articles with advanced filtering and sorting.</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-artikel">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://opendk.test/api/frontend/v1/artikel" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/artikel"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-artikel">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/artikel could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/artikel/16/comments" \
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
    "http://opendk.test/api/frontend/v1/artikel/16/comments"
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
                    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-frontend-v1-artikel--id--comments" data-initial-text="Send Request 💥" data-loading-text="⏱ Sending..." hidden>Send Request
                        💥
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
                        value="16"
                        data-component="url"
                    >
                    <br>
                    <p>The ID of the artikel. Example: <code>16</code></p>
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
                    <p>The <code>id</code> of an existing record in the das_artikel_comment table. Example: <code>16</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-artikel-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-artikel-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://opendk.test/api/frontend/v1/artikel/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/artikel/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/kategori" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/kategori"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-kategori">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/kategori could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/kategori/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/kategori/cache/architecto"
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

            <h2 id="endpoints-GETapi-frontend-v1-website">GET api/frontend/v1/website</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-website">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://opendk.test/api/frontend/v1/website" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/website"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-website">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/website could not be found.&quot;
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
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-website-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-website-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://opendk.test/api/frontend/v1/website/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/website/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/profil" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/profil"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-profil">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/profil could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/profil/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/profil/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/desa" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/desa"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-desa">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/desa could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/desa/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/desa/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/statistik-penduduk" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik-penduduk"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-penduduk">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik-penduduk could not be found.&quot;
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

            <h2 id="endpoints-GETapi-frontend-v1-statistik-penduduk-listYear">GET api/frontend/v1/statistik-penduduk/listYear</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-penduduk-listYear">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://opendk.test/api/frontend/v1/statistik-penduduk/listYear" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik-penduduk/listYear"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-penduduk-listYear">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik-penduduk/listYear could not be found.&quot;
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

            <h2 id="endpoints-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://opendk.test/api/frontend/v1/statistik-penduduk/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik-penduduk/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/komplain" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/komplain"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-komplain">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/komplain could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/komplain" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "nik=4326.41688"\
    --form "judul=m"\
    --form "kategori=architecto"\
    --form "laporan=architecto"\
    --form "tanggal_lahir=2026-02-18T16:39:16"\
    --form "anonim="\
    --form "lampiran1=@C:\Users\habib\AppData\Local\Temp\php227C.tmp" \
    --form "lampiran2=@C:\Users\habib\AppData\Local\Temp\php228D.tmp" \
    --form "lampiran3=@C:\Users\habib\AppData\Local\Temp\php228E.tmp" \
    --form "lampiran4=@C:\Users\habib\AppData\Local\Temp\php228F.tmp" </code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/komplain"
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
body.append('tanggal_lahir', '2026-02-18T16:39:16');
body.append('anonim', '');
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
                    <input type="text" style="display: none" name="tanggal_lahir" data-endpoint="POSTapi-frontend-v1-komplain" value="2026-02-18T16:39:16" data-component="body">
                    <br>
                    <p>Isian value bukan tanggal yang valid. Example: <code>2026-02-18T16:39:16</code></p>
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
                    <p>Example: <code>false</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lampiran1</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="lampiran1" data-endpoint="POSTapi-frontend-v1-komplain" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php227C.tmp</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lampiran2</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="lampiran2" data-endpoint="POSTapi-frontend-v1-komplain" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php228D.tmp</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lampiran3</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="lampiran3" data-endpoint="POSTapi-frontend-v1-komplain" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php228E.tmp</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lampiran4</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    &nbsp;
                    <input type="file" style="display: none" name="lampiran4" data-endpoint="POSTapi-frontend-v1-komplain" value="" data-component="body">
                    <br>
                    <p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>C:\Users\habib\AppData\Local\Temp\php228F.tmp</code></p>
                </div>
            </form>

            <h2 id="endpoints-DELETEapi-frontend-v1-komplain-cache--prefix--">Remove all cache entries with the specified prefix</h2>

            <p>
            </p>

            <span id="example-requests-DELETEapi-frontend-v1-komplain-cache--prefix--">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://opendk.test/api/frontend/v1/komplain/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/komplain/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/galeri" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/galeri"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-galeri">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/galeri could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/galeri/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/galeri/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/album" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/album"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-album">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/album could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/album/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/album/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/potensi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/potensi"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-potensi">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/potensi could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/potensi/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/potensi/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/form-dokumen" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/form-dokumen"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-form-dokumen">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/form-dokumen could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/form-dokumen/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/form-dokumen/cache/architecto"
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
    --get "http://opendk.test/api/frontend/v1/regulasi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/regulasi"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-regulasi">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/regulasi could not be found.&quot;
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
    "http://opendk.test/api/frontend/v1/regulasi/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/regulasi/cache/architecto"
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

            <h2 id="endpoints-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan">GET api/frontend/v1/statistik/chart-tingkat-pendidikan</h2>

            <p>
            </p>

            <span id="example-requests-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan">
                <blockquote>Example request:</blockquote>

                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://opendk.test/api/frontend/v1/statistik/chart-tingkat-pendidikan" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-tingkat-pendidikan"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-tingkat-pendidikan">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-tingkat-pendidikan could not be found.&quot;
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-putus-sekolah" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-putus-sekolah"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-putus-sekolah">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-putus-sekolah could not be found.&quot;
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-fasilitas-paud" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-fasilitas-paud"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-fasilitas-paud">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-fasilitas-paud could not be found.&quot;
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-akiakb" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-akiakb"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-akiakb">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-akiakb could not be found.&quot;
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-imunisasi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-imunisasi"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-imunisasi">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-imunisasi could not be found.&quot;
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-penyakit" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-penyakit"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-penyakit">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-penyakit could not be found.&quot;
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-sanitasi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-sanitasi"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-sanitasi">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-sanitasi could not be found.&quot;
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-penduduk" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-penduduk"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-penduduk">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-penduduk could not be found.&quot;
}</code>
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-keluarga" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-keluarga"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-keluarga">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-keluarga could not be found.&quot;
}</code>
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-anggaran-realisasi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-anggaran-realisasi"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-anggaran-realisasi">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-anggaran-realisasi could not be found.&quot;
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
    --get "http://opendk.test/api/frontend/v1/statistik/chart-anggaran-desa" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/statistik/chart-anggaran-desa"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-statistik-chart-anggaran-desa">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/statistik/chart-anggaran-desa could not be found.&quot;
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
    --get "http://opendk.test/api/frontend/v1/faq" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>

                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://opendk.test/api/frontend/v1/faq"
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

            </span>

            <span id="example-responses-GETapi-frontend-v1-faq">
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/frontend/v1/faq could not be found.&quot;
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

        </div>
        <div class="dark-box">
            <div class="lang-selector">
                <button type="button" class="lang-button" data-language-name="bash">bash</button>
                <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
            </div>
        </div>
    </div>
</body>

</html>
