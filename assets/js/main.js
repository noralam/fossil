/*
  * Theme Name: Fossil | Gas Station & Petrol Pump HTML Template

*/

(function ($) {
	"use strict";

  $.fn.porfolioExpertAccessibleDropDown = function () {
    var el = $(this);

    $("a", el)
        .on("focus", function () {
            $(this).parents("li").addClass("hover");
        })
        .on("blur", function () {
            var that = this;
            setTimeout(function () {
                if (!$(that).parents("li").find("a:focus").length) {
                    $(that).parents("li").removeClass("hover");
                }
            }, 10);
        })
        .on("keydown", function (e) {
            var parentLi = $(this).parent("li");

            // Detect Shift + Tab
            if (e.shiftKey && e.key === "Tab") {
                var prevElement = $(this).parent("li").prev().find("a").last();
                if (prevElement.length) {
                    prevElement.focus();
                    e.preventDefault();
                }
            }
        });
	};

	 jQuery(document).ready(function ($) {
    	$("#fossil-eye-menu").porfolioExpertAccessibleDropDown();

			//Header Search Form
		if ($(".search-btn").length) {
			$(".search-btn").on("click", function () {
				$("body").addClass("search-active");
			});
			$(".close-search, .search-back-drop").on("click", function () {
				$("body").removeClass("search-active");
			});
		}

		//jQuery Sticky Area 
		$('.sticky-area').sticky({
			topSpacing: 0,
		});

		// Preloader
		setTimeout(function () {
			$("#loader").fadeOut(600);
		}, 200);

			// Animate the scroll to top
		$('.go-top').on("click", function (event) {
			event.preventDefault();

			$('html, body').animate({
				scrollTop: 0
			}, 500);
		});


		$(".single-price-item").on("mouseover", function () {
			$(".single-price-item").removeClass("active");
			$(this).addClass("active");
		});

		// Menu Active Color 
		$(".main-menu .navbar-nav .nav-link").on("mouseover", function () {
			$(".main-menu .navbar-nav .nav-link").removeClass("active");
			$(this).addClass("active");
		});
 	 });


	

	jQuery(window).on("load", function () {
		jQuery(".site-preloader-wrap, .slide-preloader-wrap").fadeOut(1000);
	});


	// SCROLLTO THE TOP

	$(window).on("scroll", function () {
		if ($(this).scrollTop() > 1000) {
			$('.go-top').fadeIn(200);
		} else {
			$('.go-top').fadeOut(200);
		}
	});


	


}(jQuery));
