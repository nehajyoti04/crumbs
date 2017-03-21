<?php
namespace Drupal\crumbs;

class blog_CrumbsMonoPlugin_authorPage implements crumbs_MonoPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $api->titleWithLabel(t("The author's blog page"), t('Parent'));
  }

  /**
   * Still under constructon..
   *
   * @param string $path
   * @param array $item
   *
   * @return null|string
   */
  function findParent__node_x($path, $item) {
    $node = crumbs_Util::itemExtractEntity($item, 'node', 1);

    if ($node === FALSE || $node->type !== 'blog') {
      return NULL;
    }

    return 'blog/' . $node->uid;
  }
}
