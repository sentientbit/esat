<?php

// Funcția pentru redirecționare la pagina de mentenanță cu un anumit stil
function wpesat_maintenance_mode() {
    // Verificăm dacă suntem deja pe pagina de mentenanță
    if (!is_page('maintenance-page')) {
        // Alegem un stil pentru pagina de mentenanță (în funcție de preferințele tale)
        $style = 'style1'; // Schimbă această valoare cu stilul dorit (de ex. 'style2', 'style3')

        // Redirecționăm către pagina de mentenanță cu parametrul de stil
        wp_redirect(home_url('/maintenance-page/?style=' . $style));
        exit();
    }
}

// Adăugăm acțiunea pentru activarea modului de mentenanță
add_action('activate_maintenance_mode', 'wpesat_maintenance_mode');


if ( $maintenance === 'maintenance-style-1' ) {
    echo 
        '<!DOCTYPE html>
        <html>
            <head>
                <title>Site is down for maintenance</title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <style type="text/css">
                    body { text-align: center; padding: 10%; font: 20px Helvetica, sans-serif; color: #333; }
                    h1 { font-size: 50px; margin: 0; }
                    article { display: block; text-align: left; max-width: 650px; margin: 0 auto; }
                    a { color: #dc8100; text-decoration: none; }
                    a:hover { color: #333; text-decoration: none; }
                    @media only screen and (max-width : 480px) {
                        h1 { font-size: 40px; }
                    }
                </style>
            </head>
            <body>
                <article>
                    <h1>Site is temporarily unavailable.</h1>
                    <p>Scheduled maintenance is currently in progress. Please check back soon.</p>
                    <p>We apologize for any inconvenience.</p>
                    <p id="signature">&mdash; <a href="mailto:[Email]">[Name]</a></p>
                </article>
            </body>
        </html>'
} elseif ( $maintenance === 'maintenance-style-2' ) {
    echo
    '
    <!doctype html>
        <title>Site Maintenance</title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
        <style>
          html, body { padding: 0; margin: 0; width: 100%; height: 100%; }
          * {box-sizing: border-box;}
          body { text-align: center; padding: 0; background: #d6433b; color: #fff; font-family: Open Sans; }
          h1 { font-size: 50px; font-weight: 100; text-align: center;}
          body { font-family: Open Sans; font-weight: 100; font-size: 20px; color: #fff; text-align: center; display: -webkit-box; display: -ms-flexbox; display: flex; -webkit-box-pack: center; -ms-flex-pack: center; justify-content: center; -webkit-box-align: center; -ms-flex-align: center; align-items: center;}
          article { display: block; width: 700px; padding: 50px; margin: 0 auto; }
          a { color: #fff; font-weight: bold;}
          a:hover { text-decoration: none; }
          svg { width: 75px; margin-top: 1em; }
        </style>
        
        <article>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.24 202.24"><defs><style>.cls-1{fill:#fff;}</style></defs><title>Asset 3</title><g id="Layer_2" data-name="Layer 2"><g id="Capa_1" data-name="Capa 1"><path class="cls-1" d="M101.12,0A101.12,101.12,0,1,0,202.24,101.12,101.12,101.12,0,0,0,101.12,0ZM159,148.76H43.28a11.57,11.57,0,0,1-10-17.34L91.09,31.16a11.57,11.57,0,0,1,20.06,0L169,131.43a11.57,11.57,0,0,1-10,17.34Z"/><path class="cls-1" d="M101.12,36.93h0L43.27,137.21H159L101.13,36.94Zm0,88.7a7.71,7.71,0,1,1,7.71-7.71A7.71,7.71,0,0,1,101.12,125.63Zm7.71-50.13a7.56,7.56,0,0,1-.11,1.3l-3.8,22.49a3.86,3.86,0,0,1-7.61,0l-3.8-22.49a8,8,0,0,1-.11-1.3,7.71,7.71,0,1,1,15.43,0Z"/></g></g></svg>
            <h1>We&rsquo;ll be back soon!</h1>
            <div>
                <p>Sorry for the inconvenience. We&rsquo;re performing some maintenance at the moment. If you need to you can always follow us on <a href="http://www.twitter.com/">Twitter</a> for updates, otherwise we&rsquo;ll be back up shortly!</p>
                <p>&mdash; The [WEBSITE NAME] Team</p>
            </div>
        </article>
    '
} else {
    
}
