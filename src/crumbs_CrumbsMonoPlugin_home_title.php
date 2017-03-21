<?php
namespace Drupal\crumbs;

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
