<?php
/**
 * Sidebar template
 */
?>
<aside id="secondary" class="widget-area">
  <?php
  if (is_active_sidebar("sidebar-1")) {
    dynamic_sidebar("sidebar-1");
  } else {
    echo '<section class="widget"><h2 class="widget-title">' . esc_html__(
      "No widgets",
      "mytheme"
    ) . '</h2>';
    echo '<p>' . esc_html__(
      "Add widgets in Appearance → Widgets",
      "mytheme"
    ) . "</p></section>";
  }
  ?>
</aside>