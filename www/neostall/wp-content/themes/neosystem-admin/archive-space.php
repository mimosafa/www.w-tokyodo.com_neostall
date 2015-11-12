<?php

get_header();

$url = get_post_type_archive_link( 'space' );

?>
<h3>Spaces</h3>

<div class="clearfix">
  <ul class="inline pull-right">
    <li>
      <button class="btn btn-link" type="button" data-edit-toggle>
        <i class="icon-plus"></i> スペースを追加
      </button>
    </li>
  </ul>
</div>


<div class="row hide" data-edit-field>
  <?php wp_nonce_field( 'add_new_space', '_neosystem_admin_nonce' ); ?>
  <div class="span4">
    <fieldset>
      <legend>基本情報</legend>
      <label for="space-name">スペース名称</label>
      <input type="text" id="space-name" name="space-name" class="span4" required />
      <label for="space-slug">スペースID <small>半角英数字</small></label>
      <input type="text" id="space-slug" name="space-slug" class="span3" required />
      <label for="serial">管理番号 (通し番号)</label>
      <input type="number" id="serial" name="serial" class="span2" required />
      <label for="phase">状態</label>
      <select id="phase" name="phase" class="span2">
        <option value="0">見込み</option>
        <option value="1">アクティブ</option>
        <option value="8">休止中</option>
        <option value="9">終了</option>
      </select>
      <label for="sche_type">スケジュールのタイプ</label>
      <select id="sche_type" name="sche_type" class="span2">
        <option value="fixed">固定スケジュール</option>
        <option value="rotate">ローテーション</option>
        <option value="flex">流動スケジュール</option>
      </select>
    </fieldset>
  </div>
  <div class="span4">
    <fieldset>
      <legend>所在地情報</legend>
      <label for="region">市区郡</label>
      <input type="hidden" id="region" name="space-region" class="span2" required />
      <label for="address" style="clear:left;padding-top:9.5px;">補記住所</label>
      <input type="text" id="address" name="address" class="span4" required />
      <label for="site">施設</label>
      <input type="text" id="site" name="site" class="span3" />
      <label for="areaDetail">出店エリア詳細</label>
      <input type="text" id="areaDetail" name="areaDetail" class="span3" />
      <label for="latlng">緯度経度</label>
      <input type="text" id="latlng" name="latlng" class="span3" />
    </fieldset>
  </div>
  <div class="span4">
    <fieldset>
      <legend>出店曜日</legend>
      <label class="checkbox">
        <input type="checkbox" name="dayname[]" value="monday" /> 月曜日
      </label>
      <label class="checkbox">
        <input type="checkbox" name="dayname[]" value="tuesday" /> 火曜日
      </label>
      <label class="checkbox">
        <input type="checkbox" name="dayname[]" value="wednesday" /> 水曜日
      </label>
      <label class="checkbox">
        <input type="checkbox" name="dayname[]" value="thursday" /> 木曜日
      </label>
      <label class="checkbox">
        <input type="checkbox" name="dayname[]" value="friday" /> 金曜日
      </label>
      <label class="checkbox">
        <input type="checkbox" name="dayname[]" value="saturday" /> 土曜日
      </label>
      <label class="checkbox">
        <input type="checkbox" name="dayname[]" value="sunday" /> 日曜日
      </label>
    </fieldset>
    <fieldset>
      <legend>営業時間</legend>
      <label for="starting">開始時間</label>
      <div class="input-append">
        <input type="time" id="starting" name="starting" class="span2" />
        <span class="add-on">
          <label class="checkbox">
            <input type="checkbox" name="startingPending" value="1" /> 予定
          </label>
        </span>
      </div>
      <label for="ending">終了時間</label>
      <div class="input-append">
        <input type="time" id="ending" name="ending" class="span2" />
        <span class="add-on">
          <label class="checkbox">
            <input type="checkbox" name="endingPending" value="1" /> 予定
          </label>
        </span>
      </div>
    </fieldset>
  </div>
  <hr class="span12">
  <div class="span12">
    <div class="btn-group pull-right">
      <button type="submit" class="btn btn-small" data-edit-submit disabled="disabled"><i class="icon icon-ok"></i> 登録</button>
      <button type="button" class="btn btn-small" data-edit-cancel><i class="icon icon-remove"></i> キャンセル</button>
    </div>
  </div>
</div>


<?php
if ( have_posts() ) :
?>
<table class="table table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>Space</th>
      <th>Type</th>
      <th>Pref</th>
      <th>Region</th>
      <th>Phase</th>
    </tr>
  </thead>
  <tbody>
<?php
    while ( have_posts() ) : the_post();
        $name = esc_html( get_the_title() );
        $public = $post->publication ? '' : ' [ <i class="icon-ban-circle text-error"></i> 非公開 ]';
        $stype = ( 'fixed' != $post->sche_type ) ? esc_html( $post->sche_type ) : '';
        // Pref & Region
        $pref = '';
        $region = '';
        $region_obj = get_region_tax_obj();
        if ( 0 != $region_obj->parent ) {
            $pref_obj = get_term( $region_obj->parent, 'region' );
            $pref .= esc_html( $pref_obj->name );
            $region .= esc_html( $region_obj->name );
        } else {
            $pref .= esc_html( $region_obj->name );
        }
        $phase = ( '1' != $post->phase ) ? esc_html( $post->phase ) : '';
?>
    <tr>
      <td>
        <?php echo (int) esc_html( $post->serial ); ?>
      </td>
      <td>
        <a href="<?php the_permalink(); ?>"><?php echo $name; ?></a><?php echo $public; ?>
      </td>
      <td>
        <?php echo $stype; ?>
      </td>
      <td>
        <?php echo $pref; ?>
      </td>
      <td>
        <?php echo $region; ?>
      </td>
      <td>
        <?php echo $phase; ?>
      </td>
    </tr>
<?php
    endwhile;
else :
?>
<p class="well">No Spaces.</p>
<?php
endif;
?>
  </tbody>
</table>

<?php
previous_posts_link();
next_posts_link();

if ( !empty( $_SERVER['argv'] ) )
    echo '<a href="' . $url . '">All Spaces</a>';
?>

<?php get_footer(); ?>