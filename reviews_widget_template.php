<?php
/*
Reviews Widget Template
*/

?><?php
        if (!isset($title)) $title = '';
        else $title = __( $title, $this->plugin_slug );        
        $posts = "";
        $i=0;
        $args = array(
            'post_type' => $this->post_slug,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'suppress_filters'=>0,
        );
        get_template_part('includes/widget','full');
        
        $query = new WP_Query( $args );
        while ( $query->have_posts() ) {
        $query->the_post();
        $attach = get_post_thumbnail_id( );
        $media = '';
        if ( is_numeric( $attach ) ) { 
            $image_info = wp_get_attachment_image_src( $attach, 'thumbnail' ); 
            if ($image_info[0]) $media = "<a href=\"#\"><img class=\"img-circle media-object\" src=\"{$image_info[0]}\" alt=\"\"></a>";
        }
        $posts.="
                                <li class=\"col-sm-4 items col-xs-12\" id=\"i-".($i++)."\">
                                    <div class=\"caption\">".get_the_content()."</div>
                                    <div class=\"media\">
                                      <div class=\"media-left media-middle\">{$media}</div>
                                      <div class=\"media-body\">".get_the_title()."</div>
                                    </div>                                    
                                </li>

            ";
        }
        wp_reset_postdata();
        if (!isset($image)) $image = '';
        if (!isset($scheme)) $scheme = 'dark';
        $output ="
          <div id=\"reviews\" data-speed=\"2\" class=\"scheme-{$scheme}\" ". ( $image ? " style=\"background-image:url({$image})\"" : "" ) .">
                <div class=\"container\">
                  <div class=\"row row-title\"><div class=\"col-sm-12\"><h2 class=\"widget-title\">$title</h2></div></div>
                  <div id=\"reviews-inner\" class=\"container\">
                    <ul class=\"row review-items active\">
                    $posts
                    </ul>
                    <div class=\"jcarousel-pagination\"></div>
                  </div>
                </div>
          </div>
        ";
        print $output;