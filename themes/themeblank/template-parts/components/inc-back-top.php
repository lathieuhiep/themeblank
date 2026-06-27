<?php

use ExtendSite\Admin\Options\Modules\GeneralOptions;

$opt_back_to_top = themeblank_opt(GeneralOptions::class)::get_back_to_top_enabled() ?? true;

if ( $opt_back_to_top ) :
?>

<div id="back-top">
    <a href="#">
        <i class="icon-theme-mask icon-theme-mask-chevron-up"></i>
    </a>
</div>

<?php
endif;