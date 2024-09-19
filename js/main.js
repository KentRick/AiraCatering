
function showSlide(slideNumber) {
    const slides = document.querySelectorAll('.slides-div');
    const slider = document.querySelector('.image-slider');

    slides.forEach(slide => slide.classList.remove('active'));

    const selectedSlide = document.querySelector(`#slide-${slideNumber}`);
    selectedSlide.classList.add('active');

    if (slideNumber === 1) {
        slider.style.left = '20%';
    } else if (slideNumber === 2) {
        slider.style.left = '0%';
    } else if (slideNumber === 3) {
        slider.style.left = '-20%';
    }
    }

    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', event => {
       
          if(event.target.classList.contains('dropdown-toggle') ){
            event.target.classList.toggle('toggle-change');
          }
          else if(event.target.parentElement.classList.contains('dropdown-toggle')){
            event.target.parentElement.classList.toggle('toggle-change');
          }
        })
      });

//burgir

      (function($) {

        "use strict";
      
        var fullHeight = function() {
      
          $('.js-fullheight').css('height', $(window).height());
          $(window).resize(function(){
            $('.js-fullheight').css('height', $(window).height());
          });
      
        };
        fullHeight();
      
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
      
      })(jQuery);
      


