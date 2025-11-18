@push('scripts')
<script>
    $(function() {
        $.getJSON('{!! $urlApi !!}/website').done(function(res) {
            // Extract data from the API response
            if (res.data && Array.isArray(res.data)) {
                const websiteData = {};

                // Convert array of objects to key-value pairs
                res.data.forEach(item => {
                    if (item.id && item.attributes) {
                        websiteData[item.id] = item.attributes[0]; // Get first element of attributes array
                    }
                });

                // Store data globally for use by other components
                window.websiteData = websiteData;

                // Update navigation menu
                updateNavigationMenu(websiteData.navmenus);

                // Update slides
                updateSlides(websiteData.slides);

                // Update social media
                updateSocialMedia(websiteData.medsos);

                updateProfile(websiteData.profile[0]);
                // Update desa list
                updateDesaList(websiteData.desa);

                // Update widgets
                updateEvents(websiteData.events);
                updateMediaTerkait(websiteData.media_terkait);
                updateMediaSosial(websiteData.medsos);
                updateSinergiProgram(websiteData.sinergi);
                updatePengurus(websiteData.pengurus);
                updateCounter(websiteData.counter)
                // Trigger custom event to notify other components
                $(document).trigger('websiteDataLoaded', [websiteData]);
            }
        }).fail(function() {
            console.error('Gagal mengambil data website dari API.');
        });

        // Function to update navigation menu
        function updateNavigationMenu(navmenus) {
            if (!navmenus || !Array.isArray(navmenus)) return;

            let navHtml = '';
            navmenus.forEach(nav => {
                const hasChildren = nav.children && nav.children.length > 0;
                navHtml += '<li class="' + (hasChildren ? 'dropdown' : '') + '">' +
                    '<a href="' + (nav.url || '#') + '" target="' + (nav.target || '_self') + '" class="' + (hasChildren ? 'dropdown-toggle' : '') + '" data-toggle="' + (hasChildren ? 'dropdown' : '') + '" role="button" aria-haspopup="true" aria-expanded="false">' +
                    nav.name +
                    (hasChildren ? '<span class="caret"></span>' : '') +
                    '</a>';

                if (hasChildren) {
                    navHtml += '<ul class="dropdown-menu">';
                    nav.children.forEach(child => {
                        const hasGrandChildren = child.children && child.children.length > 0;
                        navHtml += '<li class="' + (hasGrandChildren ? 'dropdown-submenu' : '') + '">' +
                            '<a href="' + (child.url || '#') + '" target="' + (child.target || '_self') + '" tabindex="-1" class="' + (hasGrandChildren ? 'dropdown-submenu-toggle' : '') + '">' +
                            child.name +
                            (hasGrandChildren ? '<span class="caret"></span>' : '') +
                            '</a>';

                        if (hasGrandChildren) {
                            navHtml += '<ul class="dropdown-menu">';
                            child.children.forEach(grandChild => {
                                navHtml += '<li><a href="' + (grandChild.url || '#') + '" target="' + (grandChild.target || '_self') + '">' + grandChild.name + '</a></li>';
                            });
                            navHtml += '</ul>';
                        }
                        navHtml += '</li>';
                    });
                    navHtml += '</ul>';
                }
                navHtml += '</li>';
            });

            // Add auth links
            var loginUrl = "{{ route('login') }}";
            navHtml += '<li><a href="' + loginUrl + '">LogIn</a></li>';

            $('#main-nav-menu').html(navHtml);
        }

        // Function to update slides
        function updateSlides(slides) {
            if (!slides || !Array.isArray(slides)) return;

            let slidesHtml = '';
            slides.forEach(slide => {
                const imageSrc = slide.gambar && slide.gambar.startsWith('http') ? slide.gambar : (slide.gambar ? '{{ asset("") }}' + slide.gambar : '{{ asset("img/placeholder.jpg") }}');
                slidesHtml += '<div class="swiper-slide">' +
                    '<div class="slider-class">' +
                    '<div class="legend"></div>' +
                    '<div class="content-slide">' +
                    '<div class="content-txt">' +
                    '<h1>' + (slide.judul || '') + '</h1>' +
                    '<h2>' + (slide.deskripsi || '') + '</h2>' +
                    '</div>' +
                    '</div>' +
                    '<div class="image">' +
                    '<img src="' + imageSrc + '" alt="' + (slide.judul || '') + '">' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            });

            $('#slides-container').html(slidesHtml);

            // Reinitialize swiper
            if (window.swiper) {
                window.swiper.update();
            } else {
                window.swiper = new Swiper("#swiper-slider", {
                    autoplay: {
                        delay: 4000,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    loop: true,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            }
        }

        // Function to update social media
        function updateSocialMedia(medsos) {
            if (!medsos || !Array.isArray(medsos)) return;

            let socialHtml = '';
            medsos.forEach(sosmed => {
                const iconClass = getSosmedIcon(sosmed.nama);
                sosmed.logo = sosmed.logo.replace(/\/+img\//, 'img/');

                const logoPath = sosmed.logo ? (sosmed.logo.startsWith('http') ? sosmed.logo : '{{ asset("") }}' + sosmed.logo) : '';
                socialHtml += '<li style="margin: 4px">' +
                    '<a href="' + (sosmed.url || '#') + '" rel="noopener noreferrer" target="_blank">' +
                    '<img src="' + logoPath + '" class="logo-medsos" alt="Media Sosial Image">' +
                    '</a>' +
                    '</li>';
            });

            $('#social-media-container ul').html(socialHtml);
        }

        // Function to update desa list
        function updateDesaList(desa) {
            if (!desa || !Array.isArray(desa)) return;

            let desaHtml = '';
            const chunkedDesa = [];
            for (let i = 0; i < desa.length; i += 2) {
                chunkedDesa.push(desa.slice(i, i + 2));
            }

            chunkedDesa.forEach(chunk => {
                chunk.forEach(d => {
                    desaHtml += '<ul class="no-padding">' +
                        '<li class="col-12 col-xs-6 no-padding">' +
                        '<a class="footer-link" href="/desa/' + (d.nama ? d.nama.toLowerCase().replace(/\s+/g, '-') : '') + '">' +
                        '<i class="fa fa-chevron-circle-right"></i>' +
                        (d.sebutan_desa || '') + ' ' + (d.nama || '') +
                        '</a>' +
                        '</li>' +
                        '</ul>';
                });
            });

            $('#desa-list-container ul').html(desaHtml);
        }

        // Helper function to get social media icon class
        function getSosmedIcon(name) {
            const nameLower = name.toLowerCase();
            if (nameLower.includes('facebook')) return 'fa fa-facebook';
            if (nameLower.includes('twitter')) return 'fa fa-twitter';
            if (nameLower.includes('instagram')) return 'fa fa-instagram';
            if (nameLower.includes('youtube')) return 'fa fa-youtube';
            if (nameLower.includes('linkedin')) return 'fa fa-linkedin';
            if (nameLower.includes('whatsapp')) return 'fa fa-whatsapp';
            if (nameLower.includes('telegram')) return 'fa fa-telegram';
            return 'fa fa-link'; // Default icon
        }

        // Function to update events widget
        function updateEvents(events) {
            if (!events) {
                $('#events-widget ul').html('<li class="time-label"><span class="bg-gray">Event tidak tersedia.</span></li>');
                return;
            }

            let eventsHtml = '';
            Object.values(events).forEach(eventGroup => {
                if (Array.isArray(eventGroup)) {
                    eventGroup.forEach(event => {
                        const statusClass = event.status === 'OPEN' ? 'bg-maroon' : 'bg-gray';
                        const eventDate = event.start ? new Date(event.start).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        }) : '';

                        eventsHtml += '<li>' +
                            '<i class="fa fa-calendar ' + statusClass + '"></i>' +
                            '<div class="timeline-item">' +
                            '<h4 class="timeline-header">' +
                            '<a href="' + (event.slug ? '/event/' + event.slug : '#') + '">' + (event.slug || 'Event') + '</a>' +
                            '</h4>' +
                            '<small class="text-yellow"><i class="fa fa-clock-o"></i> ' + eventDate + '</small>' +
                            '</div>' +
                            '</li>' +
                            '<br />';
                    });
                }
            });

            if (eventsHtml === '') {
                eventsHtml = '<li class="time-label"><span class="bg-gray">Event tidak tersedia.</span></li>';
            }

            $('#events-widget ul').html(eventsHtml);
        }

        // Function to update media terkait widget
        function updateMediaTerkait(mediaTerkait) {
            if (!mediaTerkait || !Array.isArray(mediaTerkait)) return;

            const jumlahItem = mediaTerkait.length;
            let mediaHtml = '<div class="marquee-wrapper">' +
                '<div class="marquee-content ' + (jumlahItem > 1 ? 'animate' : '') + '">' +
                '<ul class="marquee-track">';

            // First set of items
            mediaTerkait.forEach(data => {
                const logoPath = data.logo ? (data.logo.startsWith('http') ? data.logo : '{{ asset("media_terkait/") }}' + data.logo) : '';
                mediaHtml += '<li>' +
                    '<a href="' + (data.url || '#') + '" rel="noopener noreferrer" target="_blank">' +
                    '<img src="' + logoPath + '" alt="Logo">' +
                    '</a>' +
                    '</li>';
            });

            // Duplicate for seamless loop if more than 1 item
            if (jumlahItem > 1) {
                mediaTerkait.forEach(data => {
                    const logoPath = data.logo ? (data.logo.startsWith('http') ? data.logo : '{{ asset("media_terkait/") }}' + data.logo) : '';
                    mediaHtml += '<li>' +
                        '<a href="' + (data.url || '#') + '" rel="noopener noreferrer" target="_blank">' +
                        '<img src="' + logoPath + '" alt="Logo">' +
                        '</a>' +
                        '</li>';
                });
            }

            mediaHtml += '</ul></div></div>';

            $('#media-terkait-widget').html(mediaHtml);
        }

        // Function to update media sosial widget
        function updateMediaSosial(medsos) {
            if (!medsos || !Array.isArray(medsos)) return;

            let socialHtml = '<ul style="list-style-type: none; display:flex; padding: 0;">';
            medsos.forEach(data => {
                const logoPath = data.logo ? (data.logo.startsWith('http') ? data.logo : '{{ asset("") }}' + data.logo) : '';
                socialHtml += '<li style="margin: 4px">' +
                    '<a href="' + (data.url || '#') + '" rel="noopener noreferrer" target="_blank">' +
                    '<img src="' + logoPath + '" class="logo-medsos" alt="Media Sosial Image">' +
                    '</a>' +
                    '</li>';
            });
            socialHtml += '</ul>';

            $('#media-sosial-widget').html(socialHtml);
        }

        // Function to update sinergi program widget
        function updateSinergiProgram(sinergi) {
            if (!sinergi || !Array.isArray(sinergi)) return;

            let sinergiHtml = '<div class="row" style="margin: 0">';
            sinergi.forEach(data => {
                data.gambar = data.gambar.replace(/\/+img\//, 'img/');
                const gambarPath = data.gambar ? (data.gambar.startsWith('http') ? data.gambar : '{{ asset("") }}' + data.gambar) : '';
                sinergiHtml += '<div class="col-md-6" style="padding: 4px;">' +
                    '<a href="' + (data.url || '#') + '" rel="noopener noreferrer" target="_blank">' +
                    '<img src="' + gambarPath + '" class="logo-sinergi-program" alt="Sinergi Program Image">' +
                    '</a>' +
                    '</div>';
            });
            sinergiHtml += '</div>';

            $('#sinergi-program-widget').html(sinergiHtml);
        }

        function updateProfile(profile) {
            if (!profile) return;
            let profileHtml = `<h5 class="text-bold">Kantor {{ $sebutan_wilayah }} ${profile.nama_kecamatan}</h5>
            <ul class="no-padding">                
                <li> <small style="text-indent: 0px; font-size:15px"><i class="fa fa-map-marker"></i>
                        ${profile.alamat}</small></li>
                <li><small style="text-indent: 0px ;font-size:15px"><i class="fa fa-fax"></i>
                        ${profile.kode_pos}</small></li>
                <li><small style="text-indent: 0px ;font-size:15px"><a href="mailto:${profile.email}"
                            target="_blank"><i class="fa fa-envelope"></i> ${profile.email}</a></small></li>
                <li><small style="text-indent: 0px ;font-size:15px"><i class="fa fa-phone"></i>
                        ${profile.telepon}</small></li>
            </ul>`;
            
            $('#desa-profil-container').html(profileHtml)
        }
        // Function to update pengurus widget
        function updatePengurus(pengurus) {
            if (!pengurus || !Array.isArray(pengurus)) return;

            let pengurusHtml = '<div id="swiper-pengurus" class="swiper">' +
                '<div class="swiper-wrapper">';

            pengurus.forEach(item => {
                const fotoPath = item.foto ? (item.foto.startsWith('http') ? item.foto : '{{ asset("") }}' + item.foto) : '{{ asset("img/no-profile.png") }}';
                const jabatanNama = item.jabatan && typeof item.jabatan === 'object' ? item.jabatan.nama : (item.jabatan_nama || '');

                pengurusHtml += '<div class="swiper-slide">' +
                    '<div class="pad text-bold bg-white" style="text-align:center;">' +
                    '<img src="' + fotoPath + '" width="auto" height="400px" class="img-user" style="max-height: 256px; object-fit: contain; width: 250px;">' +
                    '</div>' +
                    '<div class="box-header text-center with-border bg-blue">' +
                    '<h2 class="box-title text-bold" data-toggle="tooltip" data-placement="top">' +
                    (item.namaGelar || '') + '<br />' +
                    '<span style="font-size: 14px;color: #ecf0f5;">' + jabatanNama + '</span>' +
                    '</h2>' +
                    '</div>' +
                    '</div>';
            });

            pengurusHtml += '</div>' +
                '<!-- Add Arrows -->' +
                '<div class="swiper-button-next"></div>' +
                '<div class="swiper-button-prev"></div>' +
                '</div>';

            $('#pengurus-widget').html(pengurusHtml);

            // Reinitialize swiper for pengurus
            if (window.swiperPengurus) {
                window.swiperPengurus.update();
            } else {
                window.swiperPengurus = new Swiper("#swiper-pengurus", {
                    autoplay: {
                        delay: 3000,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    loop: true,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            }
        }

        function updateCounter(counter) {
            if(!counter) return;
            let counterHtml = `<ul class="nav">
        <li>Hari Ini <span class="pull-right badge bg-red">${counter.today} Kunjungan</span> </li>
        <li>Kemarin <span class="pull-right badge bg-purple">${counter.yesterday} Kunjungan</span> </li>
        <li>Minggu Ini <span class="pull-right badge bg-green">${counter.week} Kunjungan</span> </li>
        <li>Bulan Ini <span class="pull-right badge bg-yellow">${counter.month} Kunjungan</span> </li>
        <li>Tahun Ini <span class="pull-right badge bg-gray">${counter.year} Kunjungan</span> </li>
        <li>Total <span class="pull-right badge bg-blue">${counter.all} Kunjungan</span> </li>
    </ul>`;
            $('#desa-visitor-container').html(counterHtml)
        }
    })
</script>
@endpush