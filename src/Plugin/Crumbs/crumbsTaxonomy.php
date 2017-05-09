<?php

namespace Drupal\crumbs\Plugin\Crumbs;

use Drupal\Component\Utility\Html;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\crumbs\Annotation\CrumbsAnnotation;
use Drupal\crumbs\crumbsPluginBase;


/**
 * Displays an image as a tip.
 *
 * @CrumbsAnnotation(
 *   id = "crumbs_taxonomy",
 *   title = @Translation("Taxonomy"),
 *   name = @Translation("Chocolate - Taxonomy"),
 *   price = 1.75,
 *   multipluginKey = "termParent",
 *   module = "taxonomy",
 *   disabled_by_default_key = "*",
 *   monoplugin_key = "termReference.*",
 * )
 */
class crumbsTaxonomy extends crumbsPluginBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getOutput() {

//    $block_id = $this->getDerivativeId();
//    print '<pre>'; print_r("derivative"); print '</pre>';
//    print '<pre>'; print_r($block_id); print '</pre>';
//    print '<pre>'; print_r("default value collection"); print '</pre>';
//    print '<pre>'; print_r($this->defaultValueCollection); print '</pre>';
//    $this->defaultValueCollection

    $output = $this->custom_monoplugin_describe();

    return $output;

  }

}
