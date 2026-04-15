<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$term = get_queried_object();

$args = array(
    'post_type'      => 'otzyvy',
    'posts_per_page' => 6,
    'paged'          => $paged,
);

if (is_tax('category_otzyvy') && isset($term->term_id)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'category_otzyvy',
            'field'    => 'term_id',
            'terms'    => $term->term_id,
        ),
    );
}

$query = new WP_Query($args);
?>

<div class="blog_archive m_bottom">
    <div class="grid_container blog_archive_wrapper" id="post-container">
        <?php if ($query->have_posts()): ?>
            <?php while ($query->have_posts()): $query->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="blog_archive_item">
                    <div class="blog_archive_img">
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                    </div>
                    <div class="blog_archive_info">
                        <h2 class="blog_archive_title"><?php the_title(); ?></h2>
                        <div class="blog_archive_text"><?php echo get_the_excerpt(); ?></div>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>

    <?php if ($query->max_num_pages > 1): ?>
        <div class="load-more-wrap">
            <button
                id="load-more"
                data-page="1"
                data-max="<?php echo $query->max_num_pages; ?>"
                data-term-id="<?php echo (is_tax('category_otzyvy') && isset($term->term_id)) ? $term->term_id : 0; ?>"
            >
                Показать ещё
            </button>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('load-more');
    const container = document.getElementById('post-container');

    if (!btn) return;

    btn.addEventListener('click', function () {
        let page = parseInt(btn.getAttribute('data-page')) + 1;
        const maxPages = parseInt(btn.getAttribute('data-max'));
        const termId = parseInt(btn.getAttribute('data-term-id')) || 0;

        btn.textContent = 'Загрузка...';

        fetch('<?php echo admin_url("admin-ajax.php"); ?>?action=load_more_otzyvy&page=' + page + '&term_id=' + termId)
            .then(res => res.text())
            .then(data => {
                if (data.trim() !== '') {
                    container.insertAdjacentHTML('beforeend', data);
                }

                btn.setAttribute('data-page', page);
                btn.textContent = 'Показать ещё';

                if (page >= maxPages || data.trim() === '') {
                    btn.style.display = 'none';
                }
            })
            .catch(() => {
                btn.textContent = 'Показать ещё';
            });
    });
});
</script>