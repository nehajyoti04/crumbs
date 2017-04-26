<?php

namespace Drupal\crumbs;

use Drupal\Core\Plugin\PluginBase;

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
  public function set($key, $value) {
    $this->configuration[$key] = $value;
  }

}
