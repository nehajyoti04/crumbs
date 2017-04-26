<?php

namespace Drupal\crumbs\lib\injectedAPI\Collection;

/**
 * Represents the result of hook_crumbs_plugins()
 */
class CollectionResult {

  /**
   * @var PluginCollection
   */
  private $pluginCollection;

  /**
   * @var DefaultValueCollection
   */
  private $defaultValueCollection;

  /**
   * @param PluginCollection $pluginCollection
   * @param DefaultValueCollection $defaultValueCollection
   */
  function __construct(
    PluginCollection $pluginCollection,
    DefaultValueCollection $defaultValueCollection
  ) {
    $this->pluginCollection = $pluginCollection;
    $this->defaultValueCollection = $defaultValueCollection;
  }

  /**
   * @return array
   * @throws Exception
   */
  function getPlugins() {
    return $this->pluginCollection->getPlugins();
  }

  /**
   * @return true[][]
   *   Format: $['findParent'][$plugin_key] = true
   */
  function getRoutelessPluginMethods() {
    return $this->pluginCollection->getRoutelessPluginMethods();
  }

  /**
   * @return true[][][]
   *   Format: $['findParent'][$route][$plugin_key] = true.
   */
  function getRoutePluginMethods() {
    return $this->pluginCollection->getRoutePluginMethods();
  }

  /**
   * @return true[][]
   *   Format: $[$pluginKey]['findParent'] = true
   */
  function getPluginRoutelessMethods() {
    return $this->pluginCollection->getPluginRoutelessMethods();
  }

  /**
   * @return true[][][]
   *   Format: $[$pluginKey]['findParent'][$route] = true
   */
  function getPluginRouteMethods() {
    return $this->pluginCollection->getPluginRouteMethods();
  }

  /**
   * @return mixed[]
   *   Format: $[$key] = false|$weight
   * @throws Exception
   */
  function getDefaultValues() {
    return $this->defaultValueCollection->getDefaultValues();
  }

} 
