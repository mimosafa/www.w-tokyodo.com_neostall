<?php

/**
 * Neoyatai Core Theme functions and difinitions.
 */

require_once( TEMPLATEPATH . '/class/class.php' );

/**
 * dom decoder
 */
class MmsfDom extends Decoder {

    function create( $htmltag, $text = '', $attribute = array(), $children = array() ) {
        $array = array(
            array(
                'element' => $htmltag,
                'text' => $text,
                'attribute' => $attribute,
                'children' => $children
            )
        );
        return $this->decode( $array );
    }

    function decode( $array ) {
        return $this->getArrayToHtmlString( $array );
    }

    function decho( $array ) {
        echo $this->decode( $array );
    }

}