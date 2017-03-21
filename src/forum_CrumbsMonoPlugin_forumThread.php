<?php
namespace Drupal\crumbs;

class forum_CrumbsMonoPlugin_forumThread implements crumbs_MonoPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $api->titleWithLabel(t('The forum the node belongs to'), t('Parent'));
  }

  /**
   * Forum nodes get their forum terms as breadcrumb parents.
   * The method name matches the router path "node/%".
   *
   * @param string $path
   * @param array $item
   *
   * @return string|null
   */
  function findParent__node_x($path, $item) {
    $node = crumbs_Util::itemExtractEntity($item, 'node', 1);
    if ( FALSE === $node
      || empty($node->forum_tid)
      || !_forum_node_check_node_type($node)
    ) {
      return NULL;
    }

    return 'forum/' . $node->forum_tid;
  }
}
