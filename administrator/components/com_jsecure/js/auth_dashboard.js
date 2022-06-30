$(function () {
	jQuery('#submenu li').append('<span></span>');
	
	function menuhover(){
		jQuery(".desktopview .container-fluid.container-main .span2").mouseenter(function(){
				jQuery(this).stop().animate({left: '0'}, 200); 
			});
			jQuery(".desktopview .container-fluid.container-main .span2").mouseleave(function(){
				jQuery(this).stop().animate({left: '-223px'}, 200);
			});
	}

		jQuery('#submenu li:first-child').click(function(){
			jQuery(".container-fluid.container-main").toggleClass("menu-open");
			jQuery('.sidepanel-overlay').fadeToggle(100);
			if(jQuery('.container-fluid.container-main').hasClass('menu-open')){
				jQuery(".container-fluid.container-main .span2").animate({left: '0px'}, 200);
			} else {
				jQuery(".container-fluid.container-main .span2").animate({left: '-223px'}, 200);
			}
		});
		
		jQuery('.sidepanel-overlay').click(function(){
			jQuery(".container-fluid.container-main").toggleClass("menu-open");
			jQuery('.sidepanel-overlay').fadeToggle(100);
				
			if(jQuery('.container-fluid.container-main').hasClass('menu-open')){
				jQuery(".container-fluid.container-main .span2").animate({left: '0px'}, 200);
			} else {
				jQuery(".container-fluid.container-main .span2").animate({left: '-223px'}, 200);
			}
		});
	
	
	jQuery(window).load(function(){
		//condition
		if(jQuery(window).width() > 769){
			jQuery('body').removeClass('mobileview').addClass('desktopview');
			jQuery('.container-fluid.container-main .span2').bind('mouseenter mouseleave');
			
			//hover
			menuhover();
			
		} else if (jQuery(window).width() < 769) {
			jQuery('body').removeClass('desktopview').addClass('mobileview');
			jQuery(".mobileview .container-fluid.container-main").removeClass("menu-open");
			jQuery('.mobileview .container-fluid.container-main .span2').unbind('mouseenter mouseleave');
			
			//click
			//mobmenu();
			
		}
	});


jQuery(window).resize(function(){
		if(jQuery(window).width() > 769){
			
			jQuery(".mobileview .container-fluid.container-main .span2").animate({left: '-223px'}, 200);
			jQuery('body').removeClass('mobileview').addClass('desktopview');
			jQuery(".desktopview .container-fluid.container-main .span2").animate({left: '-223px'}, 200);
			jQuery(".desktopview .container-fluid.container-main .span2").mouseenter(function(){
				jQuery(this).stop().animate({left: '0px'}, 200); 
			});
			jQuery(".desktopview .container-fluid.container-main .span2").mouseleave(function(){
				jQuery(this).stop().animate({left: '-223px'}, 200);
			});
		}
		
		else if(jQuery(window).width() < 769){
			
			jQuery('body').removeClass('desktopview').addClass('mobileview');
			jQuery(".mobileview .container-fluid.container-main .span2").css('left','-223px');
			jQuery(".mobileview .container-fluid.container-main").removeClass("menu-open");
			jQuery('.container-fluid.container-main .span2').unbind('mouseenter mouseleave');
		}
	});
});
