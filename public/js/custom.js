$(document).ready(function () {
	$(function () {
		$(document).on('scroll', function () {

			if ($(window).scrollTop() > 100) {
				$('.scroll-top-wrapper').addClass('show');
			} else {
				$('.scroll-top-wrapper').removeClass('show');
			}
		});
		$('.scroll-top-wrapper').on('click', scrollToTop);
	});

	function scrollToTop() {
		verticalOffset = typeof (verticalOffset) != 'undefined' ? verticalOffset : 0;
		element = $('body');
		offset = element.offset();
		offsetTop = offset.top;
		$('html, body').animate({
			scrollTop: offsetTop
		}, 500, 'linear');
	}

});

window.onscroll = function () {
	stickyFunction()
};

var navbar = document.getElementById("navbar");
var logo = document.getElementById("logo-brand");
var sticky = navbar.offsetTop;

function stickyFunction() {
	if (window.pageYOffset >= sticky) {
		navbar.classList.add("sticky")
	} else {
		navbar.classList.remove("sticky");
	}

	if (document.body.scrollTop > 30 || document.documentElement.scrollTop > 30) {
		let mql = window.matchMedia('(max-width: 1199px) and (min-width: 992px)'); // media query lebar 1024px
		if (mql.matches) {
			navbar.style.fontSize = "11px";
			navbar.style.padding = "0px";
			logo.style.width = "150px";
			return;
		}
		navbar.style.fontSize = "12px";
		navbar.style.padding = "0px";
		logo.style.width = "150px";
	} else {
		navbar.style.fontSize = "16px";
		logo.style.width = "180px";
	}
}

function terbilang(angka) {
    angka = Math.abs(angka);
    const baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
    let hasil = '';

    if (angka < 12) {
        hasil = ' ' + baca[angka];
    } else if (angka < 20) {
        hasil = terbilang(angka - 10) + ' Belas';
    } else if (angka < 100) {
        hasil = terbilang(Math.floor(angka / 10)) + ' Puluh' + terbilang(angka % 10);
    } else if (angka < 200) {
        hasil = ' Seratus' + terbilang(angka - 100);
    } else if (angka < 1000) {
        hasil = terbilang(Math.floor(angka / 100)) + ' Ratus' + terbilang(angka % 100);
    } else if (angka < 2000) {
        hasil = ' Seribu' + terbilang(angka - 1000);
    } else if (angka < 1000000) {
        hasil = terbilang(Math.floor(angka / 1000)) + ' Ribu' + terbilang(angka % 1000);
    } else if (angka < 1000000000) {
        hasil = terbilang(Math.floor(angka / 1000000)) + ' Juta' + terbilang(angka % 1000000);
    }

    return hasil.trim();
}

//drop down menu
$(".drop-down").hover(function () {
	$('.dropdown-menu').addClass('display-on');
});
$(".drop-down").mouseleave(function () {
	$('.dropdown-menu').removeClass('display-on');
});