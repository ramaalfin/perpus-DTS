// toggle
$( document ).on( "click", ".button-nav, .navigation-backdrop", function () {
  
  var $nav = $( "#navigation-demo" );
  var $hasClass = $nav.hasClass( "open" );

  if ( !$hasClass ) {
      $nav.addClass( "open" );
      $( "body" ).append( "<div class='navigation-backdrop'></div>" );
  } else {
      $nav.removeClass( "open" );
      $( ".navigation-backdrop" ).remove();
  }

});

// carousel
// $('.owl-carousel').owlCarousel({
//   loop:true,
//   margin:10,
//   responsiveClass:true,
//   responsive:{
//       0:{
//           items:1,
//           nav:true
//       },
//       600:{
//           items:3,
//           nav:false
//       },
//       1000:{
//           items:5,
//           nav:true,
//           loop:false
//       }
//   }
// })