<?php
namespace Drupal\crumbs;

/**
 * Use the group node as a parent for group posts.
 * The priorities can be configured per group content type.
 */
class og_CrumbsMultiPlugin_group_post implements crumbs_MultiPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $types = node_type_get_types();
    foreach ($types as $type) {
      if (og_is_group_content_type('node', $type->type)) {
        $api->ruleWithLabel($type->type, $type->name, t('Group content type'));
      }
    }
    $api->descWithLabel(t('Group node'), t('Parent'));
  }

  /**
   * Attempts to find a breadcrumb parent path for node/%.
   * If that node is in a group, it will return the group page as a parent.
   *
   * @param string $path
   *   The path that we want to find a parent for, e.g. "node/123".
   * @param array $item
   *   Loaded router item, as returned from crumbs_get_router_item()
   *
   * @return array|null
   *   Parent path candidates
   */
  function findParent__node_x($path, $item) {
    if (FALSE === $node = crumbs_Util::itemExtractEntity($item, 'node', 1)) {
      return NULL;
    }

    // field_get_items() performs a lot faster than og_get_entity_groups().
    // See http://drupal.org/node/1819300#comment-6633494
    // TODO:
    //   We cannot rely on the field name to always be og_group_ref.
    //   Instead, we could provide a separate plugin for each such field.
    //   This way, fields in disabled plugins get never triggered.
    $items = field_get_items('node', $node, 'og_group_ref');
    if (is_array($items)) {
      foreach ($items as $item) {
        $parent_path = $this->getParentPath($item['target_id'], $node);
        return array($node->type => $parent_path);
      }
    }

    return NULL;
  }

  /**
   * This method can be overridden by custom plugins that inherit from this one,
   * e.g. to set a different parent for group events than for group discussions.
   *
   * @param int $group_nid
   *   Node id of the group that was found to be the parent.
   * @param stdClass $group_post
   *   The node that is in the group, and that we are trying to find a parent
   *   path for.
   *
   * @return string
   *   A parent path. The native implementation returns just node/$nid.
   *   Custom module might subclass this class and override this method, to let
   *   it return e.g. node/$nid/events, or node/$nid/forum, depending on the
   *   $group_post argument.
   */
  protected function getParentPath($group_nid, $group_post) {
    return 'node/' . $group_nid;
    /*
     * Example:
     * switch ($group_post->type) {
     *   case 'event':
     *     return 'node/' . $group_nid . '/events';
     *   case 'discussion':
     *     return 'node/' . $group_nid . '/forum';
     *   default:
     *     return 'node/' . $group_nid;
     * }
     */
  }
}
