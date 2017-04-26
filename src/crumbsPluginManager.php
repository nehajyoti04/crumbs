<?php

namespace Drupal\crumbs;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Provides a plugin manager for Crumbs.
 *
 * @see \Drupal\crumbs\Annotation\Tip
 * @see \Drupal\crumbs\TipPluginBase
 * @see \Drupal\crumbs\TipPluginInterface
 * @see plugin_api
 */
class crumbsPluginManager extends DefaultPluginManager {

  /**
   * Constructs a new CrumbsPluginManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations,
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/Crumbs', $namespaces, $module_handler, 'Drupal\crumbs\crumbsPluginInterface', 'Drupal\crumbs\Annotation\crumbsAnnotation');

    $this->alterInfo('crumbs_crumbs_info');
    $this->setCacheBackend($cache_backend, 'crumbs_plugins');
  }

}
