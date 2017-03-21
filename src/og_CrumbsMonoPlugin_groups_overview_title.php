<?php
namespace Drupal\crumbs;

/**
 * Make t('Groups') the title for '/group-list'.
 */
class og_CrumbsMonoPlugin_groups_overview_title implements crumbs_MonoPlugin_FindTitleInterface {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    return t('Set "Group" as the title for item for "group-list".');
  }

  /**
   * {@inheritdoc}
   */
  function findTitle($path, $item) {
    if ($item['route'] === 'group-list') {
      return t('Groups');
    }

    return NULL;
  }

}
