<?php
namespace Drupal\crumbs;

class comment_CrumbsMonoPlugin_comment implements crumbs_MonoPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $api->titleWithLabel(t("The comment's node"), t('Parent'));
  }

  /**
   * Make node/% the parent for comment/%.
   * This also completes the breadcrumb for other comment/%/* paths.
   *
   * @param string $path
   * @param array $item
   *
   * @return string
   */
  function findParent__comment_x($path, $item) {
    $comment = \Drupal::entityManager()->getStorage('comment')->load($item['original_map'][1]);
    if (!empty($comment->nid)) {
      return 'node/' . $comment->nid;
    }

    return NULL;
  }

}
