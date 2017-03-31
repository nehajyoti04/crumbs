<?php

namespace Drupal\crumbs\Controller;


/**
 * Class GithubConnectController.
 *
 * @package Drupal\github_connect\Controller
 */
class CrumbsController {

  public function crumbsDebugPage() {
    drupal_set_title('Crumbs debug');

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

    $path_checked = check_plain($path_to_test);
    $form_action = url('admin/structure/crumbs/debug');

    $input_html = <<<EOT
<input id="crumbs-admin-debug-path" type="text" class="form-text" size="40" name="path_to_test" value="$path_checked" />
<input type="submit" value="Go" class="form-submit" />
EOT;
    $input_html = t('Breadcrumb for: !text_field', array('!text_field' => $input_html));

    $placeholders = array(
      '!plugin_weights' => l(t('Plugin weights'), 'admin/structure/crumbs'),
      '!display_settings' => l(t('Display settings'), 'admin/structure/crumbs/display'),
    );

    $paragraphs = array();
    $paragraphs[] = <<<EOT
This page allows to have a look "behind the scenes", so you can analyse which crumbs plugins and rules are responsible for the "crumbs parent" to a given system path.
EOT;

    $paragraphs[] = <<<EOT
For each breadcrumb item, the Crumbs plugins can suggest candidates for the parent path and the breadcrumb item title.
Crumbs assigns a weight to each candidate, depending on the !plugin_weights configuration.
The candidate with the smallest weight wins.
EOT;

    $paragraphs[] = <<<EOT
Please note that some items may still be hidden, depending on the !display_settings.
EOT;

    $text = '';
    foreach ($paragraphs as $paragraph) {
      $paragraph = str_replace("\n", '<br/>', $paragraph);
      $text .= '<p>' . t($paragraph, $placeholders) . '</p>' . "\n";
    }

    $html = <<<EOT
    <form method="get" action="$form_action">
      $text
      <label for="path">$input_html</label>
    </form>
EOT;

    if (strlen($path_to_test)) {
      $path_to_test = drupal_get_normal_path($path_to_test);
      $html .= _crumbs_admin_debug_matrix($path_to_test);
    }

    if (!empty($_SESSION['crumbs.admin.debug.history'])) {
      $recently_visited = '';
      foreach (array_reverse($_SESSION['crumbs.admin.debug.history']) as $path => $true) {
        if ('admin/structure/crumbs/debug' !== substr($path, 0, 28)) {
          // We can't use l() directly, since this would add an "active" class.
          $url = url('admin/structure/crumbs/debug', array('query' => array('path_to_test' => $path)));
          $link = l($path, $url, array('external' => TRUE));
          $recently_visited .= '<li>' . $link . '</li>';
        }
      }
      if ($recently_visited) {
        $html .= t('Recently visited') . ':<ol>' . $recently_visited . '</ol>';
      }
    }

    return $html;
  }

  function crumbsSpecialMenuLinkPage(array $menu_link) {
//    drupal_goto('<front>');
  }

}