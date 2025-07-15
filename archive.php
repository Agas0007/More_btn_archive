
<!-- Вывод новостей по 6 штук -->


<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$category = get_queried_object(); // получаем текущую категорию

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 8,
    'paged' => $paged,
);

// если это категория, добавим условие
if (is_category() && isset($category->term_id)) {
    $args['cat'] = $category->term_id;
}

$query = new WP_Query($args);
?>

<div class="blog_archive m_bottom">
    <div class="grid_container blog_archive_wrapper" id="post-container">
        <?php if ($query->have_posts()): ?>
            <?php while ($query->have_posts()): $query->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="blog_archive_item">
                    <div class="blog_archive_img">
                        <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="" loading="lazy">
                    </div>
                    <div class="blog_archive_info">
                        <h2 class="blog_archive_title"><?php the_title(); ?></h2>
                        <div class="blog_archive_text"><?php echo get_the_excerpt(); ?></div>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>

    <?php if ( $query->found_posts > 6 ): ?>            
    <div class="load-more-wrap">
        <button id="load-more" data-page="1" data-max="<?php echo $query->max_num_pages; ?>">Показать ещё</button>
    </div>
    <?php endif; ?> 

</div>


 <!-- JS (AJAX)  для работы кнопки -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('load-more');
    const container = document.getElementById('post-container');

    if (!btn) return;

    btn.addEventListener('click', function () {
        let page = parseInt(btn.getAttribute('data-page')) + 1;
        const maxPages = parseInt(btn.getAttribute('data-max'));

        btn.textContent = 'Загрузка...';

        fetch('<?php echo admin_url("admin-ajax.php"); ?>?action=load_more_posts&page=' + page)
            .then(res => res.text())
            .then(data => {
                container.insertAdjacentHTML('beforeend', data);
                btn.setAttribute('data-page', page);
                btn.textContent = 'Показать ещё';

                if (page >= maxPages) {
                    btn.style.display = 'none';
                }
            });
    });
});
</script>

