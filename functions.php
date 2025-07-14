<?php

// Функция для отрабатывания Ajax 

add_action('wp_ajax_load_more_posts', 'load_more_posts_callback');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts_callback');

function load_more_posts_callback() {
    $paged = isset($_GET['page']) ? intval($_GET['page']) : 1;

    $query = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => 6,
        'paged' => $paged,
    ));

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post(); ?>
            <a href="<?php the_permalink(); ?>" class="blog_archive_item">
                <div class="blog_archive_img">
                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="" loading="lazy">
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
