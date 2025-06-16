<?php
// Get ACF fields
$name             = get_field('event_name');
$registration_url = get_field('event_registration_url');
$playback_url     = get_field('event_playback_url');
$speakers         = get_field('event_speakers');
?>

<?php if ($speakers) : ?>
    </div><?php // close outer container to go full width ?>
    <div class="event-speakers padding-t-b-70 light-grey-bg">
        <div class="cont-xs">
            <h2>Speakers</h2>
            <div class="event-speakers__list margin-t-40">
                <?php foreach ($speakers as $speaker) : ?>
                    <div class="event-speakers__speaker">
                        <?php 
                        $image = $speaker['event_speaker_image'] ?? null;
                        $name  = $speaker['event_speaker_name'] ?? '';
                        $role  = $speaker['event_speaker_role'] ?? '';

                        $image_url = $image ? $image['sizes']['thumbnail-square'] : get_template_directory_uri() . '/images/placeholder-thumbnail-square.png';

                        if ($image_url) :
                            echo '<img src="' . esc_url($image_url) . '" alt="' . $name . ' profile" />';
                        endif;
                        if ($name) :
                            echo '<h3 class="fs-300 fw-regular">' . $name . '</h3>';
                        endif;
                        if ($role) :
                            echo '<h4 class="fs-300 fw-regular grey-text">' . $role . '</h4>';
                        endif; 
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="cont-xs padding-t-b-40">
<?php endif; ?>

<?php if ($registration_url && !$playback_url) : ?>
    <div class="event-register padding-t-40" id="event-register">
        <h2 class="fs400 ">Register to attend<?php if ($name) { echo " '" . esc_html($name) . "'"; }  ?></h2>
        <div class="padding-30 margin-t-20 margin-b-20 light-grey-bg">
            <iframe width="100%" height="560" frameborder="0" src="<?php echo $registration_url; ?>" title="<?php echo $name ? $name : "Zotefoams Webinar"; ?> | Zotefoams Plc"></iframe>
        </div>
        <p>For more information, please contact <a href="mailto:marketing@zotefoams.com">marketing@zotefoams.com</a>.</p>
    </div>
<?php endif; ?>