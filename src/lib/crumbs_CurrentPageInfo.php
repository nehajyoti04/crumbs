<?php

namespace Drupal\crumbs\lib;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\crumbs\lib\Container\crumbs_Container_AbstractLazyData;

/**
 * Creates various data related to the current page.
 *
 * The data is provided to the rest of the world via crumbs_Container_LazyData.
 * Each method in here corresponds to one key on the data cache.
 *
 * The $page argument on each method is the data cache itself.
 * The argument can be mocked with a simple stdClass, to test the behavior of
 * each method. (if we had the time to write unit tests)
 *
 * @property bool $breadcrumbSuppressed
 * @property array $breadcrumbData
 * @property array $trail
 * @property array $rawBreadcrumbItems
 * @property bool $showCurrentPage
 * @property bool $trailingSeparator
 * @property bool $showFrontPage
 * @property int $minTrailItems
 * @property string $separator
 * @property bool $separatorSpan
 * @property int $minVisibleItems
 * @property array $breadcrumbItems
 * @property string $breadcrumbHtml
 * @property string $path
 *
 * @see crumbs_Container_AbstractLazyData::__get()
 * @see crumbs_Container_AbstractLazyData::__set()
 */
class crumbs_CurrentPageInfo extends crumbs_Container_AbstractLazyData {


  /**
   * @var crumbs_TrailCache
   */
  protected $trails;

  /**
   * @var crumbs_BreadcrumbBuilder
   */
  protected $breadcrumbBuilder;

  /**
   * @var crumbs_Router
   */
  protected $router;

  /**
   * @param crumbs_TrailCache $trails
   * @param crumbs_BreadcrumbBuilder $breadcrumbBuilder
   * @param crumbs_Router $router
   */
  function __construct(crumbs_TrailCache $trails, crumbs_BreadcrumbBuilder $breadcrumbBuilder, crumbs_Router $router) {
    $this->trails = $trails;
    $this->breadcrumbBuilder = $breadcrumbBuilder;
    $this->router = $router;
  }

  /**
   * Check if the breadcrumb is to be suppressed altogether.
   *
   * @return bool
   *
   * @see crumbs_CurrentPageInfo::$breadcrumbSuppressed
   */
  protected function breadcrumbSuppressed() {
    // @todo Make this work!
    return FALSE;
    $existing_breadcrumb = drupal_get_breadcrumb();
    // If the existing breadcrumb is empty, that means a module has
    // intentionally removed it. Honor that, and stop here.
    return empty($existing_breadcrumb);
  }

  /**
   * Assemble all breadcrumb data.
   *
   * @return array
   *
   * @see crumbs_CurrentPageInfo::$breadcrumbData
   */
  protected function breadcrumbData() {
    if (empty($this->breadcrumbItems)) {
      return FALSE;
    }
    return array(
      'trail' => $this->trail,
      'items' => $this->breadcrumbItems,
      'html' => $this->breadcrumbHtml,
    );
  }

  /**
   * Build the Crumbs trail.
   *
   * @return array
   *
   * @see crumbs_CurrentPageInfo::$trail
   */
  protected function trail() {
//    return $this->trails->getForPath($this->path);
//    return $this->crumbs_TrailCache->getForPath($this->path);
    return \Drupal::service('crumbs.trail_cache')->getForPath($this->path);
  }

  /**
   * Build the raw breadcrumb based on the $page->trail.
   *
   * Each breadcrumb item is a router item taken from the trail, with
   * two additional/updated keys:
   * - title: The title of the breadcrumb item as received from a plugin.
   * - localized_options: An array of options passed to l() if needed.
   *
   * The altering will happen in a separate step, so
   *
   * @return array
   *
   * @see crumbs_CurrentPageInfo::$rawBreadcrumbItems
   */
  protected function rawBreadcrumbItems() {
    if ($this->breadcrumbSuppressed) {
      return array();
    }
    if (\Drupal::currentUser()->hasPermission('administer crumbs')) {
      // Remember which pages we are visiting,
      // for the autocomplete on admin/structure/crumbs/debug.
      unset($_SESSION['crumbs.admin.debug.history'][$this->path]);
      $_SESSION['crumbs.admin.debug.history'][$this->path] = TRUE;
      // Never remember more than 15 links.
      while (15 < count($_SESSION['crumbs.admin.debug.history'])) {
        array_shift($_SESSION['crumbs.admin.debug.history']);
      }
    }
    $trail = $this->trail;
//    print '<pre>'; print_r("count(trail)"); print '</pre>';
//    print '<pre>'; print_r(count($trail)); print '</pre>';
//    print '<pre>'; print_r("minTrailItems"); print '</pre>';
//    print '<pre>'; print_r($this->minTrailItems); print '</pre>';
    if (count($trail) < $this->minTrailItems) {
      print '<pre>'; print_r("count(trail) < this->minTrailItems"); print '</pre>';
      return array();
    }
    if (!$this->showFrontPage) {
      array_shift($trail);
    }
    if (!$this->showCurrentPage) {
      array_pop($trail);
    }
    if (!count($trail)) {
//      print '<pre>'; print_r("!count(trail)"); print '</pre>';
      return array();
    }
//    print '<pre>'; print_r("current page info - trail"); print '</pre>';
//    print '<pre>'; print_r($trail); print '</pre>';
    $items = $this->breadcrumbBuilder->buildBreadcrumb($trail);
//    print '<pre>'; print_r("this breadcrumbBuilder->buildBreadcrumb(trail)"); print '</pre>';
//    print '<pre>'; print_r($items); print '</pre>';
    if (count($items) < $this->minVisibleItems) {
      // Some items might get lost due to having an empty title.
      return array();
    }
    return $items;
  }

  /**
   * Determine if we want to show the breadcrumb item for the current page.
   *
   * @return bool
   *
   * @see crumbs_CurrentPageInfo::$showCurrentPage
   */
  protected function showCurrentPage() {
    return \Drupal::state()->get('crumbs_show_current_page', FALSE) & ~CRUMBS_TRAILING_SEPARATOR;
  }

  /**
   * @return bool
   *
   * @see crumbs_CurrentPageInfo::$trailingSeparator
   */
  protected function trailingSeparator() {
    return \Drupal::state()->get('crumbs_show_current_page', FALSE) & CRUMBS_TRAILING_SEPARATOR;
  }

  /**
   * Determine if we want to show the breadcrumb item for the front page.
   *
   * @return bool
   *
   * @see crumbs_CurrentPageInfo::$showFrontPage
   */
  protected function showFrontPage() {
    return \Drupal::state()->get('crumbs_show_front_page', TRUE);
  }

  /**
   * If there are fewer trail items than this, we hide the breadcrumb.
   *
   * @return int
   *
   * @see crumbs_CurrentPageInfo::$minTrailItems
   */
  protected function minTrailItems() {
    return \Drupal::state()->get('crumbs_minimum_trail_items', 2);
  }

  /**
   * Determine separator string, e.g. ' &raquo; ' or ' &gt; '.
   *
   * @return string
   *
   * @see crumbs_CurrentPageInfo::$separator
   */
  protected function separator() {
    return Xss::filterAdmin(\Drupal::state()->get('crumbs_separator', ' &raquo; '));
  }

  /**
   * Determine separator string, e.g. ' &raquo; ' or ' &gt; '.
   *
   * @return bool
   *
   * @see crumbs_CurrentPageInfo::$separatorSpan
   */
  protected function separatorSpan() {
    return (bool)\Drupal::state()->get('crumbs_separator_span', FALSE);
  }

  /**
   * If there are fewer visible items than this, we hide the breadcrumb.
   * Every "trail item" does become a "visible item", except when it is hidden:
   * - The frontpage item might be hidden based on a setting.
   * - The current page item might be hidden based on a setting.
   * - Any item where the title is FALSE will be hidden / skipped over.
   *
   * @return int
   *
   * @see crumbs_CurrentPageInfo::$minVisibleItems
   */
  protected function minVisibleItems() {
    $n = $this->minTrailItems;
//    print '<pre>'; print_r("show current page"); print '<pre>';
//    print '<pre>'; print_r($this->showCurrentPage); print '<pre>';
    if (!$this->showCurrentPage) {
      --$n;
    }
    if (!$this->showFrontPage) {
      --$n;
    }
    return $n;
  }

  /**
   * Build altered breadcrumb items.
   *
   * @return array
   *
   * @see crumbs_CurrentPageInfo::$breadcrumbItems
   */
  public function breadcrumbItems() {
    $breadcrumb_items = $this->rawBreadcrumbItems;
//    print '<pre>'; print_r("rawBreadcrumbItems"); print '</pre>';
//    print '<pre>'; print_r($breadcrumb_items); print '</pre>';
    if (empty($breadcrumb_items)) {
      return array();
    }
//    print '<pre>'; print_r("breadCrumbs - this->path"); print '</pre>';
//    print '<pre>'; print_r($this->path); print '</pre>';
    $router_item = $this->router->getRouterItem($this->path);
    // Allow modules to alter the breadcrumb, if possible, as that is much
    // faster than rebuilding an entirely new active trail.
    // @TODO : for now commenting.
//    \Drupal::moduleHandler()->alter('menu_breadcrumb', $breadcrumb_items, $router_item);
    return $breadcrumb_items;
  }

  /**
   * Build the breadcrumb HTML.
   *
   * @return string
   *
   * @see crumbs_CurrentPageInfo::$breadcrumbHtml
   */
  public function breadcrumbHtml() {
    $breadcrumb_items = $this->breadcrumbItems;
//     print '<pre>'; print_r("breadcrumb_items"); print '</pre>';
//     print '<pre>'; print_r($breadcrumb_items); print '</pre>';
    if (empty($breadcrumb_items)) {
      return '';
    }
    $links = array();
    if ($this->showCurrentPage) {
      $last = array_pop($breadcrumb_items);
      foreach ($breadcrumb_items as $i => $item) {
        $links[$i] = theme('crumbs_breadcrumb_link', $item);
      }
      $links[] = theme('crumbs_breadcrumb_current_page', array(
        'item' => $last,
        'show_current_page' => $this->showCurrentPage,
      ));
    }
    else {
      foreach ($breadcrumb_items as $i => $item) {

        $links[$i] =  $this->_crumbs_breadcrumb_link($item);;

      }
    }
//    return theme('breadcrumb', array(
//      'breadcrumb' => $links,
//      'crumbs_breadcrumb_items' => $breadcrumb_items,
//      'crumbs_trail' => $this->trail,
//      'crumbs_separator' => $this->separator,
//      'crumbs_separator_span' => $this->separatorSpan,
//      'crumbs_trailing_separator' => $this->trailingSeparator,
//    ));

//    $output = [
//      '#theme' => 'test_breadcrumb',
//      '#test_breadcrumb_variable' => 'hello 123'
//    ];

    $output = [
      '#theme' => 'crumbs_breadcrumb',
      '#breadcrumb' => $links,
//      '#breadcrumb' => 'hello 123',
      '#crumbs_breadcrumb_items' => $breadcrumb_items,
      '#crumbs_trail' => $this->trail,
      '#crumbs_separator' => $this->separator,
      '#crumbs_separator_span' => $this->separatorSpan,
      '#crumbs_trailing_separator' => $this->trailingSeparator,
    ];
//    print '<pre>'; print_r("breadcrumb_html - last - output"); print '</pre>';
//    print '<pre>'; print_r($output); print '</pre>';
    return $output;
  }


  /**
   * Default theme implementation for theme('crumbs_breadcrumb_link').
   *
   * @param array $item
   *
   * @return string
   */
  public function _crumbs_breadcrumb_link(array $item) {
//  print '<pre>'; print_r("inside theme - breadcrumb link"); print '</pre>';
//  print '<pre>'; print_r("item href"); print '</pre>';
//  print '<pre>'; print_r($item); print '</pre>';

    if ('<nolink>' === $item['href']) {
      return \Drupal\Component\Utility\SafeMarkup::checkPlain($item['title']);
    }
    else {
      $options = isset($item['localized_options']) ? $item['localized_options'] : array();

      $link =  \Drupal::l($item['title'],  Url::fromUri('internal:/'. $item['link_path'], $options))->__toString();
//      return [
//        '#type' => 'markup',
//        'markup' => $link,
//      ];
      return $link;
      // @FIXME
// l() expects a Url object, created from a route name or external URI.
// return l($item['title'], $item['href'], $options);

    }
  }



  /**
   * Determine current path.
   *
   * @return string
   *
   * @see crumbs_CurrentPageInfo::$path
   */
  protected function path() {
    $current_path = \Drupal::service('path.current')->getPath();
//   print '<pre>'; print_r("current path"); print '</pre>';
   // print '<pre>'; print_r($_GET['q']); print '</pre>';
//   print '<pre>'; print_r($current_path); print '</pre>';
//    return $_GET['q'];
    return 'search/node';
    return $current_path;
  }



}
