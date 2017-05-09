<?php

namespace Drupal\crumbs\Plugin\Crumbs;

use Drupal\Component\Utility\Html;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\crumbs\Annotation\CrumbsAnnotation;
use Drupal\crumbs\crumbsPluginBase;

///**
// * Displays an image as a tip.
// *
// * @CrumbsAnnotation(
// *   id = "menu",
// *   title = @Translation("Menu"),
// *   name = @Translation("Chocolate"),
// *   price = 1.75,
// *   multipluginKey = "hierarchy",
// *   module = "menu",
// *   disabled_by_default_key = "*",
// *   monoplugin_key = "home_title",
//*    deriver = "Drupal\crumbs\Plugin\Derivative\menuPlugin"
// * )
// */

//deriver = "Drupal\crumbs\Plugin\Derivative\menuPlugin"
/**
 * Displays an image as a tip.
 *
 * @CrumbsAnnotation(
 *   id = "menu",
 *   title = @Translation("Menu"),
 *   name = @Translation("Chocolate"),
 *   price = 1.75,
 *   multipluginKey = "hierarchy",
 *   module = "menu",
 *   disabled_by_default_key = "*",
 *   monoplugin_key = "home_title",
 * )
 */
class menuPlugin extends crumbsPluginBase implements ContainerFactoryPluginInterface {


  /**
   * The url which is used for the image in this Tip.
   *
   * @var string
   *   A url used for the image.
   */
  protected $url;

  /**
   * The alt text which is used for the image in this Tip.
   *
   * @var string
   *   A alt text used for the image.
   */
  protected $alt;

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

    return "hello there";
    return [

    ];
    $prefix = '<h2 class="tour-tip-label" id="tour-tip-' . $this->get('ariaId') . '-label">' . Html::escape($this->get('label')) . '</h2>';
    $prefix .= '<p class="tour-tip-image" id="tour-tip-' . $this->get('ariaId') . '-contents">';
    return [
      '#prefix' => $prefix,
      '#theme' => 'image',
      '#uri' => $this->get('url'),
      '#alt' => $this->get('alt'),
      '#suffix' => '</p>',
    ];
  }

}
