<div id="default" class="tab-pane">
<div class="row">
<div class="span4">
<h4>
  <i class="icon-cog"></i> 基本情報
  <small>
    <a href="#" class="edit-anchor" data-target="#vendor-default">
      <i class="icon icon-pencil"></i>
    </a>
  </small>
</h4>
<div id="vendor-default">
<?php wp_nonce_field( 'vendor_default', '_neosystem_admin_nonce' ); ?>
<table class="table table-hover table-bordered">
<tbody>

<tr>
<th>ネオ屋台ID</th>
<td>
  <?php echo absint( $post->serial ); ?>
</td>
</tr>
<?php
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'text',
    'data-value' => esc_attr( get_the_title() ),
    'data-name' => 'post-title',
    'data-class' => 'span3',
    'data-required' => ''
);
$title_html = '<span ';
foreach ( $arr as $key => $val ) {
    $title_html .= $key . '="' . $val . '" ';
}
$title_html .= '>';
$title_html .= get_the_title();
$title_html .= '</span>';
?>
<tr>
<th>事業者名</th>
<td>
  <?php echo $title_html; ?>
</td>
</tr>
<?php
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'text',
    'data-value' => esc_attr( $post->organization ),
    'data-name' => 'organization',
    'data-class' => 'span3'
);
$org_html = '<span ';
foreach ( $arr as $key => $val ) {
    $org_html .= $key . '="' . $val . '" ';
}
$org_html .= '>';
$org_html .= esc_html( $post->organization );
$org_html .= '</span>';
?>
<tr>
<th>会社名</th>
<td>
<?php echo $org_html; ?>
</td>
</tr>
<?php
/*
$url = 'http://www.w-tokyodo.com/';
$safe_url = esc_url( $url );
$res = wp_remote_get( 'http://capture.heartrails.com/api/get_title/?' . $safe_url );
if ( !is_wp_error( $res ) && $res['response']['code'] == 200 ) {
    $str = json_decode( $res['body'] )->title;
} else {
    $str = $safe_url;
}
*/
?>
<tr>
<th>WEB Site</th>
<td>
<?php //printf( '<a href="%s">%s</a>', $safe_url, $str ); ?>
</td>
</tr>

</tbody>
</table>
</div><!-- /#vendor-default -->
</div><!-- /.span4 -->

<div class="span8">
<?php
if ( $pman_cmid = get_post_meta( get_the_ID(), 'pman_cmid', true ) ) {
  $args = array(
    "headers" => array(
      "Authorization" => "Basic ".base64_encode("119072:d8e326"),
    )
  );
  $pman_uri = sprintf( 'https://www.p-man.net/neoyatai_admin/sales02.php?year=%d&month=%d&cmid=%d', date( 'Y' ), date( 'm' ), $pman_cmid );
  $response = wp_remote_get( $pman_uri, $args);
  if( !is_wp_error( $response ) && $response["response"]["code"] === 200 ) {
    //$resb = mb_convert_encoding( $response['body'], 'UTF-8', 'SJIS' );
    $domDoc = new DOMDocument();
    $domDoc -> loadHTML( $response['body'] );
    $xmlStr = $domDoc -> saveXML();
    $xmlObj = simplexml_load_string( $xmlStr );
    $xmlArr = json_decode( json_encode( $xmlObj ), true);
    $tr_arr = $xmlArr['body']['table']['tr'];
    $tr_c = count( $tr_arr );
    $array = array();
    for ( $n = 2; $n < $tr_c - 1; $n++ ) {
      $td = $tr_arr[$n]['td'];
      $arr = array();
      $arr['date'] = date( 'n/j', strtotime( $td[0] ) );
      $arr['event'] = $td[1];
      $arr['car'] = $td[8];
      $array[] = $arr;
    }
    echo '<pre>';
    var_dump( $array );
    var_dump( $response['body'] );
    echo '</pre>';
  }
}
?>
</div>

</div><!-- /.row -->
</div><!-- /#default -->