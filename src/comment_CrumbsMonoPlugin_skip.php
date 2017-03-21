<?php
namespace Drupal\crumbs;

class comment_CrumbsMonoPlugin_skip implements crumbs_MonoPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    return t('Skip comment/% in the breadcrumb.');
  }

  /**
   * The default title for comment/% is "Comment permalink",
   * so not very useful to have in the breadcrumb.
   *
   * @param string $path
   * @param array $item
   *
   * @return false
   *   A value of FALSE indicates that the breadcrumb item should be skipped.
   */
  function findTitle__comment_x($path, $item) {
    return FALSE;
  }
}
