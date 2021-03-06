<?php

namespace Drupal\crumbs\lib\DIC;

use crumbs_CallbackRestoration;
use Drupal\crumbs\lib\crumbs_BreadcrumbBuilder;
use Drupal\crumbs\lib\crumbs_CurrentPageInfo;
use Drupal\crumbs\lib\crumbs_ParentFinder;
use Drupal\crumbs\lib\crumbs_Router;
use Drupal\crumbs\lib\crumbs_TrailCache;
use Drupal\crumbs\lib\crumbs_TrailFinder;
use Drupal\crumbs\lib\PluginSystem\crumbs_PluginSystem_PluginBag;
use Drupal\crumbs\lib\PluginSystem\crumbs_PluginSystem_PluginEngine;
use Drupal\crumbs\lib\PluginSystem\crumbs_PluginSystem_PluginInfo;

/**
 * Little brother of a dependency injection container (DIC)
 *
 * @property crumbs_BreadcrumbBuilder $breadcrumbBuilder
 * @property crumbs_TrailFinder $trailFinder
 * @property crumbs_ParentFinder $parentFinder
 * @property crumbs_PluginSystem_PluginBag $pluginBag
 * @property crumbs_PluginSystem_PluginEngine $pluginEngine
 * @property crumbs_CallbackRestoration $callbackRestoration
 * @property crumbs_PluginSystem_PluginInfo $pluginInfo
 * @property crumbs_CurrentPageInfo $page
 * @property crumbs_TrailCache $trails
 * @property crumbs_Router $router
 */
class crumbs_DIC_ServiceContainer extends crumbs_DIC_AbstractServiceContainer {

//  protected $crumbs_PluginSystem_PluginInfo;
//
//  function __construct(\Drupal\crumbs\lib\PluginSystem\crumbs_PluginSystem_PluginInfo $crumbs_PluginSystem_PluginInfo) {
//    $this->crumbs_PluginSystem_PluginInfo = $crumbs_PluginSystem_PluginInfo;
//  }

  /**
   * A service that can build a breadcrumb from a trail.
   *
   * @return crumbs_BreadcrumbBuilder
   *
   * @see crumbs_DIC_ServiceContainer::$breadcrumbBuilder
   */
  protected function breadcrumbBuilder() {
    return new crumbs_BreadcrumbBuilder($this->pluginEngine);
  }

  /**
   * A service that can build a trail for a given path.
   *
   * @return crumbs_TrailFinder
   *
   * @see crumbs_DIC_ServiceContainer::$trailFinder
   */
  protected function trailFinder() {
    return new crumbs_TrailFinder($this->parentFinder, $this->router);
  }

  /**
   * A service that attempts to find a parent path for a given path.
   *
   * @return crumbs_ParentFinder
   *
   * @see crumbs_DIC_ServiceContainer::$parentFinder
   */
  protected function parentFinder() {
    return new crumbs_ParentFinder($this->pluginEngine, $this->router);
  }

  /**
   * @return crumbs_PluginSystem_PluginBag
   *
   * @see crumbs_DIC_ServiceContainer::$pluginBag
   */
  protected function pluginBag() {
    $pluginInfo = $this->pluginInfo;
//    print '<pre>'; print_r("plugin bag - plugin info"); print '</pre>';
//    print '<pre>'; print_r($pluginInfo); print '</pre>';
    return new crumbs_PluginSystem_PluginBag(
      $pluginInfo->plugins,
      $pluginInfo->routelessPluginMethods,
      $pluginInfo->routePluginMethods);
  }

  /**
   * A service that knows all plugins and their configuration/weights,
   * and can run plugin operations on those plugins.
   *
   * @return crumbs_PluginSystem_PluginEngine
   *
   * @see crumbs_DIC_ServiceContainer::$pluginEngine
   */
  protected function pluginEngine() {
    return new crumbs_PluginSystem_PluginEngine(
      $this->pluginBag,
      $this->router,
      $this->pluginInfo->weightMap);
  }

  /**
   * @return crumbs_CallbackRestoration
   *
   * @see crumbs_DIC_ServiceContainer::$callbackRestoration
   */
  protected function callbackRestoration() {
    return new crumbs_CallbackRestoration();
  }

  /**
   * A service that knows all plugins and their configuration/weights.
   *
   * @return crumbs_PluginSystem_PluginInfo
   *
   * @see crumbs_DIC_ServiceContainer::$pluginInfo
   */
  public function pluginInfo() {
//    return new crumbs_PluginSystem_PluginInfo();
//    return $this->crumbs_PluginSystem_PluginInfo;
    return \Drupal::service('crumbs.plugin_system.plugin_info');
  }

  /**
   * Service that can provide information related to the current page.
   *
   * @return crumbs_CurrentPageInfo
   *
   * @see crumbs_DIC_ServiceContainer::$page
   */
  protected function page() {
    return new crumbs_CurrentPageInfo(
      $this->trails,
      $this->breadcrumbBuilder,
      $this->router);
  }

  /**
   * Service that can provide/calculate trails for different paths.
   *
   * @return crumbs_TrailCache
   *
   * @see crumbs_DIC_ServiceContainer::$trails
   */
  protected function trails() {
    return new crumbs_TrailCache($this->trailFinder);
  }

  /**
   * Wrapper for routing-related Drupal core functions.
   *
   * @return crumbs_Router
   *
   * @see crumbs_DIC_ServiceContainer::$router
   */
  protected function router() {
    return new crumbs_Router();
  }

}
