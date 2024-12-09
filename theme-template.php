<?php
/*
Template Name: About
*/

get_header();
?>

<section class="single-article max-w-screen-lg mx-auto pt-20">
    <?php while (have_posts()):
        the_post();
        $title = get_the_title();
        ?>
        <div class="article-content inst-context text-justify">
            <?php the_content() ?>
        </div>

    <?php endwhile ?>
</section>
<?php get_footer();