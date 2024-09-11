<?php

use WHMCS\Database\Capsule;

add_hook('ClientAreaFooterOutput', 1, function($vars) {
    // Fetch the current settings for the cookie banner
    $message = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'message')->value('value');
    $buttonText = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'buttonText')->value('value');
    $declineButtonText = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'declineButtonText')->value('value');
    $bgColor = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'bgColor')->value('value');
    $textColor = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'textColor')->value('value');
    $position = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'position')->value('value');
    $animation = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'animation')->value('value');
    $bannerWidth = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'bannerWidth')->value('value');
    $bannerHeight = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'bannerHeight')->value('value');
    $logo = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'logo')->value('value');

    // Accept button styles
    $acceptButtonBgColor = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'acceptButtonBgColor')->value('value');
    $acceptButtonTextColor = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'acceptButtonTextColor')->value('value');

    // Decline button styles
    $declineButtonBgColor = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'declineButtonBgColor')->value('value');
    $declineButtonTextColor = Capsule::table('tbladdonmodules')->where('module', 'cookie_banner')->where('setting', 'declineButtonTextColor')->value('value');

    // Define animation CSS classes
    $animationClass = ($animation === 'fade') ? 'fade-in' : 'slide-in';

    // Determine whether to position the banner at the top or bottom
    $positionCss = ($position === 'top') ? 'top: 0;' : 'bottom: 0;';

    // Include the external CSS file
    $cssUrl = '/modules/addons/cookie_banner/css/style.css';

    // Prepare the HTML for the logo (make sure to escape the $logo variable)
    $logoHtml = $logo ? '<img src="' . htmlspecialchars($logo) . '" alt="Logo" style="max-height: 50px; margin-right: 10px;">' : '';

    // Output the HTML and dynamically generated CSS
    return <<<HTML
        <link rel="stylesheet" href="{$cssUrl}">
        <style>
            .cookie-banner {
                background-color: {$bgColor};
                color: {$textColor};
                width: {$bannerWidth}; /* Dynamically apply width */
                height: {$bannerHeight}; /* Dynamically apply height */
                {$positionCss} /* Dynamically apply top or bottom */
            }
            .accept-button {
                background-color: {$acceptButtonBgColor};
                color: {$acceptButtonTextColor};
            }
            .decline-button {
                background-color: {$declineButtonBgColor};
                color: {$declineButtonTextColor};
            }
        </style>

        <div id="cookie-banner" class="cookie-banner {$animationClass}">
            {$logoHtml}
            <span>{$message}</span>
            <button id="accept-cookies" class="accept-button">{$buttonText}</button>
            <button id="decline-cookies" class="decline-button">{$declineButtonText}</button>
        </div>

        <script>
            document.getElementById('accept-cookies').addEventListener('click', function() {
                document.cookie = "cookie_consent=1; path=/; max-age=" + (60*60*24*365);
                document.getElementById('cookie-banner').style.display = 'none';
            });
            document.getElementById('decline-cookies').addEventListener('click', function() {
                document.getElementById('cookie-banner').style.display = 'none';
            });

            window.onload = function() {
                document.getElementById('cookie-banner').style.display = 'block';
            };
        </script>
HTML;
});
