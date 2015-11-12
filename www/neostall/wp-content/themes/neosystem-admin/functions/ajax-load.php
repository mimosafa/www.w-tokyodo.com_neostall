<?php

if ( ! is_admin() ) :

    function neosystem_admin_ajax_laod() {

        global $post;

        /**
         * vandor.js
         */
        if ( is_singular( 'vendor' ) ) {

            // genres
            $terms = get_terms(
                'genre',
                array(
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'hide_empty' => false
                )
            );
            $genres = array();
            foreach ( $terms as $term ) {
                $genres[] = esc_attr( $term->name );
            }

            wp_localize_script(
                'vendor',
                'VENDOR',
                array(
                    'endpoint' => admin_url( 'admin-ajax.php' ),
                    'vendor_id' => $post->ID,
                    'genres' => $genres
                )
            );

        }

        /**
         * kitchencar.js
         */
        if ( is_singular( 'kitchencar' ) ) {
            wp_localize_script(
                'kitchencar',
                'KITCHENCAR',
                array(
                    'endpoint' => admin_url( 'admin-ajax.php' ),
                    'kitchencar_id' => $post->ID
                )
            );
        }

        /**
         * space.js
         */
        if ( is_singular( 'space' ) ) {

            wp_localize_script(
                'space',
                'MMSF',
                array(
                    'endpoint' => admin_url( 'admin-ajax.php' )
                )
            );
        }

        /**
         * series.js
         */
        if ( is_tax( 'series' ) ) {

            wp_localize_script(
                'series',
                'MMSF',
                array(
                    'endpoint' => admin_url( 'admin-ajax.php' )
                )
            );

        }

        /**
         * event.js
         */
        if ( is_singular( 'event' ) ) {

            wp_localize_script(
                'event',
                'MMSF',
                array(
                    'endpoint' => admin_url( 'admin-ajax.php' )
                )
            );

        }

        /**
         * archive-activity.js
         */
        if ( is_post_type_archive( 'activity' ) ) {

            wp_localize_script(
                'archive-activity',
                'MMSF',
                array(
                    'endpoint' => admin_url( 'admin-ajax.php' )
                )
            );

        }

        /**
         * management.js
         */
        if ( is_singular( 'management' ) ) {

            wp_localize_script(
                'management',
                'MMSF',
                array(
                    'endpoint' => admin_url( 'admin-ajax.php' )
                )
            );

        }

        /**
         * archive-space.js
         */
        if ( is_post_type_archive( 'space' ) ) {

            wp_localize_script(
                'archive-space',
                'MMSF',
                array(
                    'endpoint' => admin_url( 'admin-ajax.php' )
                )
            );

        }

// -------------------------------------------------------------------------------------------- TEST
        $array = array( 'endpoint' => admin_url( 'admin-ajax.php' ) );
        if ( is_singular( 'activity' ) ) {
            $kitchencar_id = absint( $post->actOf );
            $vendor_id = get_post( $kitchencar_id )->post_parent;
            $array['kitchencar_id'] = $kitchencar_id;
            $array['vendor_id'] = $vendor_id;
        }
        wp_localize_script(
            'neosystem_admin_main',
            'MMSF',
            $array
        );
// -------------------------------------------------------------------------------------------- TEST

    }
    add_action( 'wp_print_scripts', 'neosystem_admin_ajax_laod', 52 );

endif;