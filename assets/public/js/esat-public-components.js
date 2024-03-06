// =============================================================================
// JS related to ESAT_Customization features
// @since 1.0.3
// =============================================================================

/**
* Preloader
* 
* @since 1.0.3
*/
document.addEventListener('DOMContentLoaded', function() {
    // Ascunde preloader-ul după ce pagina este complet încărcată
    var preloader = document.querySelector('.preloader');
    if (preloader) {
        preloader.style.display = 'none';
    }
});

/**
* Scroll to top
* 
* @since 1.0.4
*/
jQuery(document).ready(function($) {
    $("#esat-scroll-to-top").hide();
    
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $("#esat-scroll-to-top").fadeIn();
        } else {
            $("#esat-scroll-to-top").fadeOut();
        }
    });
    
    $("#esat-scroll-to-top").click(function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });
});
