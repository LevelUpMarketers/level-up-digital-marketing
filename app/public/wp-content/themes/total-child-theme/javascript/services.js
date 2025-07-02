jQuery(document).ready(function($) {    

  var element = $('.services-faq-indiv-container');
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

        $(this).find('.services-faq-indiv-answer').animate({
          'height': 0,
          'opacity': 0,
          'padding': 0,
          'padding-top': 0,
          'padding-bottom': 0,
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
      // Animate the element's styling
      var text = $(this).find('.services-faq-indiv-answer');
      text.css('padding','15px');
      text.css('padding-top','20px');
      text.css('padding-bottom','20px');
      text.css('height','initial');
      var height =  text.css('height');
      text.css('height',0);
      text.css('padding',0);
      text.css('padding-top',0);
      text.css('padding-bottom',0);

      var image = $(this).find('img');
      var rotation = 0;
      rotation += 180;
      image.css("transform", "rotate(" + rotation + "deg)");
      
      $(this).find('.services-faq-indiv-answer').animate({
        'height': height,
        'opacity': '1',
        'padding': '15px',
        'padding-top': '20px',
        'padding-bottom': '20px',
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

      $(this).find('.services-faq-indiv-answer').animate({
        'height': 0,
        'opacity': 0,
        'padding': 0,
        'padding-top': 0,
        'padding-bottom': 0,
      }, 500); // 500 milliseconds animation duration
      $('.lur-services-seo-partofseo-indiv-accordian-title-holder').css({'pointer-events':'all'})
      $('.lur-services-seo-partofseo-indiv-accordian-title-holder').animate({
        'opacity': '1'
      }, 500);
    }
  });



});