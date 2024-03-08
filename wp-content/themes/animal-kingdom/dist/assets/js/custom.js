(function ($) {
  $(document).ready(function () {

    jQuery('.home-banner-slider, .did-you-know-slider').slick({
      arrows: true,
      centerPadding: "0px",
      dots: false,
      slidesToShow: 1,
      infinite: false
    });

    jQuery('.animal-detail-banner-slider').slick({
      arrows: false,
      centerPadding: "0px",
      dots: true,
      slidesToShow: 1,
      infinite: false
    });

    jQuery('.explore-wild-life-slider').slick({
      arrows: true,
      centerPadding: '250px',
      dots: false,
      slidesToShow: 3,
      centerMode: true,
      responsive: [
        {
          breakpoint: 1500,
          settings: {
            centerPadding: '80px',
          }
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 2,
            centerPadding: '120px',
          }
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 1,
            centerPadding: '180px',
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            centerPadding: '0',
          }
        },
        {
          breakpoint: 250,
          settings: {
            slidesToShow: 1,
            centerPadding: '80px',
          }
        }
      ]
    });

    jQuery('.see-all-picture-item-wrapper').slick({
      arrows: true,
      dots: false,
      slidesToShow: 3,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
           }
        },
        {
          breakpoint: 400,
          settings: {
            slidesToShow: 1,
           }
        },
        {
          breakpoint: 250,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });
    jQuery('.image-popup-vertical-fit').magnificPopup({
      type: 'image',
      mainClass: 'mfp-with-zoom', 
      gallery:{
          enabled:true
        },
    
      zoom: {
        enabled: true, 
    
        duration: 300, // duration of the effect, in milliseconds
        easing: 'ease-in-out', // CSS transition easing function
    
        opener: function(openerElement) {
          return openerElement.is('img') ? openerElement : openerElement.find('img');
      }
    }
    
    });

    jQuery('.see-all-picture-wrapper .image-popup-vertical-fit').magnificPopup({
      type: 'image',
      mainClass: 'mfp-with-zoom',
      gallery: {
        enabled: true
      },
      zoom: {
        enabled: true,
        duration: 300,
        easing: 'ease-in-out',
        opener: function(openerElement) {
          // Update this part to target the div with background image
          return openerElement.is('div') ? openerElement : openerElement.find('div');
        }
      }
    
    });
    
    jQuery( ".modal-popup" ).each(function() {
      jQuery(this).on("click", function(){
        jQuery(".modal").show();
        jQuery("body").css("overflow","hidden");
        if (jQuery(this).is("img")) {
          const imgSrc = jQuery(this).attr("src");
          jQuery(".modal .modal-content").attr("src", imgSrc);
        } else if (jQuery(this).is("div")) {
          var bg_img = jQuery(this)
            .css("background-image")
            .replace(/^url\(['"](.+)['"]\)/, "$1");
          jQuery(".modal .modal-content").attr("src", bg_img);
        }
      });
    });


    jQuery(".modal .close").on("click", function() {
      jQuery(".modal").hide();
      jQuery("body").css("overflow","auto");
    });

    // select
    var itemValue;

    const selectValue = document.querySelectorAll("#reason option");

    $(".select-dropdown__button").on("click", function () {
      $(this).toggleClass('active');
      $(this).next().toggleClass("active");
    });

    $(".mobile-hamburger-menu .menu-icon").on("click", function () {
      $(".menu-icon").toggleClass("open");
      $(".mobile-menu").toggleClass("open");
      $('body').toggleClass('fixed');
    });

    $(".mobile-menu ul li").on("click", function () {
      $(this).addClass("active").siblings().removeClass("active");
    });

    $(".mobile-menu ul li").each(function (i, elem) {
      if ($(this).find("ul").length > 0) {
        $(this).children("label").append("<i></i>");
      } else {
        $(this).addClass("no-ul");
      }
    });

    $(".select-dropdown__list .select-dropdown__list-item").on("click", function () {
      itemValue = $(this).data("value");
      $(this).parent('.select-dropdown__list').prev().children('span').text($(this).text()).parent() .attr("data-value", itemValue);
      // $(".select-dropdown__button span")
      //   .text($(this).text())
      //   .parent()
      //   .attr("data-value", itemValue);
      $(this).parent(".select-dropdown__list").prev().toggleClass('active');
      $(this).parent(".select-dropdown__list").toggleClass("active");

      selectValue.forEach((ele) => {
        // console.log("itemValue", ele, itemValue, ele.attributes.value.value);
        if (itemValue === ele.attributes.value.value) {
          ele.setAttribute("selected", "selected");
        }
      });
    });

    // var tab_name;
    // var tab_content_attr;

    // let tab_ele = document.querySelectorAll('.tab-contents .tab-content')

    // $('.tab-list li').on('click', function(){
    //   $(this).addClass('active').siblings().removeClass('active');
    //   console.log("value", $(this).children('label').attr('data-value'));
    //   tab_name = $(this).children('label').attr('data-value');
    // })

    // console.log("tab_ele", tab_ele);

    // tab_ele.forEach(ele => {
    //   tab_content_attr = $(ele).attr('data-value')
    //   console.log("ele", tab_content_attr);
    //   tab_name === tab_content_attr ? $(ele).addClass('active') : $(ele).removeClass('active'); 
    // })

    // for faqs
    $('.faqs .faq').on('click', function(){
      $(this).toggleClass('active').siblings().removeClass('active');
    })

    var windowWidth = window.innerWidth;

    if(windowWidth < 992){
      $('.policy-inner .add-banner').insertAfter($('.banner-inner-page'));
    }

  });
})(jQuery);
