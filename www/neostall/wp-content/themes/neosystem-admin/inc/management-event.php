<?php

?>
<div id="management" class="tab-pane">
  <table class="table table-hover">
    <tbody>
      <tr>
        <th>イベント進捗</th>
        <td><?php echo esc_html( $post->phase ); ?></td>
      </tr>
      <tr>
        <th>WEB公開状況</th>
        <td><?php echo esc_html( $post->publication ); ?></td>
      </tr>
      <tr>
        <th>複数エリアでの出店</th>
        <td><?php echo esc_html( $post->multiarea ); ?></td>
      </tr>
    </tbody>
  </table>
</div><!-- /#management -->