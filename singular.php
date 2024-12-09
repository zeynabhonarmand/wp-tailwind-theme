<?php

get_header();

if (have_posts()): ?>
    <section class="single-article max-w-screen-lg mx-auto">
        <?php while (have_posts()):
            the_post();
            ?>
            <div class="flex pb-5">
                <div class="date w-40 text-left pt-6 fs-12 fw-700">
                    <?= get_the_date() ?>
                </div>

            </div>
            <div class="article-content inst-context text-justify">
                <?php the_content() ?>

            </div>

        <?php endwhile ?>
    </section>
<?php endif;
get_footer() ?>