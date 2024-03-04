<?php

$astra_warning = '
    <div class="astra-warning-container">
        <h3>Warning!</h3>
        <p>Plugin setting Sticky Header for Astra and Astra Hello child theme is active, but Astra theme is not installed and active!</p>
    </div>
';

$active_theme = wp_get_theme()->get('Name');

if ($active_theme === 'Astra') {
    wp_enqueue_style('plugins-functionalities-style', plugins_url('plugins-functionalities.css', __FILE__));
    add_action('wp_head', 'astra_sticky_header');
} elseif ($active_theme === 'Astra Hello') {
    remove_action('wp_head', 'astra_sticky_header');
    return;
} else {
    remove_action('wp_head', 'astra_sticky_header');
    echo $astra_warning;
}

function astra_sticky_header() {
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
            var header = document.querySelector('.site-header');
            if (lastScrollTop > 0) {
                header.classList.add('site-header-fixed');
            }
        
            window.addEventListener("scroll", function(){
                var currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                if (currentScroll > lastScrollTop){
                    header.classList.add('site-header-fixed');
                } else {
                    if (currentScroll === 0) {
                        header.classList.remove('site-header-fixed');
                    }
                }
                lastScrollTop = currentScroll;
            }, false);
        });
    </script>
    <?php
}
