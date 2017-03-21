<?php

/**
 * Theme callback for form elements with "#type" = "crumbs_weights_textual".
 *
 * @param $vars
 * @return bool|string
 *   Rendered HTML
 */
function theme_crumbs_weights_textual(&$vars) {

  $element = $vars['element'];
  return \Drupal::service("renderer")->render($element['text']);
}
