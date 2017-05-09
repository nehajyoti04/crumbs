<?php

namespace Drupal\crumbs\lib;

use Drupal\Core\Url;

class crumbs_TrailFinder {

  /**
   * @var crumbs_ParentFinder
   */
  protected $parentFinder;

  /**
   * @var crumbs_Router;
   */
  protected $router;

  /**
   * @param crumbs_ParentFinder $parent_finder
   * @param crumbs_Router $router
   */
  function __construct(crumbs_ParentFinder $parent_finder, crumbs_Router $router) {
    $this->parentFinder = $parent_finder;
    $this->router = $router;
  }

  /**
   * @param string $path
   * @return array
   */
  function getForPath($path) {
//    print '<pre>'; print_r("crumbs_TrailFinder :: getForPath()"); print '</pre>';
    return $this->buildTrail($path);
  }

  /**
   * Build the raw trail.
   *
   * @param string $path
   * @return array
   */
  function buildTrail($path) {
//    print '<pre>'; print_r("trail finder path"); print '</pre>';
//    print '<pre>'; print_r($path); print '</pre>';
    $path = $this->router->getNormalPath($path);

//    $path = \Drupal::service('crumbs.router')->getNormalPath($path);
//    print '<pre>'; print_r("trail finder - get normal path"); print '</pre>';
//    print '<pre>'; print_r($path); print '</pre>';
    $trail_reverse = array();
    $front_normal_path = $this->router->getFrontNormalPath();
//    $front_normal_path = \Drupal::service('crumbs.router')->getFrontNormalPath();
//    print '<pre>'; print_r("front normal path"); print '</pre>';
//    print '<pre>'; print_r($front_normal_path); print '</pre>';
//    print '<pre>'; print_r("path - before while loop"); print '</pre>';
//    print '<pre>'; print_r($path); print '</pre>';
    while (strlen($path) && $path !== '<front>' && $path !== $front_normal_path) {
//      print '<pre>'; print_r("path - after while loop"); print '</pre>';
//      print '<pre>'; print_r($path); print '</pre>';
      if (isset($trail_reverse[$path])) {
        // We found a loop! To prevent infinite recursion, we
        // remove the loopy paths from the trail and finish directly with <front>.
        while (isset($trail_reverse[$path])) {
          array_pop($trail_reverse);
        }
        break;
      }
      /** @var array|null $item */
//      print '<pre>'; print_r("build trial - path - inside while loop"); print '</pre>';
//      print '<pre>'; print_r($path); print '</pre>';
      $item = \Drupal::service('crumbs.router')->getRouterItem($path);
      // If this menu item is a default local task and links to its parent,
      // skip it and start the search from the parent instead.

//      print '<pre>'; print_r("initial - item"); print '</pre>';
//      print '<pre>'; print_r($item); print '</pre>';

      if ($item && ($item['type'] & MENU_LINKS_TO_PARENT)) {
        $path = $item['tab_parent_href'];
//        print '<pre>'; print_r("item - tab parent href"); print '</pre>';
//        print '<pre>'; print_r($item['tab_parent_href']); print '</pre>';
        $item = \Drupal::service('crumbs.router')->getRouterItem($item['tab_parent_href']);
      }

      // For a path to be included in the trail, it must resolve to a valid
      // router item, and the access check must pass.
//      print '<pre>'; print_r("trail reverse - before path set"); print '</pre>';
//      print '<pre>'; print_r($trail_reverse); print '</pre>';

//      print '<pre>'; print_r("path - before path set"); print '</pre>';
//      print '<pre>'; print_r($path); print '</pre>';
//      print '<pre>'; print_r("item - before path set"); print '</pre>';
//      print '<pre>'; print_r($item); print '</pre>';

//      if ($item && $item['access']) {
//        $trail_reverse[$path] = $item;
//
//      }
      // @TODO get $item properly & fix getRouterItem() in Router.php.
      if ($item) {
        $trail_reverse[$path] = $item;

      }

//      print '<pre>'; print_r("trail reverse - after path set"); print '</pre>';
//      print '<pre>'; print_r($trail_reverse); print '</pre>';
//      $parent_path = $this->parentFinder->getParentPath($path, $item);
      $parent_path = \Drupal::service('crumbs.parent_finder')->getParentPath($path, $item);
      if ($parent_path === $path) {
        // This is again a loop, but with just one step.
        // Not as evil as the other kind of loop.
        break;
      }
//      print '<pre>'; print_r("parent path"); print '</pre>';
//      print '<pre>'; print_r($parent_path); print '</pre>';
      $path = $parent_path;
    }
    unset($trail_reverse['<front>']);
//    print '<pre>'; print_r("trail reverse - after front unset"); print '</pre>';
//    print '<pre>'; print_r($trail_reverse); print '</pre>';


//    print '<pre>'; print_r("front normal path"); print '</pre>';
//    print '<pre>'; print_r($front_normal_path); print '</pre>';

    // Only prepend a frontpage item, if the configured frontpage is valid.
    $front_menu_item = \Drupal::service('crumbs.router')->getRouterItem($front_normal_path);
//    print '<pre>'; print_r("front menu item"); print '</pre>';
//    print '<pre>'; print_r($front_menu_item); print '</pre>';
//    print '<pre>'; print_r("front normal path"); print '</pre>';
//    print '<pre>'; print_r($front_normal_path); print '</pre>';
    if (isset($front_menu_item)) {
      $front_menu_item['href'] = '<front>';
      $trail_reverse[$front_normal_path] = $front_menu_item;
//      print '<pre>'; print_r("isset front_menu_item - trail_reverse"); print '</pre>';
//      print '<pre>'; print_r($trail_reverse); print '</pre>';
    }
    else {
      $message_raw = 'Your current setting for !site_frontpage seems to be invalid.';
      $message_replacements = array(
        '!site_frontpage' => '<em>' . \Drupal::l(t('Default front page'), Url::fromRoute('system.site_information_settings')) . '</em>',
      );
      \Drupal::logger('crumbs')->notice($message_raw, $message_replacements);
      if (\Drupal::currentUser()->hasPermission('administer site configuration')) {
        drupal_set_message(t($message_raw, $message_replacements), 'warning');
      }
    }

//    print '<pre>'; print_r("trail finder - build path - return value"); print '</pre>';
//    print '<pre>'; print_r(array_reverse($trail_reverse)); print '</pre>';


    return array_reverse($trail_reverse);
  }

}
