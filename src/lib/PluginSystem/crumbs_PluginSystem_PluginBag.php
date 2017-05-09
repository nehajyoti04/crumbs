<?php

namespace Drupal\crumbs\lib\PluginSystem;


use Drupal\crumbs\lib\crumbs_MonoPlugin;
use Drupal\crumbs\lib\crumbs_PluginInterface;

class crumbs_PluginSystem_PluginBag {

  /**
   * @var crumbs_PluginInterface[]
   */
  protected $plugins;

  /**
   * @var true[][]
   *   Format: $['findParent'][$plugin_key] = true
   */
  protected $routelessPluginMethods = array();

  /**
   * @var true[][][]
   *   Format: $['findParent'][$route][$plugin_key] = true
   */
  protected $routePluginMethods = array();

  /**
   * @param crumbs_PluginInterface[] $plugins
   * @param true[][] $routelessPluginMethods
   * @param true[][][] $routePluginMethods
   */
  function __construct(crumbs_PluginSystem_PluginInfo $crumbs_PluginSystem_PluginInfo) {
    $this->plugins = $crumbs_PluginSystem_PluginInfo->get_plugins();
    $this->routelessPluginMethods = $crumbs_PluginSystem_PluginInfo->get_routelessPluginMethods();
    $this->routePluginMethods = $crumbs_PluginSystem_PluginInfo->get_routePluginMethods();
  }

  /**
   * @return crumbs_MonoPlugin
   */
  function getDecorateBreadcrumbPlugins() {
    $plugin_methods = isset($this->routelessPluginMethods['decorateBreadcrumb'])
      ? $this->routelessPluginMethods['decorateBreadcrumb']
      : array();
    $plugins = array();
    foreach ($plugin_methods as $pluginKey => $method) {
      if (!isset($this->plugins[$pluginKey])) {
        continue;
      }
      $plugins[$pluginKey] = $this->plugins[$pluginKey];
    }
    return $plugins;
  }

  /**
   * @param $base_method_name
   * @param $route
   *
   * @return crumbs_PluginSystem_PluginMethodIterator
   */
  public function getRoutePluginMethodIterator($base_method_name, $route) {
    $methods = $this->getRoutePluginMethods($base_method_name, $route);
    return new crumbs_PluginSystem_PluginMethodIterator($methods, $this->plugins, $base_method_name);
  }

  /**
   * @param string $base_method_name
   *   Either 'findParent' or 'findTitle' or 'decorateBreadcrumb'.
   * @param string $route
   *   A route, e.g. 'node/%'.
   *
   * @return true[]
   *   Format: $[$plugin_key] = true.
   */
  private function getRoutePluginMethods($base_method_name, $route) {
    if (isset($this->routePluginMethods[$base_method_name][$route])) {
      return $this->routePluginMethods[$base_method_name][$route];
    }
    if (isset($this->routelessPluginMethods[$base_method_name])) {
      return $this->routelessPluginMethods[$base_method_name];
    }
    return array();
  }

} 
