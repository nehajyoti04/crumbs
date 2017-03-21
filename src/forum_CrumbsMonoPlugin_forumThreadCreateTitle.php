<?php
namespace Drupal\crumbs;

class forum_CrumbsMonoPlugin_forumThreadCreateTitle implements crumbs_MonoPlugin_FindTitleInterface {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $api->titleWithLabel(t('node/add/*/* in a forum'), t('Path'));
    $api->titleWithLabel('"' . t('New topic') . '"', t('Title'));
  }

  /**
   * Set a breadcrumb item title for e.g. node/add/(type)/(tid), where
   * - (type) a forum-enabled node type, e.g. "forum".
   * - (tid) is the forum term tid.
   *
   * {@inheritdoc}
   */
  function findTitle($path, $item) {
    if (
      // Start with a cheap-to-compute condition,
      // so the regex can be skipped in the average case.
      substr($path, 0, 9) === 'node/add/' &&
      preg_match('#^node/add/([^/]+)/(\d+)$#', $path, $m)
    ) {
      $type = $m[1];
      $tid = (int)$m[2];
      // We need to find out if the node type is forum-enabled.
      // See _forum_node_check_node_type() in forum.module.
      $field = field_info_instance('node', 'taxonomy_forums', $type);
      if (is_array($field)) {
        // That's a node/add/(type)/(tid) page for a forum-enabled node type.
        $term = \Drupal::entityManager()->getStorage("taxonomy_term")->load($item['original_map'][3]);
        if ($term instanceof stdClass && 'forums' === $term->vocabulary_machine_name) {
          return $this->newTopicTitle($type);
        }
      }
    }

    return NULL;
  }

  /**
   * Method that can be overwritten by subclasses.
   *
   * @param $type
   *
   * @return null|string
   */
  protected function newTopicTitle($type) {
    return t('New topic');
  }
}
