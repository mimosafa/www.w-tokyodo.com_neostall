<?php

get_header();

while ( have_posts() ) :

    the_post();

?>

<h3><?php the_title(); ?></h3>
<div id="single-main">

<?php

/* ------------------------------------ */

    if ( is_singular( 'kitchencar' ) ) {

?>

<div class="col-sm-4">

    <h4>
        基本情報
        <small><a href="#" class="mmsf-edit-toggle" data-target="#kitchencar-default"><i class="fa fa-pencil"></i></a></small>
    </h4>
    <div id="kitchencar-default">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>稼働状況</th>
                    <td><?php echo get_custom_field( 'phase' ); ?></td>
                </tr>
                <tr>
                    <th>車両名</th>
                    <td><?php echo esc_html( get_raw_title() ); ?></td>
                </tr>
                <tr>
                    <th>WEB表示</th>
                    <td><?php echo get_custom_field( 'name' ); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4>
        車両スペック
        <small><a href="#" class="mmsf-edit-toggle" data-target="#kitchencar-spec"><i class="fa fa-pencil"></i></a></small>
    </h4>
    <div id="kitchencar-spec">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>車両No.</th>
                    <td><?php echo get_custom_field( 'vin' ); ?></td>
                </tr>
                <tr>
                    <th>長さ</th>
                    <td><?php echo get_custom_field( 'length' ); ?></td>
                </tr>
                <tr>
                    <th>幅</th>
                    <td><?php echo get_custom_field( 'width' ); ?></td>
                </tr>
                <tr>
                    <th>高さ</th>
                    <td><?php echo get_custom_field( 'height' ); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="col-sm-8">

    <h4>
        提供メニューセット
        <small><a href="#" class="mmsf-edit-toggle" data-target="#kitchencar-menuitems"><i class="fa fa-pencil"></i></a></small>
    </h4>
    <div id="kitchencar-menuitems">
    </div>

</div>

<?php

    } elseif ( is_singular( 'space' ) ) {

        $class = new NeosystemSingular();
        $class->init();
/*
?>

<div class="col-sm-4">

    <h4>
        基本情報
        <small><a href="#" class="mmsf-edit-toggle" data-target="#space-default"><i class="fa fa-pencil"></i></a></small>
    </h4>
    <div id="space-default">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>#</th>
                    <td><?php echo get_custom_field( 'serial' ); ?></td>
                </tr>
                <tr>
                    <th>名称</th>
                    <td><?php echo esc_html( get_raw_title() ); ?></td>
                </tr>
                <tr>
                    <th>稼働状況</th>
                    <td><?php echo get_custom_field( 'phase' ); ?></td>
                </tr>
                <tr>
                    <th>WEB公開</th>
                    <td><?php echo get_custom_field( 'publication' ); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4>
        所在地情報
        <small><a href="#" class="mmsf-edit-toggle" data-target="#space-location"><i class="fa fa-pencil"></i></a></small>
    </h4>
    <div id="space-location">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>市区郡</th>
                    <td><?php echo get_the_region()->name; ?></td>
                </tr>
                <tr>
                    <th>補記住所</th>
                    <td><?php echo get_custom_field( 'address' ); ?></td>
                </tr>
                <tr>
                    <th>施設</th>
                    <td><?php echo get_custom_field( 'site' ); ?></td>
                </tr>
                <tr>
                    <th>詳細エリア</th>
                    <td><?php echo get_custom_field( 'areaDetail' ); ?></td>
                </tr>
                <tr>
                    <th>緯度経度</th>
                    <td><?php echo get_custom_field( 'latlng' ); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4>
        営業時間
        <small><a href="#" class="mmsf-edit-toggle" data-target="#space-time"><i class="fa fa-pencil"></i></a></small>
    </h4>
    <div id="space-time">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>開始</th>
                    <td><?php echo get_custom_field( 'starting' ); ?></td>
                </tr>
                <tr>
                    <th>終了</th>
                    <td><?php echo get_custom_field( 'ending' ); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="col-sm-8">

    <h4>
        スケジュール管理
        <small><a href="#" class="mmsf-edit-toggle" data-target="#space-schedule"><i class="fa fa-pencil"></i></a></small>
    </h4>
    <div id="space-schedule">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>スケジュール種別</th>
                    <td><?php echo get_custom_field( 'sche_type' ); ?></td>
                </tr>
                <tr>
                    <th>曜日</th>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<?php

/* ------------------------------------ */

    } else {

?>

<?php the_content(); ?>

<?

    }

endwhile;

?>

</div><!-- /#single-main /.row -->
<?php

/*
echo '<pre>';
$a = new NeosystemSingular();
$a->init();
//var_dump( $a->tabnav );
echo '</pre>';
*/

get_footer();

?>