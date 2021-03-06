<?php
namespace Drupal\crumbs\lib;

use Drupal\crumbs\lib\PluginSystem\crumbs_PluginSystem_PluginEngine;

class crumbs_BreadcrumbBuilder {

  /**
   * @var crumbs_PluginSystem_PluginEngine
   */
  protected $pluginEngine;

  /**
   * @param crumbs_PluginSystem_PluginEngine $pluginEngine
   */
  function __construct(crumbs_PluginSystem_PluginEngine $pluginEngine) {
    $this->pluginEngine = $pluginEngine;
  }

  /**
   * @param array[] $trail
   *   Trail items, keyed by system path.
   *
   * @return array[]
   *   Breadcrumb items, keyed numerically.
   */
  function buildBreadcrumb($trail) {
    $breadcrumb = array();
    foreach ($trail as $path => $item) {
      if ($item) {
        $title = $this->pluginEngine->findTitle($path, $item, $breadcrumb);
//        print '<pre>'; print_r("buildBreadcrumb - findTitle"); print '</pre>';
//        print '<pre>'; print_r($title); print '</pre>';
        if (!isset($title)) {
          $title = $item['title'];
        }
        // The item will be skipped, if $title === FALSE.
        if (isset($title) && $title !== FALSE && $title !== '') {
          $item['title'] = $title;
          $breadcrumb[] = $item;
        }
      }
    }
    return $breadcrumb;
  }
}
