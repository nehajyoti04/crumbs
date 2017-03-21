<?php
namespace Drupal\crumbs;

class forum_CrumbsMonoPlugin_forumTerm implements crumbs_MonoPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $api->titleWithLabel(t('The parent forum'), t('Parent'));
  }

  /**
   * Forums get their parent forums as breadcrumb parent.
   * The method name matches the router path "forum/%".
   * Forums are actually taxonomy terms, just the path is different.
   *
   * @param string $path
   * @param array $item
   *
   * @return string|null
   */
  function findParent__forum_x($path, $item) {
    // Load the forum term, even if the wildcard loader has been replaced or
    // removed. This will use entity_load() and not forum_forum_load(), because
    // we don't need the forum stuff here.
    $term = crumbs_Util::itemExtractEntity($item, 'taxonomy_term', 1);
    if (FALSE === $term) {
      return NULL;
    }

    $parents = taxonomy_get_parents($term->tid);
    foreach ($parents as $parent_tid => $parent_term) {
      if ($parent_term->vocabulary_machine_name == $term->vocabulary_machine_name) {
        // The parent forum
        return 'forum/' . $parent_tid;
      }
    }
    // Forum overview
    return 'forum';
  }
}
