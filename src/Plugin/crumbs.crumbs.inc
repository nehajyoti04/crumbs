<?php


/**
 * Implements hook_crumbs_plugins().
 *
 * @param crumbs_InjectedAPI_hookCrumbsPlugins $api
 */
function crumbs_crumbs_plugins($api) {
  if (\Drupal::moduleHandler()->moduleExists('node')) {
    $api->entityRoute('node', 'node/%', 'type', 'Node type');
  }
  if (\Drupal::moduleHandler()->moduleExists('user')) {
    $api->entityRoute('user', 'user/%', NULL, 'User role');
  }
  if (\Drupal::moduleHandler()->moduleExists('taxonomy')) {
    $api->entityRoute('taxonomy_term', 'taxonomy/term/%', 'vocabulary_machine_name', 'Vocabulary');
  }

  $api->monoPlugin('home_title');
  $suffix = \Drupal::moduleHandler()->moduleExists('token') ? 'TokenEnabled' : 'TokenDisabled';
  $api->entityParentPlugin('entityParent', $suffix);
}


class crumbs_CrumbsMonoPlugin_home_title implements crumbs_MonoPlugin_FindTitleInterface {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $home_title = \Drupal::config('crumbs.settings')->get('crumbs_home_link_title');
    return t('Set t("@title") as the title for the root item.', array(
      '@title' => $home_title,
    ));
  }

  /**
   * {@inheritdoc}
   */
  function findTitle($path, $item) {
    if ('<front>' === $item['href']) {
      $home_title = \Drupal::config('crumbs.settings')->get('crumbs_home_link_title');
      return t($home_title);
    }

    return NULL;
  }
}
