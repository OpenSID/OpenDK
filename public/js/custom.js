
const signs = document.querySelectorAll('x-sign')
const randomIn = (min, max) => (
  Math.floor(Math.random() * (max - min + 1) + min)
)

const mixupInterval = el => {
  const ms = randomIn(2000, 4000)
  el.style.setProperty('--interval', `${ms}ms`)
}

signs.forEach(el => {
  mixupInterval(el)
  el.addEventListener('webkitAnimationIteration', () => {
    mixupInterval(el)
  })
});

$(document).ready(function() {

  $(function() {

      $(document).on('scroll', function() {

          if ($(window).scrollTop() > 100) {
              $('.scroll-top-wrapper').addClass('show');
          } else {
              $('.scroll-top-wrapper').removeClass('show');
          }
      });

      $('.scroll-top-wrapper').on('click', scrollToTop);
  });

  function scrollToTop() {
      verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
      element = $('body');
      offset = element.offset();
      offsetTop = offset.top;
      $('html, body').animate({
          scrollTop: offsetTop
      }, 500, 'linear');
  }

  //drop down menu  
  $(".drop-down").hover(function() {
    $('.dropdown-menu').addClass('display-on');
  });
  $(".drop-down").mouseleave(function() {
    $('.dropdown-menu').removeClass('display-on');
  });

  
});

window.onscroll = function() {stickyFunction()};

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
    navbar.style.fontSize = "12px";
    navbar.style.padding = "0px";
    logo.style.width = "100px";
  } else {
    navbar.style.fontSize = "17px";
    // navbar.style.padding = "10px";
    logo.style.width = "130px";
    // logo.style.bottom = "20px";
  }
}
