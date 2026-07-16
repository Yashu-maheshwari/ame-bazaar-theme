<?php
require_once('wordpress/wp-load.php');
echo \"=== CURRENT HERO STATE ===\n\";
\ = get_option('ame_bazaar_media_hero_desktop');
\ = get_option('ame_bazaar_media_hero_mobile');

echo \"Desktop Hero ID (ame_bazaar_media_hero_desktop): \" . var_export(\, true) . \"\n\";
echo \"Mobile Hero ID (ame_bazaar_media_hero_mobile): \" . var_export(\, true) . \"\n\";

if (\) {
    \ = wp_get_attachment_url(\);
    \ = get_attached_file(\);
    \ = basename(\);
    echo \"Desktop Attachment URL: \\n\";
    echo \"Desktop Attachment Filename: \\n\";
}
if (\) {
    \ = wp_get_attachment_url(\);
    \ = get_attached_file(\);
    \ = basename(\);
    echo \"Mobile Attachment URL: \\n\";
    echo \"Mobile Attachment Filename: \\n\";
}
