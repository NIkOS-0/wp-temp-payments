<?php

// 3b. 404 Redirect: All public pages except personal_offer

add_action('template_redirect', function () {
    // Skip if in WP admin
    if (is_admin()) return;
    // Allow access to offer pages and login/logout pages
    if (is_singular('personal_offer')) return;
    if (in_array($GLOBALS['pagenow'] ?? '', ['wp-login.php'])) return;
    // Allow robots.txt, sitemap etc.
    if (is_robots() || is_feed() || is_trackback()) return;

    // Send a clean 404 screen for everyone else
    $home_url = home_url();
    $site_name = get_bloginfo('name');
    status_header(404);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html lang="ru"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Страница не найдена</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:#f8fafc;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
        .box{text-align:center;max-width:420px}
        .code{font-size:96px;font-weight:900;color:#e2e8f0;line-height:1;letter-spacing:-4px}
        h1{font-size:22px;font-weight:700;color:#1e293b;margin-top:8px}
        p{color:#64748b;margin-top:12px;font-size:15px;line-height:1.6}
    </style>
    </head><body>
    <div class="box">
        <div class="code">404</div>
        <h1>Страница не найдена</h1>
        <p>Запрошенная страница не существует или была удалена.</p>
    </div>
    </body></html>';
    exit;
}, 1); 
