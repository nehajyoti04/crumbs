<?php

namespace Drupal\crumbs;

use crumbs_EntityPlugin_Callback;
use crumbs_MonoPlugin_ParentPathCallback;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Plugin\PluginBase;
use Drupal\crumbs\lib\crumbs_EntityPlugin;
use Drupal\crumbs\lib\crumbs_MonoPlugin;
use Drupal\crumbs\lib\crumbs_MultiPlugin;
use Drupal\crumbs\lib\crumbs_PluginInterface;
use Drupal\crumbs\lib\InjectedAPI\Collection\crumbs_InjectedAPI_Collection_CallbackCollection;
use Drupal\crumbs\lib\InjectedAPI\Collection\crumbs_InjectedAPI_Collection_DefaultValueCollection;
use Drupal\crumbs\lib\InjectedAPI\Collection\crumbs_InjectedAPI_Collection_EntityPluginCollection;
use Drupal\crumbs\lib\InjectedAPI\Collection\crumbs_InjectedAPI_Collection_PluginCollection;
use Drupal\crumbs\lib\Monoplugin\crumbs_MonoPlugin_FixedParentPath;
use Drupal\crumbs\lib\Monoplugin\crumbs_MonoPlugin_SkipItem;
use Drupal\crumbs\lib\Monoplugin\crumbs_MonoPlugin_TitleCallback;
use Drupal\crumbs\lib\Monoplugin\crumbs_MonoPlugin_TranslateTitle;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\crumbs\menu_CrumbsMultiPlugin_hierarchy;

/**
 * Defines a base tour item implementation.
 *
 * @see \Drupal\crumbs\Annotation\CrumbsAnnotation
 * @see \Drupal\crumbs\crumbsPluginInterface
 * @see \Drupal\crumbs\crumbssPluginManager
 * @see plugin_api
 */
abstract class crumbsPluginBase extends PluginBase implements crumbsPluginInterface {

  /**
   * The label which is used for render of this tip.
   *
   * @var string
   */
  protected $label;

  /**
   * Allows tips to take more priority that others.
   *
   * @var string
   */
  protected $weight;

  /**
   * The attributes that will be applied to the markup of this tip.
   *
   * @var array
   */
  protected $attributes;

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->get('id');
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->get('label');
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->get('weight');
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributes() {
    return $this->get('attributes') ?: [];
  }

  /**
   * {@inheritdoc}
   */
  public function get($key) {
    if (!empty($this->configuration[$key])) {
      return $this->configuration[$key];
    }
  }
  /**
   * {@inheritdoc}
   */
//  public function get($key) {
//    if (!empty($this->pluginDefinition["key"])) {
//      return $this->pluginDefinition["key"];
//    }
//  }

  /**
   * {@inheritdoc}
   */
  public function set($key, $value) {
    $this->configuration[$key] = $value;
  }

  public function getName() {
    return $this->pluginDefinition['name'];
  }
  public function getPrice() {
    return $this->pluginDefinition['price'];
  }
  public function slogan() {
    return t('Best flavor ever.');
  }

  public function multipluginKey() {
    return $this->pluginDefinition['multipluginKey'];
  }

//  public function getApi(){
//    return $this->pluginDefinition['api'];
//  }

  public function getDisabledByDefaultKey(){
    return $this->pluginDefinition['disabled_by_default_key'];
  }


//  function customsetDefaultValue($key, $value) {
//    $this->defaultValues[$key] = $value;
//    print '<pre>'; print_r("default values - key - value"); print '</pre>';
//    print '<pre>'; print_r($key . " ". $value ); print '</pre>';
//  }


  /**
   * {@inheritdoc}
   */
  public function custom_monoplugin_describe() {
    $home_title = \Drupal::state()->get('crumbs_home_link_title', 'Home');
//    print '<pre>'; print_r("mono plugin describe"); print '</pre>';
//    print '<pre>'; print_r($home_title); print '</pre>';
    return t('Set t("@title") as the title for the root item.', array(
      '@title' => $home_title,
    ));
  }

  /**
   * {@inheritdoc}
   */
  function custom_monoplugin_findTitle($path, $item) {
    if ('<front>' === $item['href']) {
      $home_title = \Drupal::state()->get('crumbs_home_link_title', 'Home');
      return t($home_title);
    }

    return NULL;
  }

  function custom_monoplugin_key(){
    return $this->pluginDefinition['monoplugin_key'];
  }










  /**
   * @var string $module
   *   The module for the current hook implementation.
   */
  private $module;

  /**
   * @var crumbs_InjectedAPI_Collection_PluginCollection
   */
  protected $pluginCollection;

  /**
   * @var crumbs_InjectedAPI_Collection_EntityPluginCollection
   */
  private $entityPluginCollection;

  /**
   * @var crumbs_InjectedAPI_Collection_CallbackCollection
   */
  private $callbackCollection;

  /**
   * @var crumbs_InjectedAPI_Collection_DefaultValueCollection
   */
  public $defaultValueCollection;

//  /**
//   * @param crumbs_InjectedAPI_Collection_PluginCollection $pluginCollection
//   * @param crumbs_InjectedAPI_Collection_EntityPluginCollection $entityPluginCollection
//   * @param crumbs_InjectedAPI_Collection_CallbackCollection $callbackCollection
//   * @param crumbs_InjectedAPI_Collection_DefaultValueCollection $defaultValueCollection
//   */
//  function __construct(
//    crumbs_InjectedAPI_Collection_PluginCollection $pluginCollection,
//    crumbs_InjectedAPI_Collection_EntityPluginCollection $entityPluginCollection,
//    crumbs_InjectedAPI_Collection_CallbackCollection $callbackCollection,
//    crumbs_InjectedAPI_Collection_DefaultValueCollection $defaultValueCollection
//  ) {
//    $this->pluginCollection = $pluginCollection;
//    $this->entityPluginCollection = $entityPluginCollection;
//    $this->callbackCollection = $callbackCollection;
//    $this->defaultValueCollection = $defaultValueCollection;
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public static function create(ContainerInterface $container) {
//    // Instantiates this form class.
//    return new static(
//      $container->get('crumbs.injected_api.collection.callback_collection'),
//      $container->get('crumbs.injected_api.collection.default_value_collection'),
//      $container->get('crumbs.injected_api.collection.entity_plugin_collection'),
//      $container->get('crumbs.injected_api.collection.entity_plugin_collection')
//    );
//  }


//  /**
//   * @param crumbs_InjectedAPI_Collection_DefaultValueCollection $defaultValueCollection
//   */
//  function __construct(
//    crumbs_InjectedAPI_Collection_DefaultValueCollection $defaultValueCollection
//  ) {
//    $this->defaultValueCollection = $defaultValueCollection;
//  }


//
//
//  /**
//   * {@inheritdoc}
//   */
//  public function __construct(array $configuration, $plugin_id, $plugin_definition
//                              ) {
//    parent::__construct($configuration, $plugin_id, $plugin_definition);
//
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
//    return new static(
//      $configuration,
//      $plugin_id,
//      $plugin_definition
////      $container->get('crumbs.injected_api.collection.plugin_collection'),
////      $container->get('crumbs.injected_api.collection.entity_plugin_collection'),
////      $container->get('crumbs.injected_api.collection.callback_collection'),
////      $container->get('crumbs.injected_api.collection.default_value_collection')
//    );
//  }


//  /**
//   * {@inheritdoc}
//   */
//  public function __construct(array $configuration, $plugin_id, $plugin_definition,
//                              crumbs_InjectedAPI_Collection_PluginCollection $pluginCollection,
//                              crumbs_InjectedAPI_Collection_EntityPluginCollection $entityPluginCollection,
//                              crumbs_InjectedAPI_Collection_CallbackCollection $callbackCollection,
//                              crumbs_InjectedAPI_Collection_DefaultValueCollection $defaultValueCollection) {
//    parent::__construct($configuration, $plugin_id, $plugin_definition);
//    $this->pluginCollection = $pluginCollection;
//    $this->entityPluginCollection = $entityPluginCollection;
//    $this->callbackCollection = $callbackCollection;
//    $this->defaultValueCollection = $defaultValueCollection;
//    $this->setModule($this->pluginDefinition['module']);
//    $this->multiPlugin($this->multipluginKey());
//    $this->disabledByDefault('hierarchy.*');
//  }
//
//
//  /**
//   * {@inheritdoc}
//   */
//  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
//    return new static(
//      $configuration,
//      $plugin_id,
//      $plugin_definition,
//      $container->get('crumbs.injected_api.collection.plugin_collection'),
//      $container->get('crumbs.injected_api.collection.entity_plugin_collection'),
//      $container->get('crumbs.injected_api.collection.callback_collection'),
//      $container->get('crumbs.injected_api.collection.default_value_collection')
//    );
//  }


  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition,
                              crumbs_InjectedAPI_Collection_PluginCollection $pluginCollection,
                              crumbs_InjectedAPI_Collection_EntityPluginCollection $entityPluginCollection,
                              crumbs_InjectedAPI_Collection_CallbackCollection $callbackCollection,
                              crumbs_InjectedAPI_Collection_DefaultValueCollection $defaultValueCollection) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    print '<pre>'; print_r("Crumbs Plugin Base - plugin Collection"); print '</pre>';
    $this->pluginCollection = $pluginCollection;
    $this->entityPluginCollection = $entityPluginCollection;
    $this->callbackCollection = $callbackCollection;
    $this->defaultValueCollection = $defaultValueCollection;
    $this->setModule($this->pluginDefinition['module']);
    $this->multiPlugin($this->multipluginKey());
    $this->disabledByDefault('hierarchy.*');
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('crumbs.injected_api.collection.plugin_collection'),
      $container->get('crumbs.injected_api.collection.entity_plugin_collection'),
      $container->get('crumbs.injected_api.collection.callback_collection'),
      $container->get('crumbs.injected_api.collection.default_value_collection')
    );
  }

  /**
   * This is typically called before each invocation of hook_crumbs_plugins(),
   * to let the object know about the module implementing the hook.
   * Modules can call this directly if they want to let other modules talk to
   * the API object.
   *
   * @param string $module
   *   The module name.
   */
  function setModule($module) {
//    print '<pre>'; print_r("hook crumbs plugin - set module"); print '</pre>';
//    print '<pre>'; print_r($module); print '</pre>';
    $this->module = $module;
  }

  /**
   * Register an entity route.
   * This should be called by those modules that define entity types and routes.
   *
   * @param string $entity_type
   * @param string $route
   * @param string $bundle_key
   * @param string $bundle_name
   */
  function entityRoute($entity_type, $route, $bundle_key, $bundle_name) {
    $this->entityPluginCollection->entityRoute($entity_type, $route, $bundle_key, $bundle_name);
  }

  /**
   * Register an entity parent plugin.
   *
   * @param string $key
   * @param string|crumbs_EntityPlugin $entity_plugin
   * @param array $types
   *   An array of entity types, or a single entity type, or NULL to allow all
   *   entity types.
   */
  function entityParentPlugin($key, $entity_plugin = NULL, $types = NULL) {
    $this->entityPlugin('parent', $key, $entity_plugin, $types);
  }

  /**
   * Register a callback that will determine a parent path for a breadcrumb item
   * with an entity route. The behavior will be available for all known entity
   * routes, e.g. node/% or taxonomy/term/%, with different plugin keys.
   *
   * @param string $key
   * @param callable $callback
   * @param array $types
   *   An array of entity types, or a single entity type, or NULL to allow all
   *   entity types.
   */
  function entityParentCallback($key, $callback, $types = NULL) {
    $entity_plugin = new crumbs_EntityPlugin_Callback($callback, $this->module, $key, 'entityParent');
    $this->entityPlugin('parent', $key, $entity_plugin, $types);
    $this->callbackCollection->addCallback($this->module, 'entityParent', $key, $callback);
  }

  /**
   * Register an entity title plugin.
   *
   * @param string $key
   * @param string|crumbs_EntityPlugin $entity_plugin
   * @param array $types
   *   An array of entity types, or a single entity type, or NULL to allow all
   *   entity types.
   */
  function entityTitlePlugin($key, $entity_plugin = NULL, $types = NULL) {
    $this->entityPlugin('title', $key, $entity_plugin, $types);
  }

  /**
   * Register a callback that will determine a title for a breadcrumb item with
   * an entity route. The behavior will be available for all known entity
   * routes, e.g. node/% or taxonomy/term/%, with different plugin keys.
   *
   * @param string $key
   *   The plugin key under which this callback will be listed on the weights
   *   configuration form.
   * @param callback $callback
   *   The callback, e.g. an anonymous function. The signature must be
   *   $callback(stdClass $entity, string $entity_type, string $distinction_key),
   *   like the findCandidate() method of a typical crumbs_EntityPlugin.
   * @param array $types
   *   An array of entity types, or a single entity type, or NULL to allow all
   *   entity types.
   */
  function entityTitleCallback($key, $callback, $types = NULL) {
    $entity_plugin = new crumbs_EntityPlugin_Callback($callback, $this->module, $key, 'entityTitle');
    $this->entityPlugin('title', $key, $entity_plugin, $types);
    $this->callbackCollection->addCallback($this->module, 'entityTitle', $key, $callback);
  }

  /**
   * @param string $type
   *   Either 'title' or 'parent'.
   * @param string $key
   *   The plugin key under which this callback will be listed on the weights
   *   configuration form.
   * @param string|crumbs_EntityPlugin $entity_plugin
   * @param string[]|string|NULL $types
   *   An array of entity types, or a single entity type, or NULL to allow all
   *   entity types.
   */
  protected function entityPlugin($type, $key, $entity_plugin, $types) {
    if (!isset($entity_plugin)) {
      $class = $this->module . '_CrumbsEntityPlugin';
      $entity_plugin = new $class();
    }
    elseif (is_string($entity_plugin)) {
      $class = $this->module . '_CrumbsEntityPlugin_' . $entity_plugin;
      $entity_plugin = new $class();
    }
    if ($entity_plugin instanceof crumbs_EntityPlugin) {
      $this->entityPluginCollection->entityPlugin($type, $this->module . '.' . $key, $entity_plugin, $types);
    }
  }

  /**
   * Register a "Mono" plugin.
   * That is, a plugin that defines exactly one rule.
   *
   * @param string $key
   *   Rule key, relative to module name.
   * @param Crumbs_MonoPlugin $plugin
   *   Plugin object. Needs to implement crumbs_MultiPlugin.
   *   Or NULL, to have the plugin object automatically created based on a
   *   class name guessed from the $key parameter and the module name.
   * @throws Exception
   */
  function monoPlugin($key = NULL, crumbs_MonoPlugin $plugin = NULL) {
    $this->addPluginByType($plugin, $key, NULL, FALSE);
  }

  /**
   * Register a "Mono" plugin that is restricted to a specific route.
   *
   * @param string $route
   * @param string $key
   * @param crumbs_MonoPlugin $plugin
   */
  function routeMonoPlugin($route, $key = NULL, crumbs_MonoPlugin $plugin = NULL) {
    $this->addPluginByType($plugin, $key, $route, FALSE);
  }

  /**
   * Register a "Multi" plugin.
   * That is, a plugin that defines more than one rule.
   *
   * @param string|null $key
   *   Rule key, relative to module name.
   * @param crumbs_MultiPlugin|null $plugin
   *   Plugin object. Needs to implement crumbs_MultiPlugin.
   *   Or NULL, to have the plugin object automatically created based on a
   *   class name guessed from the $key parameter and the module name.
   *
   * @throws Exception
   */
  function multiPlugin($key = NULL, crumbs_MultiPlugin $plugin = NULL) {
    $this->addPluginByType($plugin, $key, NULL, TRUE);
  }

  /**
   * @param string $route
   * @param string|null $key
   * @param crumbs_MultiPlugin|null $plugin
   */
  function routeMultiPlugin($route, $key = NULL, crumbs_MultiPlugin $plugin = NULL) {
    $this->addPluginByType($plugin, $key, $route, TRUE);
  }

  /**
   * @param crumbs_MonoPlugin|crumbs_PluginInterface|null $plugin
   * @param string|null $key
   * @param string|null $route
   * @param bool $is_multi
   *   TRUE for a multi plugin.
   *
   * @throws Exception
   */
  private function addPluginByType(crumbs_PluginInterface $plugin = NULL, $key = NULL, $route = NULL, $is_multi) {
    print '<pre>'; print_r("this module"); print '</pre>';
    print '<pre>'; print_r($this->module); print '</pre>';

    $plugin_key = isset($key)
      ? $this->module . '.' . $key
      : $this->module;
    if (!isset($plugin)) {
      $class = $is_multi
        ? $this->module . '_CrumbsMultiPlugin'
        : $this->module . '_CrumbsMonoPlugin';
      $class .= isset($key) ? '_' . $key : '';
      $class = 'menu_CrumbsMultiPlugin_hierarchy';
//      if (!class_exists($class)) {
//        throw new \Exception("Plugin class " .$class. " does not exist.");
//      }
//      $plugin = new $class();

    }
    else {
//      $class = get_class($plugin);
    }
//    if ($is_multi) {
//      if (!$plugin instanceof crumbs_MultiPlugin) {
//        throw new Exception("$class must implement class_MultiPlugin.");
//      }
//    }
//    else {
//      if (!$plugin instanceof crumbs_MonoPlugin) {
//        throw new Exception("$class must implement class_MonoPlugin.");
//      }
//    }
//    $this->addPlugin($plugin, $plugin_key, $route);
    $this->addPlugin(new menu_CrumbsMultiPlugin_hierarchy, $plugin_key, $route);
  }

  /**
   * @param crumbs_PluginInterface $plugin
   * @param string $plugin_key
   * @param string|null $route
   *
   * @throws Exception
   */
  private function addPlugin(crumbs_PluginInterface $plugin, $plugin_key, $route = NULL) {
    $this->pluginCollection->addPlugin($plugin, $plugin_key, $route);
  }

  /**
   * @param string $route
   * @param string $key
   * @param string $parent_path
   */
  function routeParentPath($route, $key, $parent_path) {
    $this->routeMonoPlugin($route, $key, new crumbs_MonoPlugin_FixedParentPath($parent_path));
  }

  /**
   * Register a callback that will determine a parent for a breadcrumb item.
   *
   * @param string $route
   *   The route where this callback should be used, e.g. "node/%".
   * @param string $key
   *   The plugin key under which this callback will be listed on the weights
   *   configuration form.
   * @param callback $callback
   *   The callback, e.g. an anonymous function. The signature must be
   *   $callback(string $path, array $item), like the findParent() method of
   *   a typical crumbs_MonoPlugin.
   */
  function routeParentCallback($route, $key, $callback) {
    $this->routeMonoPlugin($route, $key, new crumbs_MonoPlugin_ParentPathCallback($callback, $this->module, $key));
    $this->callbackCollection->addCallback($this->module, 'routeParent', $key, $callback);
  }

  /**
   * @param string $route
   * @param string $key
   * @param string $title
   */
  function routeTranslateTitle($route, $key, $title) {
    $this->routeMonoPlugin($route, $key, new crumbs_MonoPlugin_TranslateTitle($title));
  }

  /**
   * Register a callback that will determine a title for a breadcrumb item.
   *
   * @param string $route
   *   The route where this callback should be used, e.g. "node/%".
   * @param string $key
   *   The plugin key under which this callback will be listed on the weights
   *   configuration form.
   * @param callback $callback
   *   The callback, e.g. an anonymous function. The signature must be
   *   $callback(string $path, array $item), like the findParent() method of
   *   a typical crumbs_MonoPlugin.
   */
  function routeTitleCallback($route, $key, $callback) {
    $this->routeMonoPlugin($route, $key, new crumbs_MonoPlugin_TitleCallback($callback, $this->module, $key));
    $this->callbackCollection->addCallback($this->module, 'routeTitle', $key, $callback);
  }

  /**
   * @param string $route
   * @param string $key
   */
  function routeSkipItem($route, $key) {
    $this->routeMonoPlugin($route, $key, new crumbs_MonoPlugin_SkipItem());
  }

  /**
   * Set specific rules as disabled by default.
   *
   * @param array|string $keys
   *   Array of keys, relative to the module name, OR
   *   a single string key, relative to the module name.
   */
  function disabledByDefault($keys = NULL) {
    if (is_array($keys)) {
      foreach ($keys as $key) {
        $this->_disabledByDefault($key);
      }
    }
    else {
      $this->_disabledByDefault($keys);
    }
  }

  /**
   * @param string|NULL $key
   */
  protected function _disabledByDefault($key) {

//    return "_disabled by default testing" . $key;


    $key = isset($key)
      ? ($this->module . '.' . $key)
      : $this->module;
    $this->defaultValueCollection->setDefaultValue($key, FALSE);
  }

}
