<?php

namespace Drupal\crumbs;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Defines an interface for tour items.
 *
 * @see \Drupal\crumbs\Annotation
 * @see \Drupal\crumbs\crumbsPluginBase
 * @see \Drupal\crumbs\crumbsPluginManager
 * @see plugin_api
 */
interface crumbsPluginInterface extends ContainerFactoryPluginInterface  {

  /**
   * Returns id of the tip.
   *
   * @return string
   *   The id of the tip.
   */
  public function id();

  /**
   * Returns label of the tip.
   *
   * @return string
   *   The label of the tip.
   */
  public function getLabel();

  /**
   * Returns weight of the tip.
   *
   * @return string
   *   The weight of the tip.
   */
  public function getWeight();

  /**
   * Returns an array of attributes for the tip wrapper.
   *
   * @return array
   *   An array of classes and values.
   */
  public function getAttributes();

  /**
   * Used for returning values by key.
   *
   * @var string
   *   Key of the value.
   *
   * @return string
   *   Value of the key.
   */
  public function get($key);

  /**
   * Used for returning values by key.
   *
   * @var string
   *   Key of the value.
   *
   * @var string
   *   Value of the key.
   */
  public function set($key, $value);

  /**
   * Returns a renderable array.
   *
   * @return array
   *   A renderable array.
   */
  public function getOutput();


  /**
   * Return the name of the ice cream flavor.
   *
   * @return string
   */
  public function getName();
  /**
   * Return the price per scoop of the ice cream flavor.
   *
   * @return float
   */
  public function getPrice();
  /**
   * A slogan for the ice cream flavor.
   *
   * @return string
   */
  public function slogan();

}
