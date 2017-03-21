<?php
namespace Drupal\crumbs;

/**
 * Determines a breadcrumb item title based on the view title of a page view.
 * The same plugin class is used for Views displays with or without arguments.
 */
class views_CrumbsMonoPlugin_PageTitle implements crumbs_MonoPlugin_FindTitleInterface {

  /**
   * @var string
   */
  private $displayId;

  /**
   * @var string
   */
  private $viewName;

  /**
   * @param string $view_name
   * @param string $display_id
   */
  function __construct($view_name, $display_id) {
    $this->viewName = $view_name;
    $this->displayId = $display_id;
  }

  /**
   * @param crumbs_InjectedAPI_describeMonoPlugin $api
   *   Injected API object, with methods that allows the plugin to further
   *   describe itself.
   *
   * @return string|void
   *   As an alternative to the API object's methods, the plugin can simply
   *   return a string label.
   */
  function describe($api) {
    return t('Views page title');
  }

  /**
   * {@inheritdoc}
   */
  function findTitle($path, $item) {

    // Some checks, to verify that the menu_router entry has not changed since
    // this Crumbs plugin was created and cached.
    if (0
      || 'views_page' !== $item['page_callback']
      || 2 > count($item['page_arguments'])
      || $this->viewName !== $item['page_arguments'][0]
      || $this->displayId !== $item['page_arguments'][1]
    ) {
      return NULL;
    }

    if ('%' !== substr($item['route'], -1)) {
      return $this->viewsPageTitle();
    }
    else {
      $args = array_slice($item['page_arguments'], 2);
      return $this->viewsArgTitle($args);
    }
  }

  /**
   * Loads the view and determines a breadcrumb item title based on the title
   * configured for this view. This is used if the views path does NOT end with
   * '%'.
   *
   * @return null|string
   */
  private function viewsPageTitle() {

    // Build and initialize the view.
    $view = views_get_view($this->viewName);
    $view->set_display($this->displayId);

    // Trigger the title calculation by calling build_title().
    $view->build_title();
    $title = $view->get_title();

    if (is_string($title) && '' !== $title) {
      // Use decode_entities() to undo duplicate check_plain().
      // See https://drupal.org/comment/7916895#comment-7916895
      return \Drupal\Component\Utility\Html::decodeEntities($title);
    }

    return NULL;
  }

  /**
   * Loads the view and determines a breadcrumb item title based on the last
   * argument. This is used for views paths that end with '%'.
   *
   * @param array $args
   *   Argument values from the url.
   *
   * @return null|string
   *   A breadcrumb item title for the last argument, or NULL if none found.
   *   This will use the breadcrumb token string configured for the Views arg.
   *
   * @see view::_build_arguments()
   */
  private function viewsArgTitle(array $args) {

    // Build and initialize the view.
    $view = views_get_view($this->viewName);
    $view->set_display($this->displayId);
    $view->set_arguments($args);

    // Trigger the argument calculation by calling build_title().
    $view->build_title();

    // Check the last argument for a breadcrumb item title.
    $last_arg = $this->getRelevantArgument($view->argument);
    if (!isset($last_arg)) {
      return NULL;
    }

    if (!empty($last_arg->options['breadcrumb_enable']) && !empty($last_arg->options['breadcrumb'])) {
      $token_string = $last_arg->options['breadcrumb'];
    }
    elseif (!empty($last_arg->options['title_enable']) && !empty($last_arg->options['title'])) {
      $token_string = $last_arg->options['title'];
    }

    if (!empty($token_string)) {
      // Use decode_entities() to undo duplicate check_plain().
      // See https://drupal.org/comment/7916895#comment-7916895
      return \Drupal\Component\Utility\Html::decodeEntities(strtr($token_string, $view->build_info['substitutions']));
    }

    return NULL;
  }

  /**
   * @param views_handler_argument[] $arguments
   *
   * @return null|views_handler_argument
   */
  private function getRelevantArgument(array $arguments) {
    while (!empty($arguments)) {
      /** @var views_handler_argument $arg */
      $arg = array_pop($arguments);
      if (1
        && is_object($arg)
        && !$arg->is_exception()
        // Argument must have a value.
        && isset($arg->argument)
      ) {
        return $arg;
      }
    }

    return NULL;
  }

}
