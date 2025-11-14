
document.addEventListener('DOMContentLoaded', function () {
  const carouselContainers = document.querySelectorAll('.game-section');

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const carousel = $(entry.target).find('.owl-carousel2');
        const navEnabled = entry.target.getAttribute('data-nav') === 'true';

        // Initialize Owl Carousel
        carousel.owlCarousel({
          autoWidth: true,
          loop: true,
          autoplay: true,
          lazyLoad: true,
          lazyLoadEager: 1,
          nav:navEnabled,
          navText: ['<i class="bx bx-chevron-left"></i>','<i class="bx bx-chevron-right"></i>'],
          dots: false, // This disables the dots
          autoplayHoverPause: true
        });

        // Unobserve after initialization
        observer.unobserve(entry.target);
      }
    });
  });

  carouselContainers.forEach(container => observer.observe(container));
});

  //client story
   $(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        accessibility: true,
        responsive: [{
            breakpoint: 1200,
            settings: {
                slidesToShow: 2
            }
        },{
            breakpoint: 1000,
            settings: {
                slidesToShow: 1
            }
        },{
            breakpoint: 768,
            settings: {
                slidesToShow: 1
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });
    // After initialization, add ARIA labels
    $('.customer-logos').on('init', function(event, slick) {
      // Set ARIA attributes for the slider
      var sliderTrack = $(this).find('.slick-track');
      sliderTrack.attr('aria-label', 'Slide to right');
      // Optionally, set ARIA labels for individual slides
      slick.$slides.each(function(index) {
        $(this).attr('role', 'group');
        $(this).attr('aria-label', 'Slide ' + (index + 1) + ' of ' + slick.slideCount);
    });
  });
});

//Talk To Our Furniture Expert  section 
$(document).ready(function() {
  $(".toggle-button").on("click", function() {
    const targetDiv = $(this).data("target");
    $(targetDiv).toggle();

    // Change button text based on visibility
    const isVisible = $(targetDiv).is(":visible");
    $(this).text(isVisible ? "Hide " : "Show More");
  });
});


//top scroll 
$(document).ready(function(){ 
    $('.SmoothmyTop').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 100);
        return false;
    });
});