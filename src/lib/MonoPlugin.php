<?php

namespace Drupal\crumbs\lib;

/**
 * Interface for plugin objects registered with hook_crumbs_plugins().
 */
interface MonoPlugin extends PluginInterface {

  /**
   * @param describeMonoPlugin $api
   *   Injected API object, with methods that allows the plugin to further
   *   describe itself.
   *
   * @return string|void
   *   As an alternative to the API object's methods, the plugin can simply
   *   return a string label.
   */
  function describe($api);
}
