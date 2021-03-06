<?php

namespace Drupal\crumbs\lib\Multiplugin;
//use Drupal\crumbs\lib;

interface crumbs_MultiPlugin_FindParentInterface extends \Drupal\crumbs\lib\crumbs_MultiPlugin {

//interface crumbs_MultiPlugin_FindParentInterface implements crumbs_MultiPlugin {
  /**
   * Find candidates for the parent path.
   *
   * @param string $path
   *   The path that we want to find a parent for.
   * @param array $item
   *   Item as returned from crumbs_get_router_item()
   *
   * @return array
   *   Parent path candidates
   */
  function findParent($path, $item);
}