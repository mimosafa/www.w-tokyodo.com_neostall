<?php

function show_error_message() {
    global $error_array;
    if ( ! empty( $error_array ) ) {
        echo '<div class="container">';
        echo '<div id="error-message" class="alert alert-error fade in">';
        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        echo implode( '<br>', $error_array );
        echo '</div>';
        echo '</div>';
    }
}