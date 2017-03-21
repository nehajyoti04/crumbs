<?php


/**
 * Form builder callback for admin/structure/crumbs/entity-parent/$type.
 *
 * @param array $form
 * @param array $form_state
 * @param string $entity_type
 *   E.g. 'node' or 'taxonomy_term'.
 *
 * @return array
 */
function crumbs_admin_entity_parent_form($form, &$form_state, $entity_type) {

  $info = \Drupal::entityManager()->getDefinition($entity_type);

  $text = <<<EOT
<p>You may specify a pattern for the parent path for each entity bundle.</p>
EOT;
  $doc = t($text);

  $text = <<<EOT
<p>Whatever you configure here, will be available on the Crumbs settings page as<br/>
crumbs.entityParent.$entity_type.[type]</p>
EOT;
  $doc .= t($text);

  if (!\Drupal::moduleHandler()->moduleExists('token')) {
    $text = <<<EOT
<p>You will get more dynamic possibilities, if you install the !token_module_link module.</p>
EOT;
    $doc .= t($text, array(
      '!token_module_link' => \Drupal::l('Token', \Drupal\Core\Url::fromUri('http://drupal.org/project/token')),
    ));
  }
  elseif (!\Drupal::moduleHandler()->moduleExists('entity_token')) {
    $text = <<<EOT
<p>You will get more interesting tokens, if you install the entity_token module.<br/>
(included in !entity_module_link module)</p>
EOT;
    $doc .= t($text, array(
      '!entity_module_link' => \Drupal::l('Entity', \Drupal\Core\Url::fromUri('http://drupal.org/project/entity')),
    ));
  }

  $form = array();
  $form['intro'] = array(
    '#markup' => $doc,
  );

  $form['crumbs_' . $entity_type . '_parent_patterns'] = array(
    '#title' => t('Bundles'),
    '#tree' => TRUE,
  );

  // @FIXME
// // @FIXME
// // The correct configuration object could not be determined. You'll need to
// // rewrite this call manually.
// $settings = variable_get('crumbs_' . $entity_type . '_parent_patterns', array());


  $element_defaults = array(
    '#type' => 'textfield',
    // The maximum length is quite arbitrary.
    // Pathauto has 1280, but we think that 512 is enough.
    '#maxlength' => 512,
  );
  if (\Drupal::moduleHandler()->moduleExists('token')) {
    $element_defaults['#element_validate'][] = 'token_element_validate';
    $element_defaults['#token_types'][] = $info['token type'];
  }

  if ($entity_type === 'user') {
    foreach (user_roles(TRUE) as $rid => $role) {
      $form['crumbs_' . $entity_type . '_parent_patterns'][$role] = array(
        '#title' => $role,
        '#description' => t('Crumbs parent path for users with role "!role"', array(
          '!role' => $role,
        )),
        '#default_value' => isset($settings[$role]) ? $settings[$role] : NULL,
      ) + $element_defaults;
    }
  }
  else {
    foreach ($info['bundles'] as $bundle_key => $bundle) {
      $form['crumbs_' . $entity_type . '_parent_patterns'][$bundle_key] = array(
        '#title' => t($bundle['label']),
        '#description' => t('Crumbs parent path for !plural of type "!bundle_label" (!bundle_key)', array(
          '!plural' => isset($info['plural label'])
            ? t($info['plural label'])
            : t('!type entities', array('!type' => t($info['label']))),
          '!bundle_label' => t($bundle['label']),
          '!bundle_key' => $bundle_key,
        )),
        '#default_value' => isset($settings[$bundle_key]) ? $settings[$bundle_key] : NULL,
      ) + $element_defaults;
    }
  }

  if (\Drupal::moduleHandler()->moduleExists('token')) {
    $form['token_help'] = array(
      '#title' => t('Replacement patterns'),
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );

    $form['token_help']['help'] = array(
      '#theme' => 'token_tree',
      '#token_types' => array($info['token type']),
    );
  }

  $form = system_settings_form($form);
  $form['#submit'][] = '_crumbs_admin_flush_cache';
  return $form;
}
