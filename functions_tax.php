<?php

add_action('wp_ajax_load_more_otzyvy', 'load_more_otzyvy_callback');
add_action('wp_ajax_nopriv_load_more_otzyvy', 'load_more_otzyvy_callback');

function load_more_otzyvy_callback() {
    $paged   = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $term_id = isset($_GET['term_id']) ? intval($_GET['term_id']) : 0;

    $args = array(
        'post_type'      => 'otzyvy',
        'posts_per_page' => 6,
        'paged'          => $paged,
    );

    if ($term_id > 0) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category_otzyvy',
                'field'    => 'term_id',
                'terms'    => $term_id,
            ),
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post(); ?>
            <a href="<?php the_permalink(); ?>" class="blog_archive_item">
                <div class="blog_archive_img">
                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                </div>
                <div class="blog_archive_info">
                    <h2 class="blog_archive_title"><?php the_title(); ?></h2>
                    <div class="blog_archive_text"><?php echo get_the_excerpt(); ?></div>
                </div>
            </a>
        <?php }
        wp_reset_postdata();
    }

    wp_die();
}
