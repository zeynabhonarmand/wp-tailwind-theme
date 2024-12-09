<?php
$cl = new SearchEndpoint();
class SearchEndpoint
{
    function __construct()
    {
        $this->create_endpoint();

    }

    function create_endpoint()
    {

        add_action('rest_api_init', function () {
            register_rest_route('part_institute/v1', '/search/', array(
                'methods' => 'GET',
                'callback' => [$this,'custom_search_all_post_types'],
                'permission_callback' => '__return_true'
            ));
        });
        
    }

    function custom_search_all_post_types($request) {
        $parameters = $request->get_params();
        $search_query = isset($parameters['q']) ? $parameters['q'] : '';
    
        $args = array(
            'post_type' => 'any', // Searches all post types
            'post_status' => 'publish',
            's' => $search_query
        );
    
        $query = new WP_Query($args);
        $posts = $query->get_posts();
    
        $data = array();
        // print_r($posts);
        foreach ($posts as $post) {
            $data[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                // 'content' => $post->post_content,
                'post_type' => $post->post_type,
                'permalink' => get_the_permalink($post->ID),
                'thumbnail' => get_the_post_thumbnail_url($post->ID, 'thumbnail')

            );
        }
    
        return new WP_REST_Response($data, 200);
    }

}