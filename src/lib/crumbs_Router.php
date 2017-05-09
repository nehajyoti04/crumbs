<?php

namespace Drupal\crumbs\lib;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Routing\RequestContext;
use Drupal\Core\Url;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Wrapper for routing-related Drupal core functions.
 */
class crumbs_Router {

  /**
   * @var string
   */
  private $frontNormalPath;

  /**
   * Returns a router item.
   *
   * This is a wrapper around menu_get_item() that sets additional keys
   * (route, link_path, alias, fragments).
   *
   * @param $path
   *   The path for which the corresponding router item is returned.
   *   For example, node/5.
   *
   * @return array|null
   *   The router item.
   */
  function getRouterItem($path) {
//    print '<pre>'; print_r(" getRouterItem - path"); print '</pre>';
//    print '<pre>'; print_r($path); print '</pre>';

    $normalpath = \Drupal::service('path.alias_manager')->getPathByAlias($path);
    try {
//      print '<pre>'; print_r("normal path"); print '</pre>';
//      print '<pre>'; print_r($normalpath); print '</pre>';
//      $item = menu_get_item($normalpath);

//      $params = Url::fromUri("internal:" . $normalpath)->getRouteParameters();
//      $entity_type = key($params);
//      $entity = \Drupal::entityTypeManager()->getStorage($entity_type)->load($params[$entity_type]);
//      print '<pre>'; print_r("entity"); print '</pre>';
//      print '<pre>'; print_r($entity); print '</pre>';


//      $params = \Drupal\Core\Url::fromUserInput("/" . $normalpath)->getRouteParameters();
//      if (isset($params['node'])) {
//        $node = \Drupal\node\Entity\Node::load($params['node']);
//        print '<pre>'; print_r("node"); print '</pre>';
//        print '<pre>'; print_r($node); print '</pre>';
//        $item = $node;
//        print '<pre>'; print_r("Router - getRouterItem - item"); print '</pre>';
//        print '<pre>'; print_r($item); print '</pre>';
//      }



//      RequestStack::attributes->get();
//      \Drupal::request()->get
//      Request::get

//      $item = \Drupal::service('request_stack')->request->get($normalpath);
//      $item = Request::get($normalpath);
//      $item = \Drupal::request()->attributes->get($normalpath);



//      print '<pre>'; print_r("normal path - inside try"); print '</pre>';
//      print '<pre>'; print_r($normalpath); print '</pre>';
      $url_object = \Drupal::service('path.validator')->getUrlIfValid($normalpath);
//      print '<pre>'; print_r("url object"); print '</pre>';
//      print '<pre>'; print_r($url_object); print '</pre>';
      $item = $url_object->getRouteName();
      $route_parameters = $url_object->getrouteParameters();
      $params = \Drupal\Core\Url::fromUserInput("/search/node")->getRouteParameters();


      $menu_tree = db_select('menu_tree', 't')
        ->fields('t',['id', 'parent', 'route_parameters', 'options'])
        ->condition('t.route_name', 'search.view')
        ->execute()->fetchAssoc();

//      $result = db_select('github_connect_users', 'g_u')
//        ->fields('g_u', array('uid', 'access_token'))
//        ->condition('uid', $uid, '=')
//        ->execute()
//        ->fetchAssoc();


      $router = db_select('router', 'r')
        ->fields('r',['name', 'path', 'route'])
        ->condition('r.path', '/search/node')
        ->execute()->fetchAssoc();
      // fetchRow()

      $search_view_node_router_route = 'C:31:"Symfony\Component\Routing\Route":1311:{a:9:{s:4:"path";s:12:"/search/node";s:4:"host";s:0:"";s:8:"defaults";a:3:{s:11:"_controller";s:47:"Drupal\search\Controller\SearchController::view";s:6:"_title";s:6:"Search";s:6:"entity";s:11:"node_search";}s:12:"requirements";a:3:{s:14:"_entity_access";s:11:"entity.view";s:11:"_permission";s:14:"search content";s:7:"_method";s:8:"GET|POST";}s:7:"options";a:5:{s:14:"compiler_class";s:34:"\Drupal\Core\Routing\RouteCompiler";s:10:"parameters";a:1:{s:6:"entity";a:2:{s:4:"type";s:18:"entity:search_page";s:9:"converter";s:21:"paramconverter.entity";}}s:14:"_route_filters";a:2:{i:0;s:13:"method_filter";i:1;s:27:"content_type_header_matcher";}s:16:"_route_enhancers";a:1:{i:0;s:31:"route_enhancer.param_conversion";}s:14:"_access_checks";a:2:{i:0;s:19:"access_check.entity";i:1;s:23:"access_check.permission";}}s:7:"schemes";a:0:{}s:7:"methods";a:2:{i:0;s:3:"GET";i:1;s:4:"POST";}s:9:"condition";s:0:"";s:8:"compiled";C:33:"Drupal\Core\Routing\CompiledRoute":344:{a:11:{s:4:"vars";a:0:{}s:11:"path_prefix";s:12:"/search/node";s:10:"path_regex";s:17:"#^/search/node$#s";s:11:"path_tokens";a:1:{i:0;a:2:{i:0;s:4:"text";i:1;s:12:"/search/node";}}s:9:"path_vars";a:0:{}s:10:"host_regex";N;s:11:"host_tokens";a:0:{}s:9:"host_vars";a:0:{}s:3:"fit";i:3;s:14:"patternOutline";s:12:"/search/node";s:8:"numParts";i:2;}}}}';
      $search_view_node_router_route = 'a:9:{s:4:"path";s:12:"/search/node";s:4:"host";s:0:"";s:8:"defaults";a:3:{s:11:"_controller";s:47:"Drupal\search\Controller\SearchController::view";s:6:"_title";s:6:"Search";s:6:"entity";s:11:"node_search";}s:12:"requirements";a:3:{s:14:"_entity_access";s:11:"entity.view";s:11:"_permission";s:14:"search content";s:7:"_method";s:8:"GET|POST";}s:7:"options";a:5:{s:14:"compiler_class";s:34:"\Drupal\Core\Routing\RouteCompiler";s:10:"parameters";a:1:{s:6:"entity";a:2:{s:4:"type";s:18:"entity:search_page";s:9:"converter";s:21:"paramconverter.entity";}}s:14:"_route_filters";a:2:{i:0;s:13:"method_filter";i:1;s:27:"content_type_header_matcher";}s:16:"_route_enhancers";a:1:{i:0;s:31:"route_enhancer.param_conversion";}s:14:"_access_checks";a:2:{i:0;s:19:"access_check.entity";i:1;s:23:"access_check.permission";}}s:7:"schemes";a:0:{}s:7:"methods";a:2:{i:0;s:3:"GET";i:1;s:4:"POST";}s:9:"condition";s:0:"";s:8:"compiled";C:33:"Drupal\Core\Routing\CompiledRoute":344:{a:11:{s:4:"vars";a:0:{}s:11:"path_prefix";s:12:"/search/node";s:10:"path_regex";s:17:"#^/search/node$#s";s:11:"path_tokens";a:1:{i:0;a:2:{i:0;s:4:"text";i:1;s:12:"/search/node";}}s:9:"path_vars";a:0:{}s:10:"host_regex";N;s:11:"host_tokens";a:0:{}s:9:"host_vars";a:0:{}s:3:"fit";i:3;s:14:"patternOutline";s:12:"/search/node";s:8:"numParts";i:2;}}}';




//      $route_match = 'search.view_node_search';
//      $url = Url::fromRouteMatch($route_match);
//      print '<pre>'; print_r("route match - url"); print '</pre>';
//      print '<pre>'; print_r($url); print '</pre>';
//      if ($request = $this->getRequestForPath($url->toString(), [])) {
//      $request = 'search/node';
//      $request = $url_object->toString();
//        $context = new RequestContext();
//        $context->fromRequest($request);
////        $this->context = $context;
//        print '<pre>'; print_r("context"); print '</pre>';
//        print '<pre>'; print_r($context); print '</pre>';
////      }




//      $menu_tree = \Drupal::menuTree();
//      $menu_name = 'search.view_node_search';
//      $menu_name = 'search/node';
//// Build the typical default set of menu tree parameters.
//      $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
//      print '<pre>'; print_r("parameters"); print '</pre>';
//      print '<pre>'; print_r($parameters); print '</pre>';
//
//// Load the tree based on this set of parameters.
//      $tree = $menu_tree->load($menu_name, $parameters);
//
//// Transform the tree using the manipulators you want.
//      $manipulators = array(
//        // Only show links that are accessible for the current user.
//        array('callable' => 'menu.default_tree_manipulators:checkAccess'),
//        // Use the default sorting of menu links.
//        array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
//      );
//      $tree = $menu_tree->transform($tree, $manipulators);
//
//// Finally, build a renderable array from the transformed tree.
//      $menu = $menu_tree->build($tree);
//
//      $menu_html = drupal_render($menu);
//
//
////      print '<pre>'; print_r("menu"); print '</pre>';
////      print '<pre>'; print_r($menu); print '</pre>';
//






//      print '<pre>'; print_r("search_view_node_router_route"); print '</pre>';
//      print '<pre>'; print_r($search_view_node_router_route); print '</pre>';

//      print '<pre>'; print_r("router"); print '</pre>';
//      print '<pre>'; print_r($router); print '</pre>';

//      print '<pre>'; print_r("router - route"); print '</pre>';
//      print '<pre>'; print_r(Json::decode($router['route'])); print '</pre>';
//      print '<pre>'; print_r(Extension::unserialize($router['route'])); print '</pre>';


//      print '<pre>'; print_r("menu tree"); print '</pre>';
//      print '<pre>'; print_r($menu_tree); print '</pre>';



//      print '<pre>'; print_r("Router - params"); print '</pre>';
//      print '<pre>'; print_r($params); print '</pre>';
//
//
//      $url = Url::fromUri('internal:/search/node');
//
//      print '<pre>'; print_r("Router - url"); print '</pre>';
//      print '<pre>'; print_r($url); print '</pre>';
//
//
//      print '<pre>'; print_r("Router - getRouterItem - item"); print '</pre>';
//      print '<pre>'; print_r($item); print '</pre>';
//
//      print '<pre>'; print_r("Router - parameters"); print '</pre>';
//      print '<pre>'; print_r($route_parameters); print '</pre>';




      $item = array();
      $item['path'] = $normalpath;
    }
    catch (Exception $e) {
      // Some modules throw an exception, if a path has unloadable arguments.
      // We don't care, because we don't actually load this page.
      return NULL;
    }

    // Some additional keys.
    if (empty($item) || !is_array($item)) {
      return NULL;
    }

    // 'route' is a less ambiguous name for a router path than 'path'.
    $item['route'] = $item['path'];
    $url_object = \Drupal::service('path.validator')->getUrlIfValid($normalpath);
    $item['route'] = $url_object->getRouteName();

    // 'href' sounds more like it had already run through url().
    $item['link_path'] = $normalpath;
    $item['alias'] = \Drupal::service('path.alias_manager')->getAliasByPath("/" . $normalpath);
    $item['fragments'] = explode('/', $normalpath);

    if (!isset($item['localized_options'])) {
      $item['localized_options'] = array();
    }

    if ('crumbs_special_menu_link_page' === $item['page_callback']) {
      $item['href'] = '<nolink>';
    }

    if ($normalpath !== $item['href']) {
      $pos = strlen($item['href']);
      $item['variadic_suffix'] = substr($normalpath, $pos);
    }
    else {
      $item['variadic_suffix'] = NULL;
    }

    return $item;
  }

  /**
   * Chop off path fragments until we find a valid path.
   *
   * @param string $path
   *   Starting path or alias
   * @param int $depth
   *   Max number of fragments we try to chop off. -1 means no limit.
   *
   * @return string|null
   */
  function reducePath($path, $depth = -1) {
    $fragments = explode('/', $path);
    while (count($fragments) > 1 && $depth !== 0) {
      array_pop($fragments);
      $parent_path = implode('/', $fragments);
//      print '<pre>'; print_r("parent path - implode - fragments"); print '</pre>';
//      print '<pre>'; print_r($parent_path); print '</pre>';
      $parent_item = $this->getRouterItem($parent_path);

//      print '<pre>'; print_r("reduce path - get router item - parent item"); print '</pre>';
//      print '<pre>'; print_r($parent_item); print '</pre>';

//      if ($parent_item && $parent_item['href'] === $parent_item['link_path']) {
//        return $parent_item['link_path'];
//      }
      // @TODO for now skipping $parent_item['href'] check.
      if ($parent_item &&  $parent_item['link_path']) {
        return $parent_item['link_path'];
      }
      --$depth;
    }

    return NULL;
  }

  /**
   * @param string $path
   *
   * @return string
   */
  function getNormalPath($path) {
    return \Drupal::service('path.alias_manager')->getPathByAlias($path);
  }

  /**
   * @param string $url
   *
   * @return bool
   *   TRUE, if external path.
   */
  function urlIsExternal($url) {
    return url_is_external($url);
  }

  /**
   * @return string
   */
  function getFrontNormalPath() {
    if (isset($this->frontNormalPath)) {
      return $this->frontNormalPath;
    }
    return $this->frontNormalPath = \Drupal::service('path.alias_manager')->getPathByAlias(\Drupal::state()->get('site_frontpage', 'node'));
  }

}
