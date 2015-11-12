<?php
global $options, $locationData;
?>
<div id="management" class="tab-pane">
<div id="location-data" data-option-table-wrapper>
<h4>出店エリア情報</h4>
<?php wp_nonce_field( 'series_location_data_management', '_neosystem_admin_nonce' ); ?>
<table class="table table-hover" data-option-table="locationData" data-number="<?php echo count( $locationData ); ?>">
  <thead>
    <tr>
      <th>#</th>
      <th>地域</th>
      <th>住所</th>
      <th>施設名</th>
      <th>出店エリア</th>
      <th>緯度経度</th>
    </tr>
  </thead>
  <tbody>
    <tr class="hide" data-option-table-template>
      <td></td>
      <td><input type="hidden" class="span2" data-option-table-form="region" /></td>
      <td><input type="text" data-option-table-form="address" /></td>
      <td><input type="text" class="span2" data-option-table-form="site" /></td>
      <td><input type="text" class="span2" data-option-table-form="areaDetail" /></td>
      <td><input type="text" class="span2" data-option-table-form="latlng" /></td>
    </tr>
<?php
if ( isset( $locationData ) ) {
    $n = 0;
    foreach ( $locationData as $data ) {
        $region = get_term( $data['region'], 'region' )->name;
?>
    <tr data-option-table-exists="<?php echo $n; ?>">
      <td><?php echo ( $n + 1 ); ?></td>
      <td><?php echo $region; ?></td>
      <td><?php echo $data['address']; ?></td>
      <td><?php echo $data['site']; ?></td>
      <td><?php echo $data['areaDetail']; ?></td>
      <td><?php echo $data['latlng']; ?></td>
    </tr>
<?php
        $n++;
    }
} else {
?>
    <tr data-option-table-noitem>
      <td colspan="6"><p class="text-center">出店エリア情報が設定されていません。</p></td>
    </tr>
<?php
}
?>
  </tbody>
</table>

<div class="clearfix">
  <div class="btn-group pull-right">
    <button type="button" class="btn btn-link" data-option-table-toggle="add"><i class="icon icon-plus"></i> 出店エリア追加</button>
    <button type="button" class="btn btn-link" data-option-table-toggle="edit"><i class="icon icon-pencil"></i> 編集</button>
    <button type="button" class="btn btn-link hide" data-option-table-toggle="cancel"><i class="icon icon-remove"></i> キャンセル</button>
    <button type="submit" class="btn btn-link hide" data-option-table-toggle="submit"><i class="icon icon-ok"></i> 登録</button>
  </div>
</div>

</div><!-- /#location-data -->

<div id="other-data" data-editable>
<h4>その他</h4>
<?php wp_nonce_field( 'series_options', '_neosystem_admin_nonce' ); ?>
<table class="table table-hover">
  <tbody>
<?php
if ( $front_end_archive = $options['front_end_archive'] )
    $web_archive = 'WEBサイト上でアーカイブする';
else
    $web_archive = 'WEBサイト上でアーカイブしない';
?>
    <tr data-row>
      <th>WEB ARCHIVE</th>
      <td data-cell data-value="<?php echo $front_end_archive; ?>">
        <span data-option-label><?php echo $web_archive; ?></span>
        <div class="hide" data-option>
          <select name="options[front_end_archive]" style="margin-bottom:0;">
            <option value="1"<?php if ( $front_end_archive ) echo ' selected="selected"' ?>>WEBサイト上でアーカイブする</option>
            <option value="0"<?php if ( !$front_end_archive ) echo ' selected="selected"' ?>>WEBサイト上でアーカイブしない</option>
          </select>
        </div>
      </td>
      <td style="border-top:none;">
        <a href="#" data-edit><i class="icon icon-pencil"></i> 編集</a>
        <div class="btn-group pull-right hide" data-toggles>
          <button type="button" class="btn" data-cancel><i class="icon icon-remove"></i> キャンセル</button>
          <button type="submit" class="btn" data-submit disabled="disabled"><i class="icon icon-ok"></i> 登録</button>
        </div>
      </td>
    </tr>
    <tr data-row>
      <th>関連WEBサイト</th>
      <td></td>
      <td>
        <a href="#" data-edit><i class="icon icon-pencil"></i> 編集</a>
      </td>
    </tr>
  </tbody>
</table>

</div><!-- /#other-data -->

<pre><?php var_dump( $options ); ?></pre>

</div><!-- /#management -->