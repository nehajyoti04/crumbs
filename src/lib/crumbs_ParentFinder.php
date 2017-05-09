<?php

namespace Drupal\crumbs\lib;
use Drupal\crumbs\lib\PluginSystem\crumbs_PluginSystem_PluginEngine;

/**
 * Can find a parent path for a given path.
 * Contains a cache.
 */
class crumbs_ParentFinder {

  /**
   * @var crumbs_PluginSystem_PluginEngine
   */
  protected $pluginEngine;

  /**
   * @var crumbs_Router;
   */
  protected $router;

  /**
   * @var array
   *   Cached parent paths
   */
  protected $parents = array();

  /**
   * @param crumbs_PluginSystem_PluginEngine $pluginEngine
   * @param crumbs_Router $router
   */
  function __construct(crumbs_PluginSystem_PluginEngine $pluginEngine, crumbs_Router $router) {
    $this->pluginEngine = $pluginEngine;
    $this->router = $router;
  }

  /**
   * @param string $path
   * @param array|null &$item
   *
   * @return string
   */
  function getParentPath($path, &$item) {
    if (!isset($this->parents[$path])) {
      $parent_path = $this->_findParentPath($path, $item);
//      print '<pre>'; print_r("find parent path"); print '</pre>';
//      print '<pre>'; print_r($parent_path); print '</pre>';
      if (is_string($parent_path)) {
        $parent_path = $this->router->getNormalPath($parent_path);
      }
      $this->parents[$path] = $parent_path;
    }
    return $this->parents[$path];
  }

  /**
   * @param string $path
   * @param array|null &$item
   *
   * @return string|bool
   */
  protected function _findParentPath($path, &$item) {
    if ($item) {
      // @TODO for now commenting this.
//      if (!$item['access']) {
//        // Parent should be the front page.
//        return FALSE;
//      }
      $parent_path = $this->pluginEngine->findParent($path, $item);
//      print '<pre>'; print_r("this - pluginENgine - find parent path"); print '</pre>';
//      print '<pre>'; print_r($parent_path); print '</pre>';
      // @TODO for now hardcoding it.
      $parent_path = NULL;
      if (isset($parent_path)) {
        return $parent_path;
      }
    }

    // fallback: chop off the last fragment of the system path.
    $parent_path = $this->router->reducePath($path);
//    print '<pre>'; print_r("this - router - reduce path"); print '</pre>';
//    print '<pre>'; print_r($parent_path); print '</pre>';
    return isset($parent_path) ? $parent_path : FALSE;
  }

}
