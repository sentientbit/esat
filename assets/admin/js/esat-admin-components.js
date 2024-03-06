/**
 * Security level progress
 * 
 *  @since 1.0.0
 */
$(document).ready(function(){
    $('.progress-value > span').each(function(){
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        },{
            duration: 3500,
            easing: 'swing',
            step: function (now){
                $(this).text(Math.ceil(now));
            }
        });
    });
});

/**
 * Lock dropdown in settings
 * 
 *  @since 1.0.0
 */
document.addEventListener("DOMContentLoaded", function() {
    setupAccordionCheckbox("esat_security_option_1", "accordionSecurity_1");
    setupAccordionCheckbox("esat_security_option_2", "accordionSecurity_2");

    function setupAccordionCheckbox(checkboxId, accordionId) {
        var checkbox = document.getElementById(checkboxId);
        var accordion = document.getElementById(accordionId);

        var isChecked = localStorage.getItem(checkboxId);
        if (isChecked === "true") {
            checkbox.checked = true;
            accordion.classList.remove("disable");
        } else {
            checkbox.checked = false;
            accordion.classList.add("disable");
            accordion.querySelector(".accordion-collapse").classList.remove("show");
        }

        checkbox.addEventListener("change", function() {
            if (this.checked) {
                accordion.classList.remove("disable");
                accordion.querySelector(".accordion-collapse").classList.add("show");
                localStorage.setItem(checkboxId, "true");
            } else {
                accordion.classList.add("disable");
                accordion.querySelector(".accordion-collapse").classList.remove("show");
                localStorage.setItem(checkboxId, "false");
            }
        });
    }
});
