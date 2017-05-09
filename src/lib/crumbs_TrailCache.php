<?php

namespace Drupal\crumbs\lib;

/**
 * Class crumbs_Container_LazyTrails
 */
class crumbs_TrailCache {

  /**
   * @var array
   *   Cached data
   */
  protected $data = array();

  /**
   * @var crumbs_TrailFinder
   */
  protected $crumbs_TrailFinder;

  /**
   * @todo Add an interface for $source.
   *   Don't restrict it to crumbs_TrailFinder.
   *
   * @param crumbs_TrailFinder $source
   */
  function __construct(crumbs_TrailFinder $crumbs_TrailFinder) {
    $this->crumbs_TrailFinder = $crumbs_TrailFinder;
  }

  /**
   * @param $path
   * @return mixed
   */
  function getForPath($path) {
//     print '<pre>'; print_r("TrailCache :: getForPath"); print '</pre>';
//     print '<pre>'; print_r("this->data[$path])"); print '</pre>';
//     print '<pre>'; print_r($this->data[$path]); print '</pre>';
    if (!isset($this->data[$path])) {
//      print '<pre>'; print_r("TrailCache :: getForPath - inside if"); print '</pre>';
      $this->data[$path] = $this->crumbs_TrailFinder->getForPath($path);
//      $this->data[$path] = \Drupal::service('crumbs.trail_finder')->getForPath($path);
    }
//    print '<pre>'; print_r("return TrailCache :: getForPath"); print '</pre>';
//    print '<pre>'; print_r($this->data[$path]); print '</pre>';
    return $this->data[$path];
  }
}
