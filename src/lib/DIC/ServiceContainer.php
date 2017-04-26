<?php

namespace Drupal\crumbs\lib\DIC;
use Drupal\crumbs\crumbs_DIC_AbstractServiceContainer;

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
class ServiceContainer extends crumbs_DIC_AbstractServiceContainer {

  /**
   * A service that can build a breadcrumb from a trail.
   *
   * @return crumbs_BreadcrumbBuilder
   *
   * @see crumbs_DIC_ServiceContainer::$breadcrumbBuilder
   */
  public function breadcrumbBuilder() {
    return new crumbs_BreadcrumbBuilder($this->pluginEngine);
  }

  /**
   * A service that can build a trail for a given path.
   *
   * @return crumbs_TrailFinder
   *
   * @see crumbs_DIC_ServiceContainer::$trailFinder
   */
  public function trailFinder() {
    return new crumbs_TrailFinder($this->parentFinder, $this->router);
  }

  /**
   * A service that attempts to find a parent path for a given path.
   *
   * @return crumbs_ParentFinder
   *
   * @see crumbs_DIC_ServiceContainer::$parentFinder
   */
  public function parentFinder() {
    return new crumbs_ParentFinder($this->pluginEngine, $this->router);
  }

  /**
   * @return crumbs_PluginSystem_PluginBag
   *
   * @see crumbs_DIC_ServiceContainer::$pluginBag
   */
  public function pluginBag() {
    $pluginInfo = $this->pluginInfo;
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
  public function pluginEngine() {
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
  public function callbackRestoration() {
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
    return new crumbs_PluginSystem_PluginInfo();
  }

  /**
   * Service that can provide information related to the current page.
   *
   * @return crumbs_CurrentPageInfo
   *
   * @see crumbs_DIC_ServiceContainer::$page
   */
  public function page() {
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
  public function trails() {
    return new crumbs_TrailCache($this->trailFinder);
  }

  /**
   * Wrapper for routing-related Drupal core functions.
   *
   * @return crumbs_Router
   *
   * @see crumbs_DIC_ServiceContainer::$router
   */
  public function router() {
    return new crumbs_Router();
  }

}
