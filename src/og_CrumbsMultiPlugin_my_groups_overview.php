<?php
namespace Drupal\crumbs;

/**
 * Make $my_groups_path the parent path for group nodes where the current user
 * is a member.
 * The priorities can be configured per group node type.
 */
class og_CrumbsMultiPlugin_my_groups_overview extends og_CrumbsMultiPlugin_groups_overview {

  /**
   * Overridable helper method to actually find the parent path,
   * once we know it is a group node.
   *
   * @param stdClass $group_node
   *   The node at this path, of which we know it is a group node.
   * @return array
   */
  protected function getGroupsOverviewPath($group_node) {

    // Check if the current user is a group member.
    if (og_is_member('node', $group_node->nid)) {

      // Use the parent implementation.
      return parent::getGroupsOverviewPath($group_node);
    }

    return NULL;
  }
}
