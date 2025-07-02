jQuery(document).ready(function($) {    
    function animateheroimages(){
        var images = document.querySelectorAll(".lur-seo-google-shot");
        // Convert the NodeList to reverse order array
        images = Array.from(images).reverse();
        images.push(null);
        images = Array.from(images).reverse();
        images.push(null);
        images = Array.from(images).reverse();


        images.forEach(function(element, index) {
                setTimeout(function(){
                    if ( 5 === index ){
                        //setTimeout(function(){
                            animateimagesreset()
                        //}, (2000)  )
                    } else {
                        if( null !== element ){
                            animateimages(element.id)
                        }
                    }
                }, (3500 * index)  )
        });
    }

    function animateimages(id){
            $('#'+id).animate({
                'left': 1000,
            }, 500);
    }

    function animateimagesreset(){

            $('.lur-seo-google-shot').each( function( index, element ){
                var left = (index * 2) * 10;
                $(this).css({
                    'left': left + 'px',
                    'opacity': '1',
                });
            })
           animateheroimages();
    }




    function runProgressBar() {
      const progressBar = $('.progress');
      progressBar.css('animation', 'fill-progress 48s linear');

      progressBar.each(function(index, element) {
        const $element = $(element);

        setTimeout(() => {
          $element.css({
            animation: 'none',
          });

          setTimeout(runProgressBar, 100); // Re-run after 100 milliseconds
        }, 48000);
      });
    }

    function imageCarousel1() {
      const images = $('.lur-services-seo-whatisseo-google-img').get().reverse();
      const totalImages = images.length;
      let currentImageIndex = totalImages - 1;

      function showNextImage1() {
        const currentImage = $(images[currentImageIndex]);
        const nextImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
        const nextImage = $(images[nextImageIndex]);

        // Fade out the current image
        currentImage.css({
          opacity: '0',
          transition: 'opacity 1s'
        });

        // Fade in the next image
        nextImage.css({
          opacity: '1',
          transition: 'opacity 1s'
        });

        // Update the current image index
        currentImageIndex = nextImageIndex;
      }

      // Set the initial image visibility
      $(images[currentImageIndex]).css('opacity', '1');

      // Call showNextImage every 5 seconds for the carousel effect
      setTimeout(function(){
        setInterval(showNextImage1, 12000); // 12000 milliseconds = 12 seconds
      }, 0);
    }

    function imageCarousel2() {
      const images = $('.lur-services-seo-whatisseo-google-text').get().reverse();
      const totalImages = images.length;
      let currentImageIndex = totalImages - 1;

      function showNextImage2() {
        const currentImage = $(images[currentImageIndex]);
        const nextImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
        const nextImage = $(images[nextImageIndex]);

        // Fade out the current image
        currentImage.css({
          opacity: '0',
          transition: 'opacity 1s'
        });

        // Fade in the next image
        nextImage.css({
          opacity: '1',
          transition: 'opacity 1s'
        });

        // Update the current image index
        currentImageIndex = nextImageIndex;
      }

      // Set the initial image visibility
      $(images[currentImageIndex]).css('opacity', '1');

      // Call showNextImage every 5 seconds for the carousel effect
      setTimeout(function(){
        setInterval(showNextImage2, 12000); // 12000 milliseconds = 12 seconds
      }, 0);
    }

  var element = $('.lur-services-seo-partofseo-indiv-accordian-title-holder');
  var antielement = $('body');

  element.on('click', function(event) {
    // Stop the click event from propagating further
    event.stopPropagation();
    
    // Your code here
  });

 $(document).on('click', function(event) {


  element.on('click', function(event) {
    // Stop the click event from propagating further
    event.stopPropagation();
    
    // Your code here
  });

    element.each(function() {
      // Check if the element has an additional class 'additional-class'
      if ($(this).hasClass('active')) {
        var activeelement = $(this);
        $(this).removeClass('active');

        var image = $(this).find('img');
        var rotation = 0;
        image.css("transform", "rotate(" + rotation + "deg)");

        $(this).next().animate({
          'height': 0,
          'opacity': 0,
          'pointer-events':'all',
        }, 500); // 500 milliseconds animation duration
        $('.lur-services-seo-partofseo-indiv-accordian-title-holder').css({'pointer-events':'all'})
        $('.lur-services-seo-partofseo-indiv-accordian-title-holder').animate({
          'opacity': '1'
        }, 500);
      }
    });
  });

  element.on('click', function() {
    $(this).toggleClass('active');
    if ($(this).hasClass('active')){
      $('.lur-services-seo-partofseo-indiv-accordian-title-holder:not(.active)').css({'pointer-events':'none'})
      $('.lur-services-seo-partofseo-indiv-accordian-title-holder:not(.active)').animate({
        'opacity': '0'
      }, 500);
      // Animate the element's styling
      var text = $(this).next();
      text.css('height','initial');
      var height =  text.css('height');
      text.css('height',0);

      var image = $(this).find('img');
      var rotation = 0;
      rotation += 180;
      image.css("transform", "rotate(" + rotation + "deg)");
      
      $(this).next().animate({
        'height': height,
        'opacity': '1',
      }, 500); // 500 milliseconds animation duration
/*
      buttons.css('height','initial');
      var height =  buttons.css('height');
      buttons.css('height',0);
*/
    } else {

      var image = $(this).find('img');
      var rotation = 0;
      image.css("transform", "rotate(" + rotation + "deg)");

      $(this).next().animate({
        'height': 0,
        'opacity': 0
      }, 500); // 500 milliseconds animation duration
      $('.lur-services-seo-partofseo-indiv-accordian-title-holder').css({'pointer-events':'all'})
      $('.lur-services-seo-partofseo-indiv-accordian-title-holder').animate({
        'opacity': '1'
      }, 500);
    }
  });






var threshold = 959; // Set your desired threshold value here
var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

if (screenWidth < threshold) {
  // Screen width is below the threshold
} else {
  // Screen width is equal to or above the threshold
    animateheroimages();
    runProgressBar();
    imageCarousel1();
    imageCarousel2();

}


});