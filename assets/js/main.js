$(document).ready(function(){
    $('#nd').tooltip({
      placement:'left',
      html:'true'
    });
  
    $('#nt-slider').slick({
      infinite: false,
      slidesToShow: 5,
      slidesToScroll: 5,
      accesibility:true,
      arrows:false,
      swipe: true,
      responsive: [
        {
          breakpoint: 922,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 4,
            infinite: false
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: false
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: false
          }
        },
        {
          breakpoint: 300,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            infinite: false
          }
        }
      ]
    });
    

    $('#nt-slider-prev').click(function (e) {
      e.preventDefault();
      $('#nt-slider').slickPrev();
    });
  
    $('#nt-slider-next').click(function (e) {
      e.preventDefault();
      $('#nt-slider').slickNext();
    });
});