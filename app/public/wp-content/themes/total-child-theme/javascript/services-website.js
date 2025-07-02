function imageCarousel1() {
  const images = document.querySelectorAll('.websitehero-indiv-macframe-image');
  const totalImages = images.length;
  let currentImageIndex = totalImages - 1;

  function showNextImage1() {
    const currentImage = images[currentImageIndex];
    const nextImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
    const nextImage = images[nextImageIndex];

    // Fade out the current image
    currentImage.style.opacity = '0';
    currentImage.style.transition = 'opacity 1s';

    // Fade in the next image
    nextImage.style.opacity = '1';
    nextImage.style.transition = 'opacity 1s';

    // Update the current image index
    currentImageIndex = nextImageIndex;
  }

  // Set the initial image visibility
  images[currentImageIndex].style.opacity = '1';

  // Call showNextImage every 5 seconds for the carousel effect
  setTimeout(function(){
    setInterval(showNextImage1, 3000); // 5000 milliseconds = 5 seconds
  }, 2000)
}

// Call the imageCarousel function when the document is loaded
document.addEventListener('DOMContentLoaded', imageCarousel1);


function imageCarousel2() {
  const images = document.querySelectorAll('.websitehero-indiv-iphoneframe-image');
  const totalImages = images.length;
  let currentImageIndex = totalImages - 1;

  function showNextImage2() {
    const currentImage = images[currentImageIndex];
    const nextImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
    const nextImage = images[nextImageIndex];

    // Fade out the current image
    currentImage.style.opacity = '0';
    currentImage.style.transition = 'opacity 1s';

    // Fade in the next image
    nextImage.style.opacity = '1';
    nextImage.style.transition = 'opacity 1s';

    // Update the current image index
    currentImageIndex = nextImageIndex;
  }

  // Set the initial image visibility
  images[currentImageIndex].style.opacity = '1';

  // Call showNextImage every 5 seconds for the carousel effect
  setInterval(showNextImage2, 3000); // 5000 milliseconds = 5 seconds
}

// Call the imageCarousel function when the document is loaded
document.addEventListener('DOMContentLoaded', imageCarousel2);



function imageCarousel3() {
  const images = document.querySelectorAll('.lur-homepage-rotating-site-row-two');
  const totalImages = images.length;
  let currentImageIndex = totalImages - 1;

  function showNextImage3() {
    const currentImage = images[currentImageIndex];
    const nextImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
    const nextImage = images[nextImageIndex];

    // Fade out the current image
    currentImage.style.opacity = '0';
    currentImage.style.transition = 'opacity 1s';

    // Fade in the next image
    nextImage.style.opacity = '1';
    nextImage.style.transition = 'opacity 1s';

    // Update the current image index
    currentImageIndex = nextImageIndex;
  }

  // Set the initial image visibility
  images[currentImageIndex].style.opacity = '1';

  // Call showNextImage every 5 seconds for the carousel effect
  setInterval(showNextImage3, 3000); // 5000 milliseconds = 5 seconds
}

// Call the imageCarousel function when the document is loaded
document.addEventListener('DOMContentLoaded', imageCarousel3);

function imageCarousel4() {
  const images = document.querySelectorAll('.good-design-this-or-that-p');
  const totalImages = images.length;
  let currentImageIndex = totalImages - 1;

  function showNextImage4() {
    const currentImage = images[currentImageIndex];
    const nextImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
    const nextImage = images[nextImageIndex];

    // Fade out the current image
    currentImage.style.opacity = '0';
    currentImage.style.transition = 'opacity 1s';

    // Fade in the next image
    nextImage.style.opacity = '1';
    nextImage.style.transition = 'opacity 1s';

    // Update the current image index
    currentImageIndex = nextImageIndex;
  }

  // Set the initial image visibility
  images[currentImageIndex].style.opacity = '1';

  // Call showNextImage every 5 seconds for the carousel effect
  setInterval(showNextImage4, 3000); // 5000 milliseconds = 5 seconds
}

// Call the imageCarousel function when the document is loaded
document.addEventListener('DOMContentLoaded', imageCarousel4);


if (window.innerWidth > 959) {
  // Your JavaScript code here
  console.log("Screen width is greater than 1024 pixels.");
  // Add your code that should only run for screen widths above 1024 pixels


  jQuery(document).ready(function($) {
    var element = $('.lur-services-website-design-button');
    element.on('click', function() {
      scrollToClass('lur-services-website-design-answer', -250);
      var indivelement = $(this);
      element.removeClass('active');
      $(this).toggleClass('active');
      $('.lur-services-website-design-answer').toggleClass('active');
      // element.each(function() {
      $('.lur-services-website-design-answer').animate({
        'opacity': 0
      }, 100, function() {
          $('.lur-services-website-design-right').animate({
            'opacity': 0.1,
          }, 100);
          indivelement.next().animate({
            'opacity': 1,
          }, 100);
      });
    });
  });


  function scrollToClass(className, offset) {
    console.log('in function')
    const elements = document.getElementsByClassName(className);
    console.log(elements)

    if (elements.length > 0) {
      // Scroll smoothly to the first element with the specified class, with an offset
      const targetOffset = elements[0].getBoundingClientRect().top + window.pageYOffset + offset;
      window.scrollTo({ top: targetOffset, behavior: 'smooth' });
    }
  }
} else {


  jQuery(document).ready(function($) {
    var element = $('.lur-services-website-design-button');


   















    element.on('click', function() {
      var indivelement = $(this);
      $(this).toggleClass('active');
      if ($(this).hasClass('active')){
        var answer = $(this).next();
        answer.css('height','initial');
        var height =  answer.css('height');
        answer.css('height',0); 
        answer.animate({
          'opacity': 1,
          'height': height,
          'padding':'20px',
          'margin-bottom': '170px'
        }, 500 );

      } else {


        element.next().animate({
          'opacity': 0,
          'height': 0,
          'padding':'0px',
          'margin-bottom': '0px'
        }, 500 );



      }
    });
  });



}

// Example usage: scrolling to a div with class name "myClass"
//scrollToClass('lur-services-website-design-answer');
