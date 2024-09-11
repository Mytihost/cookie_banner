<?php

use WHMCS\Database\Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function cookie_banner_config() {
    return [
        'name' => 'Custom Cookie Banner',
        'description' => 'An addon to display a custom cookie banner with customizable styling, positioning, and animations.',
        'version' => '1.4',
        'author' => 'Your Name',
        'fields' => [] // Settings are handled in the admin UI
    ];
}

function cookie_banner_activate() {
    Capsule::table('tbladdonmodules')->insert([
        ['module' => 'cookie_banner', 'setting' => 'message', 'value' => 'This site uses cookies to enhance your experience.'],
        ['module' => 'cookie_banner', 'setting' => 'buttonText', 'value' => 'I Accept'],
        ['module' => 'cookie_banner', 'setting' => 'declineButtonText', 'value' => 'Decline'],
        ['module' => 'cookie_banner', 'setting' => 'bgColor', 'value' => '#333'],
        ['module' => 'cookie_banner', 'setting' => 'textColor', 'value' => '#fff'],
        ['module' => 'cookie_banner', 'setting' => 'position', 'value' => 'bottom'], // Default position
        ['module' => 'cookie_banner', 'setting' => 'animation', 'value' => 'fade'],  // Default animation
        ['module' => 'cookie_banner', 'setting' => 'logo', 'value' => ''],
        ['module' => 'cookie_banner', 'setting' => 'bannerWidth', 'value' => '100%'],  // Default width
        ['module' => 'cookie_banner', 'setting' => 'bannerHeight', 'value' => 'auto'],  // Default height
        ['module' => 'cookie_banner', 'setting' => 'acceptButtonBgColor', 'value' => '#4CAF50'],
        ['module' => 'cookie_banner', 'setting' => 'acceptButtonTextColor', 'value' => '#fff'],
        ['module' => 'cookie_banner', 'setting' => 'declineButtonBgColor', 'value' => '#f44336'],
        ['module' => 'cookie_banner', 'setting' => 'declineButtonTextColor', 'value' => '#fff'],
    ]);

    return [
        'status' => 'success',
        'description' => 'The cookie banner module has been activated with customizable styling, positioning, and animations.',
    ];
}

function cookie_banner_output($vars) {
    // Handling form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Fetch submitted values with fallback defaults
        $message = isset($_POST['message']) ? $_POST['message'] : 'This site uses cookies to enhance your experience.';
        $buttonText = isset($_POST['buttonText']) ? $_POST['buttonText'] : 'I Accept';
        $declineButtonText = isset($_POST['declineButtonText']) ? $_POST['declineButtonText'] : 'Decline';
        $bgColor = isset($_POST['bgColor']) ? $_POST['bgColor'] : '#333';
        $textColor = isset($_POST['textColor']) ? $_POST['textColor'] : '#fff';
        $position = isset($_POST['position']) ? $_POST['position'] : 'bottom';  // Default position
        $animation = isset($_POST['animation']) ? $_POST['animation'] : 'fade';  // Default animation
        $bannerWidth = isset($_POST['bannerWidth']) ? $_POST['bannerWidth'] : '100%';  // Default width
        $bannerHeight = isset($_POST['bannerHeight']) ? $_POST['bannerHeight'] : 'auto';  // Default height
        $logo = '';

        // Handle logo upload if provided
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/uploads/';  // Uploads directory within the addon directory
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);  // Create the uploads directory if it doesn't exist
            }

            $targetFile = $targetDir . basename($_FILES['logo']['name']);
            move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile);
            $logo = 'modules/addons/cookie_banner/uploads/' . basename($_FILES['logo']['name']);
        }

        // Save all settings into the database
        Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'message'], ['value' => $message]);
        Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'buttonText'], ['value' => $buttonText]);
        Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'declineButtonText'], ['value' => $declineButtonText]);
        Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'bgColor'], ['value' => $bgColor]);
        Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'textColor'], ['value' => $textColor]);
        Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'position'], ['value' => $position]);
        Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'animation'], ['value' => $animation]);
        Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'bannerWidth'], ['value' => $bannerWidth]);
        Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'bannerHeight'], ['value' => $bannerHeight]);
        if ($logo) {
            Capsule::table('tbladdonmodules')->updateOrInsert(['module' => 'cookie_banner', 'setting' => 'logo'], ['value' => $logo]);
        }

        echo '<div class="alert alert-success">Settings saved successfully!</div>';
    }

    // Fetch current settings from the database to prefill the form
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

    // Ensure the logo is accessible via the correct URL path
    $logoUrl = $logo ? 'modules/addons/cookie_banner/uploads/' . basename($logo) : '';  // Relative URL to the uploads directory

    // Prepare logo HTML (ensure proper escaping of variables)
    $logoHtml = $logoUrl ? '<img src="' . htmlspecialchars($logoUrl) . '" alt="Logo" style="max-height: 100px; margin-top: 10px;">' : '';

    // Output the form to manage banner settings, including width, height, positioning, and animations
    echo <<<HTML
    <h3>Cookie Banner Settings</h3>
    <form method="post" enctype="multipart/form-data">
        <!-- Banner Message (Replaced with textarea) -->
        <div class="form-group">
            <label for="message">Banner Message</label>
            <textarea id="message" name="message" class="form-control" style="width: 50%;" rows="6">{$message}</textarea>
        </div>
        <!-- Accept Button Text -->
        <div class="form-group">
            <label for="buttonText">Accept Button Text</label>
            <input type="text" id="buttonText" name="buttonText" class="form-control" style="width: 8%;" value="{$buttonText}">
        </div>
        <!-- Decline Button Text -->
        <div class="form-group">
            <label for="declineButtonText">Decline Button Text</label>
            <input type="text" id="declineButtonText" name="declineButtonText" class="form-control" style="width: 8%;" value="{$declineButtonText}">
        </div>
        <!-- Banner Background Color -->
        <div class="form-group">
            <label for="bgColor">Banner Background Color</label>
            <input type="color" id="bgColor" name="bgColor" class="form-control" style="width: 8%;" value="{$bgColor}">
        </div>
        <!-- Banner Text Color -->
        <div class="form-group">
            <label for="textColor">Banner Text Color</label>
            <input type="color" id="textColor" name="textColor" class="form-control" style="width: 8%;" value="{$textColor}">
        </div>
        <!-- Banner Width -->
        <div class="form-group">
            <label for="bannerWidth">Banner Width</label>
            <input type="text" id="bannerWidth" name="bannerWidth" class="form-control" style="width: 8%;" value="{$bannerWidth}">
        </div>
        <!-- Banner Height -->
        <div class="form-group">
            <label for="bannerHeight">Banner Height</label>
            <input type="text" id="bannerHeight" name="bannerHeight" class="form-control" style="width: 8%;" value="{$bannerHeight}">
        </div>
        <!-- Banner Position -->
        <div class="form-group">
            <label for="position">Banner Position</label>
            <select id="position" name="position" class="form-control" style="width: 8%;">
                <option value="top" {$positionTopSelected}>Top</option>
                <option value="bottom" {$positionBottomSelected}>Bottom</option>
            </select>
        </div>
        <!-- Animation -->
        <div class="form-group">
            <label for="animation">Animation</label>
            <select id="animation" name="animation" class="form-control" style="width: 8%;">
                <option value="fade" {$animationFadeSelected}>Fade</option>
                <option value="slide" {$animationSlideSelected}>Slide</option>
            </select>
        </div>
        <!-- Banner Logo -->
        <div class="form-group">
            <label for="logo">Banner Logo</label>
            <input type="file" id="logo" name="logo" class="form-control" style="width: 30% style="height: 30%;">
            <!-- Display the logo if available -->
            {$logoHtml}
        </div>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
HTML;
}
?>
