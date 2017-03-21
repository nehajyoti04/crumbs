<?php
namespace Drupal\crumbs;

/**
 * Make $groups_overview_path the parent path for group nodes.
 * The priorities can be configured per group node type.
 *
 * This class is never instantiated in native Crumbs,
 * but it can be used in custom modules.
 */
class og_CrumbsMultiPlugin_groups_overview implements crumbs_MultiPlugin {

  /**
   * @var string[]|string
   *   See the constructor argument.
   */
  protected $groupsOverviewPaths;

  /**
   * @param array|string $groups_overview_paths
   *   Either
   *     just one parent path,
   *   or
   *     an array of parent paths per node type. E.g.
   *     array(
   *       'city_group' => 'city-groups',
   *       'sports_group' => 'groups/sports',
   *     )
   *     The user is responsible to make sure that these are all group types.
   */
  function __construct($groups_overview_paths) {
    $this->groupsOverviewPath = $groups_overview_paths;
  }

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    if (is_array($this->groupsOverviewPaths)) {
      foreach ($this->groupsOverviewPaths as $type => $parent_path) {
        $api->addRule($type);
      }
    }
    else {
      $types = node_type_get_types();
      foreach ($types as $type) {
        if (og_is_group_type('node', $type->type)) {
          $api->addRule($type->type);
        }
      }
    }
  }

  /**
   * Find a parent path for node/%, if that node is a group.
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

    if (og_is_group('node', $node)) {
      return $this->getGroupsOverviewPath($node);
    }

    return NULL;
  }

  /**
   * Overridable helper method to actually find the parent path,
   * once we know it is a group node.
   *
   * @param stdClass $group_node
   *   The node at this path, of which we know it is a group node.
   * @return array
   */
  protected function getGroupsOverviewPath($group_node) {
    if (is_array($this->groupsOverviewPaths)) {
      if (isset($this->groupsOverviewPaths[$group_node->type])) {
        return array($group_node->type => $this->groupsOverviewPaths[$group_node->type]);
      }
      // If the node type is not in the array, we return nothing!
    }
    else {
      return array($group_node->type => $this->groupsOverviewPaths);
    }

    return NULL;
  }

}
