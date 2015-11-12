<div id="schedule" class="tab-pane row">
<div class="span10 pull-right">
<?php

// global $term;

$schedule = (array) get_post_meta( $id, 'schedule', true );

if ( ! empty( $schedule ) ) :

?>

<table class="table table-hover" data-mmsf-edtbl="schedule">

<thead>
  <tr>
    <th>Date</th>
    <th>Name</th>
    <th>Post</th>
  </tr>
</thead>

<tbody>
  <tr class="hide" data-mmsf-edtbl-template>
    <td data-mmsf-edtbl-key="day">
      <input type="date" class="span2" />
    </td>
    <td data-mmsf-edtbl-key="name">
      <input type="text" class="span4" />
    </td>
    <td data-mmsf-edtbl-muted></td>
  </tr>

<?php

    foreach( $schedule as $oneday ) {

        $day = date( 'n/j D.', strtotime( $oneday['day'] ) );
        $name = esc_html( $oneday['name'] );
        $events = $oneday['post_id'];
        $num_events = count( $events );
        $handle = '';
        $event_permalink = '';
        if ( 2 > $num_events ) {
            if ( !empty( $events[0] ) ) {
                $event_permalink .= get_permalink( $events[0] );
                $handle .= sprintf( '<a href="%s">Event detail <i class="icon icon-arrow-right"></i></a>', $event_permalink );
            } else {
                $handle .= 'Still No Events';
            }
        }

?>

<tr data-mmsf-edtbl-item>
  <td><?php echo $day; ?></td>
  <td><?php echo $name; ?></td>
  <td><?php echo $handle; ?></td>
</tr>

<?php

    }

?>

</tbody>

</table>

<?php

endif;

?>
</div>
<div class="span2 pull-left">
  <ul class="nav nav-list">
    <li>
      <a href="#" data-mmsf-edtbl-toggle="add" data-mmsf-edtbl-target="schedule">
        <i class="icon icon-plus"></i> スケジュールを追加
      </a>
    </li>
  </ul>
</div>
</div><!-- /#schedule -->