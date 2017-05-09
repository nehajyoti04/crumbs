<?php

namespace Drupal\crumbs\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\crumbs\Annotation\CrumbsAnnotation;
use Symfony\Component\DependencyInjection\ContainerInterface;



/**
 * Provides block plugin definitions for mymodule blocks.
 *
 * @see \Drupal\crumbs\Plugin\Crumbs\menuPlugin
 */
class menuPlugin extends DeriverBase implements ContainerDeriverInterface {

//  /**
//   * The node storage.
//   *
//   * @var \Drupal\Core\Entity\EntityStorageInterface
//   */
//  protected $nodeStorage;
//
  /**
   * Constructs new NodeBlock.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $node_storage
   *   The node storage.
   */
  public function __construct(EntityStorageInterface $node_storage) {
    $this->nodeStorage = $node_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity.manager')->getStorage('node')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
//    print '<pre>'; print_r("base plugin definition"); print '<pre>';
//    print '<pre>'; print_r($base_plugin_definition); print '<pre>';
//    print '<pre>'; print_r($this->derivatives); print '<pre>';
//    print '<pre>'; print_r($base_plugin_definition); print '<pre>';
//    $nodes = $this->nodeStorage->loadByProperties(['type' => 'article']);
//    foreach ($nodes as $node) {
//      $this->derivatives[$node->id()] = $base_plugin_definition;
//      $this->derivatives[$node->id()]['admin_label'] = t('Node block: ') . $node->label();
//    }

//    $base_plugin_definition['id'] = 'menu';
//    $this->derivatives["menu:menu"] = $base_plugin_definition;

//    implements ContainerDerivativeInterface {

//    $this->derivatives["demo"] = $base_plugin_definition;
//    $this->derivatives["demo"]['id'] = "demo";


    $this->derivatives[$base_plugin_definition['id']] = $base_plugin_definition;
//    $this->derivatives["demo"]['id'] = "demo";
    return $this->derivatives;
  }
}