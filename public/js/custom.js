$(document).ready(function () {
  $(function () {
    $(document).on("scroll", function () {
      if ($(window).scrollTop() > 100) {
        $(".scroll-top-wrapper").addClass("show");
      } else {
        $(".scroll-top-wrapper").removeClass("show");
      }
    });
    $(".scroll-top-wrapper").on("click", scrollToTop);
  });

  function scrollToTop() {
    verticalOffset = typeof verticalOffset != "undefined" ? verticalOffset : 0;
    element = $("body");
    offset = element.offset();
    offsetTop = offset.top;
    $("html, body").animate(
      {
        scrollTop: offsetTop,
      },
      500,
      "linear",
    );
  }
});

window.onscroll = function () {
  stickyFunction();
};

var navbar = document.getElementById("navbar");
var logo = document.getElementById("logo-brand");
var sticky = navbar.offsetTop;

function stickyFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky");
  } else {
    navbar.classList.remove("sticky");
  }

  if (document.body.scrollTop > 30 || document.documentElement.scrollTop > 30) {
    let mql = window.matchMedia("(max-width: 1199px) and (min-width: 992px)"); // media query lebar 1024px
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
  const baca = [
    "",
    "Satu",
    "Dua",
    "Tiga",
    "Empat",
    "Lima",
    "Enam",
    "Tujuh",
    "Delapan",
    "Sembilan",
    "Sepuluh",
    "Sebelas",
  ];
  let hasil = "";

  if (angka < 12) {
    hasil = " " + baca[angka];
  } else if (angka < 20) {
    hasil = terbilang(angka - 10) + " Belas";
  } else if (angka < 100) {
    hasil =
      terbilang(Math.floor(angka / 10)) + " Puluh" + terbilang(angka % 10);
  } else if (angka < 200) {
    hasil = " Seratus" + terbilang(angka - 100);
  } else if (angka < 1000) {
    hasil =
      terbilang(Math.floor(angka / 100)) + " Ratus" + terbilang(angka % 100);
  } else if (angka < 2000) {
    hasil = " Seribu" + terbilang(angka - 1000);
  } else if (angka < 1000000) {
    hasil =
      terbilang(Math.floor(angka / 1000)) + " Ribu" + terbilang(angka % 1000);
  } else if (angka < 1000000000) {
    hasil =
      terbilang(Math.floor(angka / 1000000)) +
      " Juta" +
      terbilang(angka % 1000000);
  }

  return hasil.trim();
}

// Helper function to format date
function formatDate(dateString) {
  if (!dateString) return "";

  var options = { year: "numeric", month: "long", day: "numeric" };
  var date = new Date(dateString);
  return date.toLocaleDateString("id-ID", options);
}

function generateDropdownYear(element) {
  // Create Year List for 4 years ago
  let thisYear = new Date().getFullYear();  

  for (let i = 1; i <= 3; i++) {    
	$(element).append(`<option value="${thisYear}">${thisYear}</option>`);
	thisYear--;
  }  
}

/**
 * Render media galeri (foto, video, atau YouTube) menjadi HTML.
 *
 * Data galeri berasal dari API frontend dan sudah membawa:
 * - media_type     : "image" | "video" | "youtube" | "unknown"
 * - media_url      : URL yang bisa dimuat langsung (src)
 * - media_embed_url: URL untuk <iframe> bila <video> tidak mendukung
 * - gambar_path    : thumbnail/pratinjau
 *
 * Opsi:
 * - className  : kelas CSS untuk elemen pembungkus
 * - imageStyle : gaya inline untuk elemen <img>
 * - controls   : tampilkan kontrol pemutar video (default true)
 * - autoplay   : mulai putar otomatis (default false)
 * - preferNativeVideo : pakai <video> walau tersedia embed
 */
function renderGaleriMedia(galeri, options) {
  var opts = options || {};
  var type = galeri && galeri.media_type ? galeri.media_type : "unknown";
  var url = galeri ? galeri.media_url : null;
  var embed = galeri ? galeri.media_embed_url : null;
  var poster = (galeri && galeri.gambar_path) || "/img/no-image.png";
  var label = galeri ? galeri.judul || "Media" : "Media";
  var wrapperClass = opts.className ? " " + opts.className : "";
  var imageStyle = opts.imageStyle || "width:100%;height:100%;object-fit:cover;";
  var allowFullscreen =
    ' allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"';

  function escapeAttr(value) {
    return String(value)
      .replace(/&/g, "&amp;")
      .replace(/"/g, "&quot;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  }

  var html = "";

  if (type === "youtube" && embed) {
    // YouTube hanya mengizinkan pemutaran lewat iframe.
    html =
      '<div class="embed-responsive embed-responsive-16by9' + wrapperClass + '">' +
      '<iframe class="embed-responsive-item" src="' + escapeAttr(embed) + '" title="' + escapeAttr(label) + '"' + allowFullscreen + "></iframe>" +
      "</div>";
  } else if (type === "video" && embed && !opts.preferNativeVideo) {
    // Google Drive dan Vimeo tidak mendukung Range Request, jadi pakai preview resmi.
    html =
      '<div class="embed-responsive embed-responsive-16by9' + wrapperClass + '">' +
      '<iframe class="embed-responsive-item" src="' + escapeAttr(embed) + '" title="' + escapeAttr(label) + '"' + allowFullscreen + "></iframe>" +
      "</div>";
  } else if (type === "video" && url) {
    html =
      '<video src="' + escapeAttr(url) + '"' + (opts.controls === false ? "" : " controls") + ' preload="metadata"' + (opts.autoplay ? " autoplay" : "") + ' playsinline style="width:100%;height:100%;object-fit:contain;background:#000;"' + wrapperClass + "></video>";
  } else if (type === "image" && url) {
    html =
      '<img src="' + escapeAttr(url) + '" alt="' + escapeAttr(label) + '" loading="lazy" style="' + imageStyle + '"' + wrapperClass +
      ' onerror="this.onerror=null;this.src=\'/img/no-image.png\';">';
  } else {
    html =
      '<div class="' + (opts.className || "") + '">' +
      '<img src="' + escapeAttr(poster) + '" alt="' + escapeAttr(label) + '" loading="lazy" style="' + imageStyle + '">' +
      "</div>";
  }

  return html;
}

/**
 * Tandai galeri yang berupa video/YouTube agar tema bisa menampilkan ikon putar.
 */
function isGaleriPlayable(galeri) {
  var type = galeri && galeri.media_type ? galeri.media_type : "unknown";
  return type === "video" || type === "youtube";
}

//drop down menu
$(".drop-down").hover(function () {
  $(".dropdown-menu").addClass("display-on");
});
$(".drop-down").mouseleave(function () {
  $(".dropdown-menu").removeClass("display-on");
});
