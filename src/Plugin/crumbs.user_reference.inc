<?php

/**
 * Implements hook_crumbs_plugins().
 *
 * @param crumbs_InjectedAPI_hookCrumbsPlugins $api
 */
function user_reference_crumbs_plugins($api) {
  // @FIXME
// Fields and field instances are now exportable configuration entities, and
// the Field Info API has been removed.
// 
// 
// @see https://www.drupal.org/node/2012896
// foreach (field_info_fields() as $field_name => $field_info) {
//     if (1
//       && 'user_reference' === $field_info['type']
//       && '1' === '' . $field_info['cardinality']
//     ) {
//       $plugin = new crumbs_EntityPlugin_Field_UserReference($field_name, $field_info['bundles']);
//       $api->entityParentPlugin($field_name, $plugin, array_keys($field_info['bundles']));
//     }
//   }

  $api->disabledByDefault('*');
}
