jQuery(document).ready(function(){
	var allPanels = jQuery('.settingwrap').hide();
	jQuery('.menu-title').click(function(){
		allPanels.slideUp();
		jQuery(this).next().slideDown();
		return false;
	})
});