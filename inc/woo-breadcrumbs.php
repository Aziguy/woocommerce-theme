<?php
    /*!
     * Woocommerce Breadcrumb
     * Description: A lightweight, customisable function to generate and display a breadcrumb for our WordPress Theme.
     * Version: 1.1
     * Author: Hippolyte
     * Url: https://aziguy.github.io/portfolio/
     * License: http://www.apache.org/licenses/LICENSE-2.0
     */

     namespace App;

     class WOO_Breadcrumbs {
        
        public static function woo_generate_breadcrumbs($list_style = 'ul', $list_id = 'breadcrumb', $list_class = 'breadcrumb__list', $active_class = 'active__breadcrumb', $echo = false) {

            $breadcrumb = '<' . $list_style . ' id="' . $list_id . '" class="' . $list_class . '">';

            $breadcrumb .= self::generateHomeBreadcrumb($active_class);
            $breadcrumb .= self::generateBlogArchiveBreadcrumb($active_class);
            $breadcrumb .= self::generateArchiveBreadcrumb($active_class);
            $breadcrumb .= self::generatePostBreadcrumb($active_class);
            $breadcrumb .= self::generatePageBreadcrumb($active_class);
            $breadcrumb .= self::generateAttachmentBreadcrumb($active_class);
            $breadcrumb .= self::generateSearchBreadcrumb($active_class);
            $breadcrumb .= self::generate404Breadcrumb($active_class);
            $breadcrumb .= self::generatePostTypeArchiveBreadcrumb($active_class);
            $breadcrumb .= self::generateCustomTaxonomiesBreadcrumb($active_class);
            $breadcrumb .= self::generateCustomPostTypesBreadcrumb($active_class);

            // Close list
            $breadcrumb .= '</' . $list_style . '>';

            // Ouput
            if ($echo) {
                echo $breadcrumb;
            } else {
                return $breadcrumb;
            }
        }

        // Front page
        private static function generateHomeBreadcrumb($active_class) {
            if (is_front_page()) {
                return '<li class="breadcrumb__item ' . $active_class . '"><i class="fa fa-home" aria-hidden="true"></i> Accueil</li>';
            } else {
                return '<li class= "breadcrumb__item"><a class="breadcrumb__link" href="' . home_url() . '"><i class="fa fa-home" aria-hidden="true"></i> Accueil</a></li>';
            }
        }
        

        // Blog archive
        private static function generateBlogArchiveBreadcrumb($active_class){
            $breadcrumb = '';
            if ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
                $blog_page_id = get_option( 'page_for_posts' );

                if ( is_home() ) {
                    $breadcrumb .= '<li class="breadcrumb__item ' . $active_class . '">' . get_the_title( $blog_page_id )  . '</li>';
                } else if ( is_category() || is_tag() || is_author() || is_date() || is_singular( 'post' ) ) {
                    $breadcrumb .= '<li class="breadcrumb__item"><a class="breadcrumb__link" href="' . get_permalink( $blog_page_id ) . '"> <i class="fa fa-angle-right"></i> ' . get_the_title( $blog_page_id )  . '</a></li>';
                }
            }
            return $breadcrumb;
        }

        // Category, tag, author and date archives
        private static function generateArchiveBreadcrumb($active_class){
            $breadcrumb = '';
            if ( is_archive() && ! is_tax() && ! is_post_type_archive() ) {
                $breadcrumb .= '<li class="breadcrumb__item ' . $active_class . '">';

                // Title of archive
                if ( is_category() ) {
                    $breadcrumb .= single_cat_title( '', false );
                } else if ( is_tag() ) {
                    $breadcrumb .= single_tag_title( '', false );
                } else if ( is_author() ) {
                    $breadcrumb .= get_the_author();
                } else if ( is_date() ) {
                    if ( is_day() ) {
                        $breadcrumb .= get_the_time( 'F j, Y' );
                    } else if ( is_month() ) {
                        $breadcrumb .= get_the_time( 'F, Y' );
                    } else if ( is_year() ) {
                        $breadcrumb .= get_the_time( 'Y' );
                    }
                }

                $breadcrumb .= '</li>';
            }
            return $breadcrumb;
        }

        // Posts
        private static function generatePostBreadcrumb($active_class){
            $breadcrumb = '';
            if ( is_singular( 'post' ) ) {

                // Post categories
                $post_cats = get_the_category();

                if ( $post_cats[0] ) {
                    $breadcrumb .= '<li class="breadcrumb__item"> <i class="fa fa-angle-right"></i> <a class="breadcrumb__link" href="' . get_category_link( $post_cats[0]->term_id ) . '">' . $post_cats[0]->name . '</a></li>';
                }

                // Post title
                $breadcrumb .= '<li class="breadcrumb__item ' . $active_class . '"> <i class="fa fa-angle-right"></i> ' . get_the_title() . '</li>';
            }
            return $breadcrumb;
        }
        
        // Pages
        private static function generatePageBreadcrumb($active_class){
            $breadcrumb = '';
            if ( is_page() && ! is_front_page() ) {
                $post = get_post( get_the_ID() );

                // Page parents
                if ( $post->post_parent ) {
                    $parent_id  = $post->post_parent;
                    $crumbs = array();

                    while ( $parent_id ) {
                        $page = get_page( $parent_id );
                        $crumbs[] = ' <i class="fa fa-angle-right"></i> <a class="breadcrumb__link" href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
                        $parent_id  = $page->post_parent;
                    }

                    $crumbs = array_reverse( $crumbs );

                    foreach ( $crumbs as $crumb ) {
                        $breadcrumb .= '<li class="breadcrumb__item">' . $crumb . '</li>';
                    }
                }

                // Page title
                $breadcrumb .=  '<li class="breadcrumb__item ' . $active_class . '"> <i class="fa fa-angle-right"></i> ' . get_the_title() . '</li>';
            }
            return $breadcrumb;
        }

        // Attachments
        private static function generateAttachmentBreadcrumb($active_class){
            $breadcrumb = '';
            if ( is_attachment() ) {
                // Attachment parent
                $post = get_post( get_the_ID() );

                if ( $post->post_parent ) {
                    $breadcrumb .= '<li class="breadcrumb__item"> <i class="fa fa-angle-right"></i> <a class="breadcrumb__link" href="' . get_permalink( $post->post_parent ) . '">' . get_the_title( $post->post_parent ) . '</a></li>';
                }

                // Attachment title
                $breadcrumb .= '<li class="breadcrumb__item ' . $active_class . '"> <i class="fa fa-angle-right"></i> ' . get_the_title() . '</li>';
            }
            return $breadcrumb;
        }

        // Search
        private static function generateSearchBreadcrumb($active_class) {
            $breadcrumb = '';
            $theme = wp_get_theme();
            $text_domain = $theme->get('TextDomain'); // Retrieve text domain consistently

            if (is_search()) {
                $breadcrumb .= '<li class="breadcrumb__item ' . $active_class . '">' . __('Search', $text_domain) . '</li>';
            }

            return $breadcrumb;
        }

        // 404
        private static function generate404Breadcrumb($active_class) {
            $breadcrumb = '';
            $theme = wp_get_theme();
            $text_domain = $theme->get('TextDomain'); // Retrieve text domain consistently

            if (is_404()) {
                $breadcrumb .= '<li class="breadcrumb__item ' . $active_class . '">' . __('404', $text_domain) . '</li>';
            }

            return $breadcrumb;
        }


        // Custom Post Type Archive
        private static function generatePostTypeArchiveBreadcrumb($active_class) {
            $breadcrumb = '';
            
            if (is_post_type_archive()) {
                $breadcrumb .= '<li class="breadcrumb__item ' . $active_class . '">' . post_type_archive_title('', false) . '</li>';
            }
            return $breadcrumb;
        }

        // Custom Taxonomies
        private static function generateCustomTaxonomiesBreadcrumb($active_class) {
            $breadcrumb = '';
            $theme = wp_get_theme();
            $text_domain = $theme->get('TextDomain'); // Retrieve text domain consistently

            if (is_tax()) {
                $current_term = get_queried_object();
                $attached_to = array();
                $post_types = get_post_types();

                foreach ($post_types as $post_type) {
                    $taxonomies = get_object_taxonomies($post_type);

                    if (in_array($current_term->taxonomy, $taxonomies)) {
                        $attached_to[] = $post_type;
                    }
                }

                $output = false;

                foreach ($attached_to as $post_type) {
                    $cpt_obj = get_post_type_object($post_type);

                    if (!$output && get_post_type_archive_link($cpt_obj->name)) {
                        $breadcrumb .= '<li class="breadcrumb__item"> <i class="fa fa-angle-right"></i> <a class="breadcrumb__link" href="' . get_post_type_archive_link($cpt_obj->name) . '">' . $cpt_obj->labels->name . '</a></li>';
                        $output = true;
                    }
                }

                $breadcrumb .= '<li class="breadcrumb__item ' . $active_class . '"> <i class="fa fa-angle-right"></i> ' . single_term_title('', false) . '</li>'; // Use single_term_title consistently
            }
            return $breadcrumb;
        }

        // Custom Post Types
        private static function generateCustomPostTypesBreadcrumb($active_class) {
            if (!is_single() || get_post_type() === 'post' || get_post_type() === 'attachment') {
                return '';
            }
        
            $cpt_obj = get_post_type_object(get_post_type());
        
            if (get_post_type() === 'training' || 'tool') {
                return self::generateTrainingBreadcrumb($cpt_obj, $active_class);
            }
        
            return self::generateNonTrainingBreadcrumb($cpt_obj, $active_class);
        }
        
        private static function generateTrainingBreadcrumb($cpt_obj, $active_class) {
            $cpt_name_plural = $cpt_obj->labels->name . 's';
            $archive_link = rtrim(get_post_type_archive_link($cpt_obj->name), '/') . 's'; 
            $link = esc_url($archive_link);
        
            return '<li class="breadcrumb__item"> <i class="fa fa-angle-right"></i> <a class="breadcrumb__link" href="' . $link . '">' . $cpt_name_plural . '</a></li>'
                    . '<li class="breadcrumb__item ' . $active_class . '"> <i class="fa fa-angle-right"></i> ' . get_the_title() . '</li>';
        }
        
        private static function generateNonTrainingBreadcrumb($cpt_obj, $active_class) {
            $breadcrumb = '';
        
            if (is_post_type_hierarchical($cpt_obj->name)) {
                $breadcrumb .= self::generateHierarchicalBreadcrumb($cpt_obj);
            } else {
                $breadcrumb .= self::generateNonHierarchicalBreadcrumb($cpt_obj);
            }
        
            $breadcrumb .= '<li class="breadcrumb__item ' . $active_class . '"> <i class="fa fa-angle-right"></i> ' . get_the_title() . '</li>';
            return $breadcrumb;
        }
        
        private static function generateHierarchicalBreadcrumb($cpt_obj) {
            $post = get_post(get_the_ID());
            $breadcrumb = '';
        
            if (get_post_type_archive_link($cpt_obj->name)) {
                $breadcrumb .= '<li class="breadcrumb__item"> <i class="fa fa-angle-right"></i> <a class="breadcrumb__link" href="' . get_post_type_archive_link($cpt_obj->name) . '">' . $cpt_obj->labels->name . '</a></li>';
            }
        
            if ($post->post_parent) {
                $parent_id = $post->post_parent;
                $crumbs = array();
        
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $crumbs[] = ' <i class="fa fa-angle-right"></i> <a class="breadcrumb__link" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                    $parent_id = $page->post_parent;
                }
        
                $crumbs = array_reverse($crumbs);
        
                foreach ($crumbs as $crumb) {
                    $breadcrumb .= '<li class="breadcrumb__item">' . $crumb . '</li>';
                }
            }
        
            return $breadcrumb;
        }
        
        private static function generateNonHierarchicalBreadcrumb($cpt_obj) {
            $breadcrumb = '';
        
            if (get_post_type_archive_link($cpt_obj->name)) {
                $breadcrumb .= '<li class="breadcrumb__item"> <i class="fa fa-angle-right"></i> <a class="breadcrumb__link" href="' . get_post_type_archive_link($cpt_obj->name) . '">' . $cpt_obj->labels->name . '</a></li>';
            }
        
            $cpt_taxes = get_object_taxonomies($cpt_obj->name);
        
            if ($cpt_taxes && is_taxonomy_hierarchical($cpt_taxes[0])) {
                $cpt_terms = get_the_terms(get_the_ID(), $cpt_taxes[0]);
        
                if (is_array($cpt_terms)) {
                    $output = false;
        
                    foreach ($cpt_terms as $cpt_term) {
                        if (!$output) {
                            $breadcrumb .= '<li class="breadcrumb__item"> <i class="fa fa-angle-right"></i> <a class="breadcrumb__link" href="' . get_term_link($cpt_term->name, $cpt_taxes[0]) . '">' . $cpt_term->name . '</a></li>';
                            $output = true;
                        }
                    }
                }
            }
        
            return $breadcrumb;
        }        

    }
?>