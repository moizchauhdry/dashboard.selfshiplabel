$(document).ready(function(){
    $('.owl-carousel').owlCarousel({
        loop:true,
        items:1,
        lazyLoad:true,
        nav:true,
        navText: ['<i class="far fa-arrow-left"></i>', '<i class="far fa-arrow-right"></i>'],
        dots:false,
        autoplay:false,
        autoplayTime:2000,
        responsive:{
            0:{
                items:1,
                dots:true,
            },
            767:{
                items:1,
                dots:true,
            },
            1200:{
                items:1
            }
        }
    }) 

});