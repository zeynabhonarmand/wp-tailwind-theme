<?php

$cl = new CustomPostType();
class CustomPostType
{
    function __construct()
    {
        foreach (get_class_methods(self::class) as $method) {
            if (substr($method, 0, strlen("register")) == "register")
                $this->{$method}();
        }

        CustomFields::add_text_field('event_url', "لینک",'event');
        CustomFields::add_attachment_field("event_poster", "پوستر", "image",'event');
    }

    static function poster()
    {
        $poster = get_post_custom_values("event_poster");
        // print_r($poster);
        if (!isset($poster[0]))
            return "";
        return "<img src={$poster[0]} />";
    }

    static function get_details()
    {
        $return = ["labels" => []];
        $link = get_post_custom_values("event_url");
        $return['url'] = isset($link[0]) ? $link[0] : '';
    }

    function registerPostType()
    {

        add_action('init', function () {
            register_post_type(
                'event',
                array(
                    'labels' => array(
                        'name' => __('رویدادها'),
                        'singular_name' => __('رویداد')
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'rewrite' => array('slug' => 'events'),
                    'show_in_rest' => true,
                    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
                    'taxonomies' => array('category', 'post_tag')
                )
            );
        });
    }
}
