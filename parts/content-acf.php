<?php
if( function_exists('get_field') ) {

    // --- event posts AND you need to reference this in the html!:) ---
    if ( has_category('event') ) {
        $event_tag = get_field('event_tag');       // event field
        $date = get_field('date');                // event date field
        $time = get_field('time');                // event time field
        $location = get_field('location');        // event location field
        $registration = get_field('registration_link'); // event registration link

        echo '<div class="acf-field acf-event" style="margin-top:30px;padding:20px;border-radius:8px;background:#f7f7f7;">';
        
        if( $event_tag ) echo '<p><strong>Event Tag:</strong> ' . esc_html($event_tag) . '</p>';
        if( $date ) echo '<p><strong>Date:</strong> ' . esc_html($date) . '</p>';
        if( $time ) echo '<p><strong>Time:</strong> ' . esc_html($time) . '</p>';
        if( $location ) echo '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
        if( $registration ) echo '<p><strong>Registration:</strong> <a href="'.esc_url($registration).'" target="_blank">Click here</a></p>';

        echo '</div>';
    }

    // --- community posts ---
    if ( has_category('community') ) {
        $platform = get_field('platform_location'); // community platform
        $link = get_field('link');                 // community website
        $location = get_field('location');         // optional community location

        echo '<div class="acf-field acf-community" style="margin-top:30px;padding:20px;border-radius:8px;background:#e9f5ff;">';
        
        if( $location ) echo '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
        if( $platform ) echo '<p><strong>Platform:</strong> ' . esc_html($platform) . '</p>';
        if( $link ) echo '<p><strong>Website:</strong> <a href="'.esc_url($link).'" target="_blank">Visit</a></p>';

        echo '</div>';
    }
}
