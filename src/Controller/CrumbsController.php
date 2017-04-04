<?php

namespace Drupal\crumbs\Controller;
use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;


/**
 * Class GithubConnectController.
 *
 * @package Drupal\github_connect\Controller
 */
class CrumbsController {

  public function crumbsDebugPage() {
    $path_to_test = '';
    if (isset($_GET['path_to_test'])) {
      $path_to_test = $_GET['path_to_test'];
    }
    elseif (!empty($_SESSION['crumbs.admin.debug.history'])) {
      foreach ($_SESSION['crumbs.admin.debug.history'] as $path => $true) {
        if ('admin' !== substr($path, 0, 5)) {
          $path_to_test = $path;
        }
        elseif ('admin/structure/crumbs' !== substr($path, 0, 22)) {
          $admin_path_to_test = $path;
        }
      }
      if (empty($path_to_test) && !empty($admin_path_to_test)) {
        $path_to_test = $admin_path_to_test;
      }
    }

    $path_checked = Html::escape($path_to_test);
    $form_action = Url::fromUserInput('/admin/structure/crumbs/debug')->toString();
    $user_weight_url = Url::fromUserInput('/admin/structure/crumbs/debug');
    $user_display_url = Url::fromUserInput('/admin/structure/crumbs/display');

//    $input_html = <<<EOT
//<input id="crumbs-admin-debug-path" type="text" class="form-text" size="40" name="path_to_test" value="$path_checked" />
//<input type="submit" value="Go" class="form-submit" />
//EOT;

    $input_html = '<input id="crumbs-admin-debug-path" type="text" class="form-text" size="40" name="path_to_test" value="$path_checked" />
<input type="submit" value="Go" class="form-submit" />';

    $input_html = array();
//    $input_html['path_to_test'] = array(
//      '#type' => 'textfield',
////      '#title' => $this->t('Subject'),
//      '#default_value' => $path_checked,
//      '#size' => 60,
//      '#maxlength' => 128,
//      '#required' => TRUE,
//    );
//
//
//    $input_html = t('Breadcrumb for: %text_field', array('%text_field' => $input_html));

    $input_html['path_to_test'] = array(
      '#type' => 'textfield',
//      '#title' => $this->t('Subject'),
      '#default_value' => $path_checked,
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => TRUE,
    );
    $input_html = $this->formElement();

    $placeholders = array(
      '!plugin_weights' => \Drupal::l(t('Plugin weights'), $user_weight_url),
      '!display_settings' => \Drupal::l(t('Display settings'), $user_display_url),
    );

    $paragraphs = array();
//    $paragraphs[] = <<<EOT
//This page allows to have a look "behind the scenes", so you can analyse which crumbs plugins and rules are responsible for the "crumbs parent" to a given system path.
//EOT;

    $paragraphs[] = 'This page allows to have a look "behind the scenes", so you can analyse which crumbs plugins and rules are responsible for the "crumbs parent" to a given system path.';

//    $paragraphs[] = <<<EOT
//For each breadcrumb item, the Crumbs plugins can suggest candidates for the parent path and the breadcrumb item title.
//Crumbs assigns a weight to each candidate, depending on the !plugin_weights configuration.
//The candidate with the smallest weight wins.
//EOT;
    $paragraphs[] = "
For each breadcrumb item, the Crumbs plugins can suggest candidates for the parent path and the breadcrumb item title.
Crumbs assigns a weight to each candidate, depending on the !plugin_weights configuration.
The candidate with the smallest weight wins.";

//    $paragraphs[] = <<<EOT
//Please note that some items may still be hidden, depending on the !display_settings.
//EOT;
    $paragraphs[] = 'Please note that some items may still be hidden, depending on the !display_settings.';


    $text = '';
    foreach ($paragraphs as $paragraph) {
      $paragraph = str_replace("\n", '<br/>', $paragraph);
      $text .= '<p>' . t($paragraph, $placeholders) . '</p>' . "\n";
    }

//    $html = <<<EOT
//    <form method="get" action=" . $form_action . ">
//      . $text .
//      <label for="path">$input_html</label>
//    </form>
//EOT;

    $html = '<form method="get" action="' . $form_action . '">'
      . $text .
      '<label for="path">' . $input_html . '</label>
    </form>';

    if (strlen($path_to_test)) {
      $path_to_test = $system_path = \Drupal::service('path.alias_manager')->getPathByAlias($path_to_test);
      $html .= _crumbs_admin_debug_matrix($path_to_test);
    }

    if (!empty($_SESSION['crumbs.admin.debug.history'])) {
      $recently_visited = '';
      foreach (array_reverse($_SESSION['crumbs.admin.debug.history']) as $path => $true) {
        if ('admin/structure/crumbs/debug' !== substr($path, 0, 28)) {
          // We can't use l() directly, since this would add an "active" class.
          $url = Url::fromUserInput('admin/structure/crumbs/debug', array('query' => array('path_to_test' => $path)));
          $link = \Drupal::l($path, $url, array('external' => TRUE));
          $recently_visited .= '<li>' . $link . '</li>';
        }
      }
      if ($recently_visited) {
        $html .= t('Recently visited') . ':<ol>' . $recently_visited . '</ol>';
      }
    }

//    return $html;
//    return array(
//      '#markup' => $html,
//    );

    return [
      '#theme' => 'crumbs_debug_page',
      '#crumbs_debug_page' => $html,
    ];

  }

  function crumbsSpecialMenuLinkPage(array $menu_link) {
//    drupal_goto('<front>');
  }

  public function formElement() {
//    $countries = \Drupal::service('country_manager')->getList();
    $element['value'] = array(
        '#type' => 'textfield',
//        '#default_value' =>  (isset($items[$delta]->value) && isset($countries[$items[$delta]->value])) ? $countries[$items[$delta]->value] : '',
//        '#autocomplete_route_name' => $this->getSetting('autocomplete_route_name'),
//        '#autocomplete_route_parameters' => array(),
        '#size' => 60,
//        '#placeholder' => $this->getSetting('placeholder'),
        '#maxlength' => 255,
//        '#element_validate' => array('country_autocomplete_validate'),
      );
    return $element;
  }

}
