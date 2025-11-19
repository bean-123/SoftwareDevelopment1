<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// --- Post formats support ---
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
    function twentytwentyfive_post_format_setup() {
        add_theme_support( 'post-formats', array( 'aside','audio','chat','gallery','image','link','quote','status','video' ) );
    }
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// --- Editor style ---
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
    function twentytwentyfive_editor_style() {
        add_editor_style( 'assets/css/editor-style.css' );
    }
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// --- Enqueue styles and scripts ---
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'twentytwentyfive-style',
        get_parent_theme_file_uri( 'style.css' ),
        array(),
        wp_get_theme()->get( 'Version' )
    );

    if ( is_front_page() ) {
        wp_enqueue_script(
            'home-events-filter',
            get_stylesheet_directory_uri() . '/assets/js/home-events-filter.js',
            array(),
            false,
            true
        );
    }
});

// --- events shortcode ---
if ( ! function_exists('filter_events_shortcode') ) {
    function filter_events_shortcode() {
        $output = '<div class="events-grid">';
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 8,
            'category_name'  => 'event',
            'category__not_in'=> [ get_cat_ID('community') ] // exclude community posts!
        ];
        $query = new WP_Query($args);
        if (!$query->have_posts()) {
            $output .= '<p>No event posts found.</p>';
        } else {
            while ($query->have_posts()) {
                $query->the_post();
                $tags = wp_get_post_tags(get_the_ID(), ['fields'=>'names']);
                $output .= '<div class="filter-post" data-tags="'.implode(',', $tags).'">';
                $output .= '<h2>'.get_the_title().'</h2>';
                $output .= '<p>'.get_the_excerpt().'</p>';
                $output .= '<a href="'.get_permalink().'">Read more</a>';
                $output .= '</div>';
            }
        }
        wp_reset_postdata();
        $output .= '</div>';
        return $output;
    }
    add_shortcode('filter_events', 'filter_events_shortcode');
}

// --- communities shortcode ---
if ( ! function_exists('filter_communities_shortcode') ) {
    function filter_communities_shortcode() {
        $output = '<div class="communities-grid">';
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 8,
            'category_name'  => 'community',
        ];
        $query = new WP_Query($args);
        if (!$query->have_posts()) {
            $output .= '<p>No community posts found.</p>';
        } else {
            while ($query->have_posts()) {
                $query->the_post();
                $tags = wp_get_post_tags(get_the_ID(), ['fields'=>'names']);
                $output .= '<div class="filter-post" data-tags="'.implode(',', $tags).'">';
                $output .= '<h2>'.get_the_title().'</h2>';
                $output .= '<p>'.get_the_excerpt().'</p>';
                $output .= '<a href="'.get_permalink().'">Read more</a>';
                $output .= '</div>';
            }
        }
        wp_reset_postdata();
        $output .= '</div>';
        return $output;
    }
    add_shortcode('filter_communities', 'filter_communities_shortcode');
}

// --- append ACF fields to content ---
add_filter('the_content', function($content) {
    if (!is_singular('post')) return $content;

    $html = '';

    // --- event posts ---
    if (has_category('event')) {
        $event_tag    = get_field('event_tag');
        $date         = get_field('date');
        $time         = get_field('time');
        $location     = get_field('location');
        $registration = get_field('registration_link');

        if ($event_tag || $date || $time || $location || $registration) {
            $html .= '<div class="acf-event" style="margin-top:30px;padding:20px;border-radius:8px;background:#69013b;">';
            if ($date) $html .= '<p><strong>Date:</strong> ' . esc_html($date) . '</p>';
            if ($time) $html .= '<p><strong>Time:</strong> ' . esc_html($time) . '</p>';
            if ($location) $html .= '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
            if ($registration) $html .= '<p><strong>Registration:</strong> <a href="'.esc_url($registration).'" target="_blank">Click here</a></p>';
            $html .= '</div>';
        }
    }

    // --- community posts ---
    if (has_category('community')) {
        $platform = get_field('platform_location');
        $link     = get_field('link');
        $location = get_field('location');

        if ($platform || $link || $location) {
            $html .= '<div class="acf-community" style="margin-top:30px;padding:20px;border-radius:8px;background:#69013b;">';
            if ($location) $html .= '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
            if ($platform) $html .= '<p><strong>Platform:</strong> ' . esc_html($platform) . '</p>';
            if ($link) $html .= '<p><strong>Website:</strong> <a href="'.esc_url($link).'" target="_blank">Visit</a></p>';
            $html .= '</div>';
        }
    }

    return $content . $html;
});

function populate_acf_from_csv_data() {
    $csv_data = [
        // --- events (idk if i can do it automatically..) ---
        [
            'post_title' => 'Finland TalentMatch x Tech & IT',
            'date' => '2025-11-04',
            'time' => '09:00',
            'location' => 'Online',
            'registration_link' => 'https://www.workinfinland.com/fi/events/finland-talent-match-autumn/',
        ],
        [
            'post_title' => 'CollabDays Finland 2025',
            'date' => '2025-09-11',
            'time' => '',
            'location' => 'Clarion Helsinki Airport, Vantaa, Finland',
            'registration_link' => 'https://www.collabdays.org/2025-finland/',
        ],
        [
            'post_title' => 'ITS Finland Fall Seminar 2025',
            'date' => '2025-11-13',
            'time' => '',
            'location' => 'BioRex Tripla, Theater 1, Pasila, Helsinki',
            'registration_link' => 'https://its-finland.fi/en/event/its-finland-fall-seminar-13-11-2025/',
        ],
        [
            'post_title' => 'Slush Global Impact Track',
            'date' => '2025-11-21',
            'time' => '09:00',
            'location' => 'Messukeskus, Helsinki',
            'registration_link' => 'https://www.slush.org/events/global-impact-track/',
        ],
        [
            'post_title' => 'TechChill Online 2025',
            'date' => '2025-02-12',
            'time' => '09:00',
            'location' => 'Online',
            'registration_link' => 'https://techchill.co/',
        ],
        [
            'post_title' => 'European Cybersecurity Month 2025',
            'date' => '2025-10-01',
            'time' => '09:00',
            'location' => 'Various European locations',
            'registration_link' => 'https://www.enisa.europa.eu/events/ecm',
        ],
        [
            'post_title' => 'PyCon Finland 2025',
            'date' => '2025-06-10',
            'time' => '10:00',
            'location' => 'Helsinki',
            'registration_link' => 'https://www.pycon.fi/',
        ],
        [
            'post_title' => 'Global Game Jam 2025',
            'date' => '2025-01-31',
            'time' => '09:00',
            'location' => 'Helsinki / Online',
            'registration_link' => 'https://globalgamejam.org/',
        ],
        [
            'post_title' => 'Hack Finland 2025',
            'date' => '2025-08-15',
            'time' => '10:00',
            'location' => 'Helsinki',
            'registration_link' => 'https://hackfinland.fi/',
        ],

        // --- Communities ---
        [
            'post_title' => 'KAJSEC ry',
            'location' => 'Kajaani, Finland',
            'platform_location' => '',
            'link' => 'https://kajsec.fi/',
        ],
        [
            'post_title' => 'TIVIA ry',
            'location' => 'Finland nationwide',
            'platform_location' => '',
            'link' => 'https://tivia.fi/en/tivia',
        ],
        [
            'post_title' => 'HelSec ry',
            'location' => 'Helsinki, Finland',
            'platform_location' => '',
            'link' => 'https://helsec.fi/',
        ],
        [
            'post_title' => 'Aalto University Coding Club',
            'location' => 'Aalto University, Espoo',
            'platform_location' => '',
            'link' => 'https://www.aalto.fi/en/events',
        ],
        [
            'post_title' => 'Startup Sauna',
            'location' => 'Helsinki, Finland / Online',
            'platform_location' => '',
            'link' => 'https://startupsauna.com',
        ],
        [
            'post_title' => 'Helsinki Game Community',
            'location' => 'Helsinki, Finland / Online',
            'platform_location' => '',
            'link' => 'https://helsinkigameshub.com',
        ],
        [
            'post_title' => 'Women in Tech Finland',
            'location' => 'Finland / Online',
            'platform_location' => '',
            'link' => 'https://www.womenintech.fi',
        ],
        [
            'post_title' => 'Code for Finland',
            'location' => 'Helsinki, Finland / Online',
            'platform_location' => '',
            'link' => 'https://codeforfinland.fi',
        ],
        [
            'post_title' => 'Node.js Helsinki Meetup',
            'location' => 'Helsinki, Finland',
            'platform_location' => '',
            'link' => 'https://www.meetup.com/nodejs-helsinki/',
        ],
        [
            'post_title' => 'Startup Grind Helsinki',
            'location' => 'Helsinki, Finland / Online',
            'platform_location' => '',
            'link' => 'https://www.startupgrind.com/helsinki',
        ],
        [
            'post_title' => 'TechSoup Finland',
            'location' => 'Finland / Online',
            'platform_location' => '',
            'link' => 'https://www.techsoup.fi',
        ],
        [
            'post_title' => 'DevOps Community Finland',
            'location' => 'Finland / Online',
            'platform_location' => '',
            'link' => 'https://devopscommunity.fi',
        ],
    ];

    foreach ($csv_data as $row) {
        $post = get_page_by_title($row['post_title'], OBJECT, 'post');
        if ($post) {
            if (has_category('event', $post)) {
                update_field('date', $row['date'], $post->ID);
                update_field('time', $row['time'], $post->ID);
                update_field('location', $row['location'], $post->ID);
                update_field('registration_link', $row['registration_link'], $post->ID);
            }
            if (has_category('community', $post)) {
                update_field('location', $row['location'], $post->ID);
                update_field('platform_location', $row['platform_location'], $post->ID);
                update_field('link', $row['link'], $post->ID);
            }
        }
    }
}

// Run manually once when ure setting everything up!:)
// add_action('admin_init', 'populate_acf_from_csv_data');
