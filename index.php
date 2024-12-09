<?php

get_header(); ?>
<div class="max-w-screen-lg">
    <div class="grid grid-cols-1 md:grid-cols-3">
        <?php while (have_posts()) : the_post();        ?>
        <a href="<?=get_the_permalink()?>">
            <?php the_title();?>
        </a>
        <?php endwhile; ?>
    </div>

</div>



<?php get_footer() ?>