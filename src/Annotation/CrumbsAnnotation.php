<?php

namespace Drupal\crumbs\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a tour item annotation object.
 *
 * Plugin Namespace: Plugin\Crumbs
 *
 * For a working example, see \Drupal\crumbs\Plugin\Crumbs\Example
 *
 * @see \Drupal\crumbs\CrumbsPluginBase
 * @see \Drupal\crumbs\CrumbsPluginInterface
 * @see \Drupal\crumbs\CrumbsManager
 * @see plugin_api
 *
 * @Annotation
 */
class CrumbsAnnotation extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The title of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;

}
