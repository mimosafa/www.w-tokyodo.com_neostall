<?php

/**
 * Functions for developer of this theme
 */

/**
 * リモートサーバー情報で条件分岐
 *
 * @param $addr REMOTE_ADDR: default is W.S.T.D. Office in Higashi-yaguchi, Kamata-ku.
 */
function remote_addr_check( $addr = '211.3.110.183' ) {
    if ( $_SERVER['REMOTE_ADDR'] === $addr )
        return true;
    return false;
}