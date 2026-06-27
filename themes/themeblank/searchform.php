<?php $themeblank_unique_id = esc_attr(uniqid('search-form-')); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="search"
           id="<?php echo $themeblank_unique_id; ?>"
           class="search-field"
           placeholder="<?php esc_attr_e('Tìm kiếm...', 'themeblank'); ?>"
           value="<?php echo get_search_query(); ?>" name="s" aria-label="" />

    <button type="submit" class="btn search-submit">
        <i class="icon-theme-mask icon-theme-mask-magnifying-glass"></i>
    </button>
</form>