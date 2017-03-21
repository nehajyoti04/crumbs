<?php
namespace Drupal\crumbs;

class forum_CrumbsMonoPlugin_forumTermName implements crumbs_MonoPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $api->titleWithLabel(t('The forum\'s term name'), t('Title'));
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
  function findTitle__forum_x($path, $item) {
    // Load the forum term, even if the wildcard loader has been replaced or
    // removed. This will use entity_load() and not forum_forum_load(), because
    // we don't need the forum stuff here.
    $term = crumbs_Util::itemExtractEntity($item, 'taxonomy_term', 1);
    if (FALSE === $term || empty($term->name)) {
      return NULL;
    }
    return $term->name;
  }
}
