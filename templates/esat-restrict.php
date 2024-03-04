<?php

function render_error_page_cdn($page_type) {
    global $wp_sat_rdur_error_image, $wp_sat_rdur_error_css, $site_access_now_formatted, $site_access;

    ob_start();
    ?>
    <div class="error-wrapper">
        <div class="error-container">
            <div class="info">
                <?php if ($page_type === 'admin'): ?>
                    <h1>Website Admin Panel Blocked</h1>
                    <p>This admin website panel has been blocked by <span class="developer">Sentient Bit</span></p>
                    <p>Reason: due to non-payment.</p>
                <?php else: ?>
                    <h1>Website Blocked</h1>
                    <p>This website has been blocked.</p>
                <?php endif; ?>
            </div>
            <?php if ($page_type === 'admin'): ?>
                <div class="lock-image-container"><img src="<?php echo $wp_sat_rdur_error_image; ?>" class="lock-image"></div>
                

             
                <div class="buttons">
                    <button onclick="window.location.href='<?php echo esc_url(home_url('/')); ?>'">Back to site</button>
                </div>
            <?php else: ?>
                <div class="lock-image-container"><img src="<?php echo $wp_sat_rdur_error_image; ?>" class="lock-image"></div>
            <?php endif; ?>
            <?php if ($page_type === 'admin'): ?>
                <p class="help-link">
                    <span>Do you think that's an error?</span>
                    <a rel="nofollow" href="https://sentientbit.com/contact" class="arrow-link" target="_blank">Get support
                        <span class="arrow-link__icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.49951 10C3.49951 9.58579 3.8353 9.25 4.24951 9.25L13.9391 9.25L11.2192 6.53036C10.9263 6.23748 10.9263 5.7626 11.2192 5.4697C11.512 5.17679 11.9869 5.17676 12.2798 5.46964L16.2802 9.46964C16.4209 9.6103 16.4999 9.80107 16.4999 10C16.4999 10.1989 16.4209 10.3897 16.2802 10.5304L12.2798 14.5304C11.9869 14.8232 11.512 14.8232 11.2192 14.5303C10.9263 14.2374 10.9263 13.7625 11.2192 13.4696L13.9391 10.75L4.24951 10.75C3.8353 10.75 3.49951 10.4142 3.49951 10Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
