<?php
namespace Drupal\crumbs;

/**
 * Implementation of class hook class_CrumbsParentFinder
 * on the behalf of path module.
 */
class path_CrumbsMonoPlugin implements crumbs_MonoPlugin_FindParentInterface {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    return t('Chop off the last fragment of the path alias, consider the result as the parent path.');
  }

  /**
   * {@inheritdoc}
   */
  function findParent($path, $item) {
    if ($alias = $item['alias']) {
      return crumbs_reduce_path($alias, 1);
    }

    return NULL;
  }
}
