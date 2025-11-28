<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>OpenDK API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.5.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.5.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
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
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-frontend-v1-artikel">
                                <a href="#endpoints-GETapi-frontend-v1-artikel">Display a listing of articles with advanced filtering and sorting.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-frontend-v1-artikel--id--comments">
                                <a href="#endpoints-POSTapi-frontend-v1-artikel--id--comments">Store a new comment for an article.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-frontend-v1-artikel-cache--prefix--">
                                <a href="#endpoints-DELETEapi-frontend-v1-artikel-cache--prefix--">Remove all cache entries with the specified prefix</a>
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
                                <a href="#endpoints-GETapi-frontend-v1-statistik-penduduk">Display a listing of desa with advanced filtering and sorting.</a>
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
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: November 15, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-frontend-v1-artikel">Display a listing of articles with advanced filtering and sorting.</h2>

<p>
</p>



<span id="example-requests-GETapi-frontend-v1-artikel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/artikel" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

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
 </code></pre></details>         <pre>

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
                &quot;updated_at&quot;: &quot;2025-03-10T22:09:21.000000Z&quot;
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
                &quot;updated_at&quot;: &quot;2025-04-03T17:36:53.000000Z&quot;
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
                &quot;updated_at&quot;: &quot;2025-01-06T09:55:59.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;4&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: 1,
                &quot;slug&quot;: &quot;doloribus-unde-ut-voluptatem-doloribus&quot;,
                &quot;judul&quot;: &quot;Doloribus unde ut voluptatem doloribus.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;&lt;p&gt;Itaque eaque beatae ut error nisi fuga iste. Sunt nesciunt qui id aut reprehenderit aut suscipit. Aut omnis id recusandae iure reprehenderit dolorem illo ut.&lt;/p&gt;&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-01-30T16:36:43.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-11-10T07:02:13.000000Z&quot;
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
                &quot;updated_at&quot;: &quot;2025-04-02T20:10:13.000000Z&quot;
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
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Non fuga facilis quia et praesentium. Minus voluptatem adipisci aspernatur mollitia. In et magnam deserunt voluptates eveniet.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-04-08T17:33:56.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-03-03T13:16:38.000000Z&quot;
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
                &quot;updated_at&quot;: &quot;2025-01-16T10:01:14.000000Z&quot;
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
                &quot;updated_at&quot;: &quot;2025-01-20T20:53:41.000000Z&quot;
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
                &quot;updated_at&quot;: &quot;2025-01-27T08:47:27.000000Z&quot;
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
                &quot;updated_at&quot;: &quot;2025-04-08T06:00:44.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;11&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;et-autem-labore-sit-est-et-voluptatum-necessitatibus&quot;,
                &quot;judul&quot;: &quot;Et autem labore sit est et voluptatum necessitatibus.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Illum quo omnis nam. Iste alias sed harum provident nesciunt quam. Accusantium sed dolores ad ipsum qui dolorem. Omnis libero nam cupiditate sint non praesentium et.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-02-09T01:45:55.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-02-04T22:06:21.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;12&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;ut-et-corrupti-et&quot;,
                &quot;judul&quot;: &quot;Ut et corrupti et.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Esse saepe nihil nihil eos ea harum. Reiciendis esse non perferendis quia praesentium dolore vero. Eum sit sed aut natus sequi ea a. Vel aliquam ut rerum ut perferendis aut corrupti.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-01-12T23:51:59.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-03-12T17:38:27.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;13&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;ut-voluptatibus-sunt-aut-illum-optio-voluptas-consequuntur-harum&quot;,
                &quot;judul&quot;: &quot;Ut voluptatibus sunt aut illum optio voluptas consequuntur harum.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;In rerum et dolor facere quae rem dignissimos. Et ducimus voluptatem minus voluptatem. Expedita nemo voluptates pariatur rerum quia quidem ut. Itaque magnam ut quia consequatur sunt quis earum.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-04-02T15:20:58.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-02-10T05:49:41.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;14&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;inventore-vel-est-eum-rem&quot;,
                &quot;judul&quot;: &quot;Inventore vel est eum rem.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Iure ex nulla voluptates occaecati quo voluptatem sint. Aspernatur reprehenderit reprehenderit rem fugiat aut. Et delectus dolor explicabo ab. Quia eius saepe at ducimus.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-02-19T11:31:58.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-01-02T20:25:01.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;15&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;asperiores-et-voluptatem-quia-ducimus&quot;,
                &quot;judul&quot;: &quot;Asperiores et voluptatem quia ducimus.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Voluptatum aut aut molestiae et dolores. Qui debitis voluptatem deserunt rerum sint et et quo. Iusto et blanditiis tenetur nisi consectetur adipisci quas.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-04-14T07:49:52.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-03-05T03:06:30.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;16&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;officiis-pariatur-optio-nihil-voluptates-omnis&quot;,
                &quot;judul&quot;: &quot;Officiis pariatur optio nihil voluptates omnis.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Praesentium provident placeat beatae aut. Aut dicta quisquam perspiciatis explicabo odit. Rem eum sunt omnis quia necessitatibus debitis. Deserunt id nisi voluptas.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-02-23T22:37:37.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-02-19T00:55:38.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;17&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;facilis-ex-quidem-aliquam-rerum-deleniti-esse&quot;,
                &quot;judul&quot;: &quot;Facilis ex quidem aliquam rerum deleniti esse.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Est ipsa doloribus sint quidem. Est dolores id error omnis. Magnam veniam facere expedita. Modi iste at nam.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-02-18T08:23:00.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-05T08:44:07.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;18&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;id-quisquam-architecto-nam-quis-quibusdam&quot;,
                &quot;judul&quot;: &quot;Id quisquam architecto nam quis quibusdam.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Repudiandae animi iste reprehenderit commodi libero qui. Repellat dolores aut et in deleniti harum. Facilis aliquam dolorum et harum molestias assumenda. Ut omnis sed consequatur sunt accusantium eum.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-01-14T20:50:21.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-02-12T08:27:34.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;19&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;fugiat-et-earum-natus-recusandae-non&quot;,
                &quot;judul&quot;: &quot;Fugiat et earum natus recusandae non.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Nam aut eligendi aut recusandae aut cupiditate temporibus. Mollitia quos ducimus accusantium. Minima et veniam minima porro natus consequatur id. Voluptas distinctio consectetur voluptatem eaque perspiciatis sunt.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-01-08T11:03:10.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-07T05:46:44.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;20&quot;,
            &quot;attributes&quot;: {
                &quot;id_kategori&quot;: null,
                &quot;slug&quot;: &quot;ea-dolorem-eligendi-est&quot;,
                &quot;judul&quot;: &quot;Ea dolorem eligendi est.&quot;,
                &quot;kategori_id&quot;: null,
                &quot;gambar&quot;: &quot;/storage/artikel//img/no-image.png&quot;,
                &quot;isi&quot;: &quot;Eum ex odit maxime corrupti eos est. Excepturi at facilis consectetur in repellendus. Debitis expedita rerum eveniet molestiae. Nostrum rerum eius quod velit.&quot;,
                &quot;status&quot;: 1,
                &quot;created_at&quot;: &quot;2025-04-08T06:31:56.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-03-10T14:08:53.000000Z&quot;
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 20,
            &quot;count&quot;: 20,
            &quot;per_page&quot;: 30,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1
        }
    },
    &quot;links&quot;: {
        &quot;self&quot;: &quot;http://localhost:8000/api/frontend/v1/artikel?page%5Bnumber%5D=1&quot;,
        &quot;first&quot;: &quot;http://localhost:8000/api/frontend/v1/artikel?page%5Bnumber%5D=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/frontend/v1/artikel?page%5Bnumber%5D=1&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-frontend-v1-artikel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-frontend-v1-artikel"></span>:
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
<form id="form-GETapi-frontend-v1-artikel" data-method="GET"
      data-path="api/frontend/v1/artikel"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-artikel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-frontend-v1-artikel"
                    onclick="tryItOut('GETapi-frontend-v1-artikel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-frontend-v1-artikel"
                    onclick="cancelTryOut('GETapi-frontend-v1-artikel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-frontend-v1-artikel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-frontend-v1-artikel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-frontend-v1-artikel"
               value="application/json"
               data-component="header">
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
</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-frontend-v1-artikel--id--comments">
</span>
<span id="execution-results-POSTapi-frontend-v1-artikel--id--comments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-frontend-v1-artikel--id--comments"></span>:
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
<form id="form-POSTapi-frontend-v1-artikel--id--comments" data-method="POST"
      data-path="api/frontend/v1/artikel/{id}/comments"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-frontend-v1-artikel--id--comments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-frontend-v1-artikel--id--comments"
                    onclick="tryItOut('POSTapi-frontend-v1-artikel--id--comments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-frontend-v1-artikel--id--comments"
                    onclick="cancelTryOut('POSTapi-frontend-v1-artikel--id--comments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-frontend-v1-artikel--id--comments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-frontend-v1-artikel--id--comments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-frontend-v1-artikel--id--comments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-frontend-v1-artikel--id--comments"
               value="1"
               data-component="url">
    <br>
<p>The ID of the artikel. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nama</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nama"                data-endpoint="POSTapi-frontend-v1-artikel--id--comments"
               value="b"
               data-component="body">
    <br>
<p>Isian value seharusnya tidak lebih dari 191 karakter. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-frontend-v1-artikel--id--comments"
               value="zbailey@example.net"
               data-component="body">
    <br>
<p>Isian value harus berupa alamat surel yang valid. Isian value seharusnya tidak lebih dari 191 karakter. Example: <code>zbailey@example.net</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>body</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="body"                data-endpoint="POSTapi-frontend-v1-artikel--id--comments"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>comment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="comment_id"                data-endpoint="POSTapi-frontend-v1-artikel--id--comments"
               value="16"
               data-component="body">
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
    "http://localhost:8000/api/frontend/v1/artikel/cache/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-frontend-v1-artikel-cache--prefix--">
</span>
<span id="execution-results-DELETEapi-frontend-v1-artikel-cache--prefix--" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-frontend-v1-artikel-cache--prefix--"></span>:
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
<form id="form-DELETEapi-frontend-v1-artikel-cache--prefix--" data-method="DELETE"
      data-path="api/frontend/v1/artikel/cache/{prefix?}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-artikel-cache--prefix--', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-frontend-v1-artikel-cache--prefix--"
                    onclick="tryItOut('DELETEapi-frontend-v1-artikel-cache--prefix--');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-frontend-v1-artikel-cache--prefix--"
                    onclick="cancelTryOut('DELETEapi-frontend-v1-artikel-cache--prefix--');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-frontend-v1-artikel-cache--prefix--"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-frontend-v1-artikel-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-frontend-v1-artikel-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="prefix"                data-endpoint="DELETEapi-frontend-v1-artikel-cache--prefix--"
               value="architecto"
               data-component="url">
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
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

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
x-ratelimit-remaining: 118
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: null,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;provinsi_id&quot;: &quot;51&quot;,
                &quot;nama_provinsi&quot;: &quot;BALI&quot;,
                &quot;kabupaten_id&quot;: &quot;51.02&quot;,
                &quot;nama_kabupaten&quot;: &quot;TABANAN&quot;,
                &quot;kecamatan_id&quot;: &quot;51.02.06&quot;,
                &quot;nama_kecamatan&quot;: &quot;Kediri&quot;,
                &quot;alamat&quot;: &quot;Jl. Koperasi No. 1, Kab Tabanan &quot;,
                &quot;kode_pos&quot;: &quot;83653&quot;,
                &quot;telepon&quot;: &quot;0212345234&quot;,
                &quot;email&quot;: &quot;admin@mail.com&quot;,
                &quot;tahun_pembentukan&quot;: 2025,
                &quot;dasar_pembentukan&quot;: &quot;PEREGUB No 4 1990&quot;,
                &quot;nama_camat&quot;: &quot;H. Hadi Fathurrahman, S.Sos, M.AP&quot;,
                &quot;sekretaris_camat&quot;: &quot;Drs. Zaenal Abidin&quot;,
                &quot;kepsek_pemerintahan_umum&quot;: &quot;Musyayad, S.Sos&quot;,
                &quot;kepsek_kesejahteraan_masyarakat&quot;: &quot;Suhartono, S.Sos&quot;,
                &quot;kepsek_pemberdayaan_masyarakat&quot;: &quot;Asrarudin, SE&quot;,
                &quot;kepsek_pelayanan_umum&quot;: &quot;Masturi, ST&quot;,
                &quot;kepsek_trantib&quot;: &quot;Mastur Idris, SH&quot;,
                &quot;file_struktur_organisasi&quot;: null,
                &quot;file_logo&quot;: null,
                &quot;visi&quot;: &quot;&lt;p&gt;Ini adalah kalimat visi&lt;/p&gt;&quot;,
                &quot;misi&quot;: &quot;&lt;p&gt;Ini adalah kalimat visi&lt;/p&gt;&quot;,
                &quot;foto_kepala_wilayah&quot;: null,
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;sambutan&quot;: null
            }
        }
    ],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 1,
            &quot;count&quot;: 1,
            &quot;per_page&quot;: 30,
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
    <blockquote>Received response<span
                id="execution-response-status-GETapi-frontend-v1-profil"></span>:
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
<form id="form-GETapi-frontend-v1-profil" data-method="GET"
      data-path="api/frontend/v1/profil"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-profil', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-frontend-v1-profil"
                    onclick="tryItOut('GETapi-frontend-v1-profil');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-frontend-v1-profil"
                    onclick="cancelTryOut('GETapi-frontend-v1-profil');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-frontend-v1-profil"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-frontend-v1-profil"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-frontend-v1-profil"
               value="application/json"
               data-component="header">
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
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-frontend-v1-profil-cache--prefix--">
</span>
<span id="execution-results-DELETEapi-frontend-v1-profil-cache--prefix--" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-frontend-v1-profil-cache--prefix--"></span>:
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
<form id="form-DELETEapi-frontend-v1-profil-cache--prefix--" data-method="DELETE"
      data-path="api/frontend/v1/profil/cache/{prefix?}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-profil-cache--prefix--', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-frontend-v1-profil-cache--prefix--"
                    onclick="tryItOut('DELETEapi-frontend-v1-profil-cache--prefix--');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-frontend-v1-profil-cache--prefix--"
                    onclick="cancelTryOut('DELETEapi-frontend-v1-profil-cache--prefix--');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-frontend-v1-profil-cache--prefix--"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-frontend-v1-profil-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-frontend-v1-profil-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="prefix"                data-endpoint="DELETEapi-frontend-v1-profil-cache--prefix--"
               value="architecto"
               data-component="url">
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
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

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
x-ratelimit-remaining: 117
x-ratelimit-reset: 60
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2001&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2001&quot;,
                &quot;nama&quot;: &quot;Bedalewun&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Bedalewun&quot;,
                &quot;website&quot;: &quot;https://bana.opendesa.id/&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2001&quot;,
                    &quot;nama&quot;: &quot;Desa Bedalewun&quot;,
                    &quot;website&quot;: &quot;https://bana.opendesa.id/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;2&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2002&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2002&quot;,
                &quot;nama&quot;: &quot;Lebanuba&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Lebanuba&quot;,
                &quot;website&quot;: &quot;https://natairaya.com/&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2002&quot;,
                    &quot;nama&quot;: &quot;Desa Lebanuba&quot;,
                    &quot;website&quot;: &quot;https://natairaya.com/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;3&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2003&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2003&quot;,
                &quot;nama&quot;: &quot;Rianwale&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Rianwale&quot;,
                &quot;website&quot;: &quot;https://bantal.desa.id/&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2003&quot;,
                    &quot;nama&quot;: &quot;Desa Rianwale&quot;,
                    &quot;website&quot;: &quot;https://bantal.desa.id/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;4&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2004&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2004&quot;,
                &quot;nama&quot;: &quot;Bungalawan&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Bungalawan&quot;,
                &quot;website&quot;: &quot;https://berputar.opensid.or.id/&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2004&quot;,
                    &quot;nama&quot;: &quot;Desa Bungalawan&quot;,
                    &quot;website&quot;: &quot;https://berputar.opensid.or.id/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;5&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2005&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2005&quot;,
                &quot;nama&quot;: &quot;Lamawolo&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Lamawolo&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2005&quot;,
                    &quot;nama&quot;: &quot;Desa Lamawolo&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;6&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2006&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2006&quot;,
                &quot;nama&quot;: &quot;Helanlangowuyo&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Helanlangowuyo&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2006&quot;,
                    &quot;nama&quot;: &quot;Desa Helanlangowuyo&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;7&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2007&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2007&quot;,
                &quot;nama&quot;: &quot;Lewopao&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Lewopao&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2007&quot;,
                    &quot;nama&quot;: &quot;Desa Lewopao&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;8&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2008&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2008&quot;,
                &quot;nama&quot;: &quot;Nelereren&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Nelereren&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2008&quot;,
                    &quot;nama&quot;: &quot;Desa Nelereren&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;9&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2009&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2009&quot;,
                &quot;nama&quot;: &quot;Boleng&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Boleng&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2009&quot;,
                    &quot;nama&quot;: &quot;Desa Boleng&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;10&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2010&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2010&quot;,
                &quot;nama&quot;: &quot;Neleblolong&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Neleblolong&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2010&quot;,
                    &quot;nama&quot;: &quot;Desa Neleblolong&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;11&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2011&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2011&quot;,
                &quot;nama&quot;: &quot;Duablolong&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Duablolong&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2011&quot;,
                    &quot;nama&quot;: &quot;Desa Duablolong&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;12&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2012&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2012&quot;,
                &quot;nama&quot;: &quot;Lewokeleng&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Lewokeleng&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2012&quot;,
                    &quot;nama&quot;: &quot;Desa Lewokeleng&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;13&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2013&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2013&quot;,
                &quot;nama&quot;: &quot;Nelelamawangi&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Nelelamawangi&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2013&quot;,
                    &quot;nama&quot;: &quot;Desa Nelelamawangi&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;14&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2014&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2014&quot;,
                &quot;nama&quot;: &quot;Harubala&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Harubala&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2014&quot;,
                    &quot;nama&quot;: &quot;Desa Harubala&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;15&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2015&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2015&quot;,
                &quot;nama&quot;: &quot;Nelelamadike&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Nelelamadike&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2015&quot;,
                    &quot;nama&quot;: &quot;Desa Nelelamadike&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;16&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2016&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2016&quot;,
                &quot;nama&quot;: &quot;Lamabayung&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Lamabayung&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2016&quot;,
                    &quot;nama&quot;: &quot;Desa Lamabayung&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;17&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2017&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2017&quot;,
                &quot;nama&quot;: &quot;Lewat&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Lewat&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2017&quot;,
                    &quot;nama&quot;: &quot;Desa Lewat&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;18&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2018&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2018&quot;,
                &quot;nama&quot;: &quot;Dokeng&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Dokeng&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2018&quot;,
                    &quot;nama&quot;: &quot;Desa Dokeng&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;19&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2019&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2019&quot;,
                &quot;nama&quot;: &quot;Bayuntaa&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Bayuntaa&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2019&quot;,
                    &quot;nama&quot;: &quot;Desa Bayuntaa&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;20&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2020&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2020&quot;,
                &quot;nama&quot;: &quot;Nobo&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Nobo&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2020&quot;,
                    &quot;nama&quot;: &quot;Desa Nobo&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        },
        {
            &quot;type&quot;: &quot;desa&quot;,
            &quot;id&quot;: &quot;21&quot;,
            &quot;attributes&quot;: {
                &quot;desa_id&quot;: &quot;53.06.13.2021&quot;,
                &quot;kode_desa&quot;: &quot;53.06.13.2021&quot;,
                &quot;nama&quot;: &quot;Nelelamawangi Dua&quot;,
                &quot;sebutan_desa&quot;: &quot;desa&quot;,
                &quot;nama_lengkap&quot;: &quot;Desa Nelelamawangi Dua&quot;,
                &quot;website&quot;: &quot;&quot;,
                &quot;website_url_feed&quot;: {
                    &quot;desa_id&quot;: &quot;53.06.13.2021&quot;,
                    &quot;nama&quot;: &quot;Desa Nelelamawangi Dua&quot;,
                    &quot;website&quot;: &quot;/index.php/feed&quot;
                },
                &quot;luas_wilayah&quot;: 0,
                &quot;peta&quot;: {
                    &quot;path&quot;: null
                },
                &quot;created_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-04-23T01:02:12.000000Z&quot;
            }
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-frontend-v1-desa" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-frontend-v1-desa"></span>:
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
<form id="form-GETapi-frontend-v1-desa" data-method="GET"
      data-path="api/frontend/v1/desa"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-desa', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-frontend-v1-desa"
                    onclick="tryItOut('GETapi-frontend-v1-desa');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-frontend-v1-desa"
                    onclick="cancelTryOut('GETapi-frontend-v1-desa');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-frontend-v1-desa"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-frontend-v1-desa"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-frontend-v1-desa"
               value="application/json"
               data-component="header">
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
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-frontend-v1-desa-cache--prefix--">
</span>
<span id="execution-results-DELETEapi-frontend-v1-desa-cache--prefix--" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-frontend-v1-desa-cache--prefix--"></span>:
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
<form id="form-DELETEapi-frontend-v1-desa-cache--prefix--" data-method="DELETE"
      data-path="api/frontend/v1/desa/cache/{prefix?}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-desa-cache--prefix--', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-frontend-v1-desa-cache--prefix--"
                    onclick="tryItOut('DELETEapi-frontend-v1-desa-cache--prefix--');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-frontend-v1-desa-cache--prefix--"
                    onclick="cancelTryOut('DELETEapi-frontend-v1-desa-cache--prefix--');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-frontend-v1-desa-cache--prefix--"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-frontend-v1-desa-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-frontend-v1-desa-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="prefix"                data-endpoint="DELETEapi-frontend-v1-desa-cache--prefix--"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-frontend-v1-statistik-penduduk">Display a listing of desa with advanced filtering and sorting.</h2>

<p>
</p>



<span id="example-requests-GETapi-frontend-v1-statistik-penduduk">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/frontend/v1/statistik-penduduk" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

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
x-ratelimit-remaining: 116
x-ratelimit-reset: 59
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;type&quot;: &quot;statistik-penduduk&quot;,
            &quot;id&quot;: &quot;1&quot;,
            &quot;attributes&quot;: {
                &quot;yearList&quot;: [
                    2019,
                    2020,
                    2021,
                    2022,
                    2023,
                    2024,
                    2025
                ],
                &quot;dashboard&quot;: {
                    &quot;total_penduduk&quot;: 96,
                    &quot;total_lakilaki&quot;: 45,
                    &quot;total_perempuan&quot;: 51,
                    &quot;total_disabilitas&quot;: 0,
                    &quot;ktp_wajib&quot;: 88,
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
                            &quot;year&quot;: 2019,
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
                            &quot;value&quot;: 9,
                            &quot;color&quot;: &quot;#09faff&quot;
                        },
                        {
                            &quot;umur&quot;: &quot;Remaja (15 - 24 tahun)&quot;,
                            &quot;value&quot;: 11,
                            &quot;color&quot;: &quot;#09e5ff&quot;
                        },
                        {
                            &quot;umur&quot;: &quot;Dewasa (25 - 44 tahun)&quot;,
                            &quot;value&quot;: 51,
                            &quot;color&quot;: &quot;#09d1ff&quot;
                        },
                        {
                            &quot;umur&quot;: &quot;Tua (45 - 74 tahun)&quot;,
                            &quot;value&quot;: 22,
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
                            &quot;year&quot;: &quot;2025&quot;,
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
    <blockquote>Received response<span
                id="execution-response-status-GETapi-frontend-v1-statistik-penduduk"></span>:
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
<form id="form-GETapi-frontend-v1-statistik-penduduk" data-method="GET"
      data-path="api/frontend/v1/statistik-penduduk"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-statistik-penduduk', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-frontend-v1-statistik-penduduk"
                    onclick="tryItOut('GETapi-frontend-v1-statistik-penduduk');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-frontend-v1-statistik-penduduk"
                    onclick="cancelTryOut('GETapi-frontend-v1-statistik-penduduk');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-frontend-v1-statistik-penduduk"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-frontend-v1-statistik-penduduk"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-frontend-v1-statistik-penduduk"
               value="application/json"
               data-component="header">
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
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--">
</span>
<span id="execution-results-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"></span>:
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
<form id="form-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--" data-method="DELETE"
      data-path="api/frontend/v1/statistik-penduduk/cache/{prefix?}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
                    onclick="tryItOut('DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
                    onclick="cancelTryOut('DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="prefix"                data-endpoint="DELETEapi-frontend-v1-statistik-penduduk-cache--prefix--"
               value="architecto"
               data-component="url">
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
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

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
x-ratelimit-remaining: 115
x-ratelimit-reset: 59
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;pagination&quot;: {
            &quot;total&quot;: 0,
            &quot;count&quot;: 0,
            &quot;per_page&quot;: 30,
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
    <blockquote>Received response<span
                id="execution-response-status-GETapi-frontend-v1-komplain"></span>:
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
<form id="form-GETapi-frontend-v1-komplain" data-method="GET"
      data-path="api/frontend/v1/komplain"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-frontend-v1-komplain', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-frontend-v1-komplain"
                    onclick="tryItOut('GETapi-frontend-v1-komplain');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-frontend-v1-komplain"
                    onclick="cancelTryOut('GETapi-frontend-v1-komplain');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-frontend-v1-komplain"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-frontend-v1-komplain"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-frontend-v1-komplain"
               value="application/json"
               data-component="header">
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
    --form "tanggal_lahir=2025-11-15T10:20:15"\
    --form "anonim="\
    --form "lampiran1=@/tmp/php2ch558oeej7q8kema2V" \
    --form "lampiran2=@/tmp/phpnbqvjq1u837rfB46qpb" \
    --form "lampiran3=@/tmp/php08hg9qu83kmn8okPvWK" \
    --form "lampiran4=@/tmp/php5v3htp5sdt4i7qvqDHR" </code></pre></div>


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
body.append('tanggal_lahir', '2025-11-15T10:20:15');
body.append('anonim', '');
body.append('lampiran1', document.querySelector('input[name="lampiran1"]').files[0]);
body.append('lampiran2', document.querySelector('input[name="lampiran2"]').files[0]);
body.append('lampiran3', document.querySelector('input[name="lampiran3"]').files[0]);
body.append('lampiran4', document.querySelector('input[name="lampiran4"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-frontend-v1-komplain">
</span>
<span id="execution-results-POSTapi-frontend-v1-komplain" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-frontend-v1-komplain"></span>:
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
<form id="form-POSTapi-frontend-v1-komplain" data-method="POST"
      data-path="api/frontend/v1/komplain"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-frontend-v1-komplain', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-frontend-v1-komplain"
                    onclick="tryItOut('POSTapi-frontend-v1-komplain');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-frontend-v1-komplain"
                    onclick="cancelTryOut('POSTapi-frontend-v1-komplain');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-frontend-v1-komplain"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-frontend-v1-komplain"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-frontend-v1-komplain"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="nik"                data-endpoint="POSTapi-frontend-v1-komplain"
               value="4326.41688"
               data-component="body">
    <br>
<p>Example: <code>4326.41688</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="judul"                data-endpoint="POSTapi-frontend-v1-komplain"
               value="m"
               data-component="body">
    <br>
<p>Isian value seharusnya tidak lebih dari 255 karakter. Example: <code>m</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="kategori"                data-endpoint="POSTapi-frontend-v1-komplain"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>laporan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="laporan"                data-endpoint="POSTapi-frontend-v1-komplain"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tanggal_lahir</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tanggal_lahir"                data-endpoint="POSTapi-frontend-v1-komplain"
               value="2025-11-15T10:20:15"
               data-component="body">
    <br>
<p>Isian value bukan tanggal yang valid. Example: <code>2025-11-15T10:20:15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>anonim</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-frontend-v1-komplain" style="display: none">
            <input type="radio" name="anonim"
                   value="true"
                   data-endpoint="POSTapi-frontend-v1-komplain"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-frontend-v1-komplain" style="display: none">
            <input type="radio" name="anonim"
                   value="false"
                   data-endpoint="POSTapi-frontend-v1-komplain"
                   data-component="body"             >
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
                <input type="file" style="display: none"
                              name="lampiran1"                data-endpoint="POSTapi-frontend-v1-komplain"
               value=""
               data-component="body">
    <br>
<p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>/tmp/php2ch558oeej7q8kema2V</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lampiran2</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="lampiran2"                data-endpoint="POSTapi-frontend-v1-komplain"
               value=""
               data-component="body">
    <br>
<p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>/tmp/phpnbqvjq1u837rfB46qpb</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lampiran3</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="lampiran3"                data-endpoint="POSTapi-frontend-v1-komplain"
               value=""
               data-component="body">
    <br>
<p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>/tmp/php08hg9qu83kmn8okPvWK</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lampiran4</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="lampiran4"                data-endpoint="POSTapi-frontend-v1-komplain"
               value=""
               data-component="body">
    <br>
<p>Must be a file. Isian value seharusnya tidak lebih dari 1024 kilobytes. Example: <code>/tmp/php5v3htp5sdt4i7qvqDHR</code></p>
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
    --header "Accept: application/json"</code></pre></div>


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
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-frontend-v1-komplain-cache--prefix--">
</span>
<span id="execution-results-DELETEapi-frontend-v1-komplain-cache--prefix--" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-frontend-v1-komplain-cache--prefix--"></span>:
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
<form id="form-DELETEapi-frontend-v1-komplain-cache--prefix--" data-method="DELETE"
      data-path="api/frontend/v1/komplain/cache/{prefix?}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-frontend-v1-komplain-cache--prefix--', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-frontend-v1-komplain-cache--prefix--"
                    onclick="tryItOut('DELETEapi-frontend-v1-komplain-cache--prefix--');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-frontend-v1-komplain-cache--prefix--"
                    onclick="cancelTryOut('DELETEapi-frontend-v1-komplain-cache--prefix--');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-frontend-v1-komplain-cache--prefix--"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
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
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-frontend-v1-komplain-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-frontend-v1-komplain-cache--prefix--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="prefix"                data-endpoint="DELETEapi-frontend-v1-komplain-cache--prefix--"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
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
