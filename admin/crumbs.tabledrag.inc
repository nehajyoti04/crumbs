<?php

/**
 * Theme callback for form elements with "#type" = "crumbs_weights_tabledrag".
 *
 * @param array &$vars
 * @return string
 *   Rendered HTML
 */
function theme_crumbs_weights_tabledrag(&$vars) {

  $element = $vars['element'];

  $table = new crumbs_Admin_WeightsTable($element['#crumbs_plugin_info']);

  foreach (\Drupal\Core\Render\Element::children($element) as $key) {
    $child = $element[$key];
    $table->addElement($key, $child);
  }

  $rows = $table->getRows();

  // @FIXME
// TableDrag is now attached with the #tabledrag property of certain render
// arrays. drupal_add_tabledrag() is now internal and should never be called directly.
// 
// 
// @see https://www.drupal.org/node/2160571
// drupal_add_tabledrag('crumbs_weights_tabledrag', 'order', 'sibling', 'crumbs-weight-element');


  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// return theme('table', array(
//     'rows' => $rows,
//     'attributes' => array('id' => 'crumbs_weights_tabledrag'),
//   ));

}
