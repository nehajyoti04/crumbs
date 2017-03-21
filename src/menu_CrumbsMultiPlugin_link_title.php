<?php
namespace Drupal\crumbs;

class menu_CrumbsMultiPlugin_link_title implements crumbs_MultiPlugin_FindTitleInterface {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    foreach (menu_get_menus() as $key => $title) {
      $api->ruleWithLabel($key, $title, t('Menu'));
    }
    $api->descWithLabel(t('Menu link title'), t('Title'));
  }

  /**
   * Find all menu links with $path as the link path.
   * For each menu, find the one with the lowest depth.
   *
   * {@inheritdoc}
   */
  function findTitle($path, $item) {

    // We need to load the original title from menu_router,
    // because _menu_item_localize() does a decision based on that, that we want
    // to reproduce.
    $q = db_select('menu_router', 'mr');
    $q->condition('path', $item['path']);
    $q->fields('mr', array('title'));
    $router_title = $q->execute()->fetchField();

    // Reproduce menu_link_load() with _menu_link_translate() and
    // _menu_item_localize(). However, a lot of information is already provided
    // in the $item argument, so we can skip these steps.
    $q = db_select('menu_links', 'ml');
    $q->fields('ml');
    $q->condition('link_path', $path);
    $q->condition('router_path', $item['path']);

    // Top-level links have higher priority.
    $q->orderBy('ml.depth', 'ASC');

    if (\Drupal::moduleHandler()->moduleExists('i18n_menu')) {
      // Filter and sort by language.
      // The 'language' column only exists if i18n_menu is installed.
      // (See i18n_menu_install())
      $language = \Drupal\Core\Language\Language::LANGCODE_NOT_SPECIFIED;
      if (isset(\Drupal::languageManager()->getCurrentLanguage())) {
        $language = array($language, \Drupal::languageManager()->getCurrentLanguage()->language);
        $q->addExpression('case ml.language when :language then 1 else 0 end', 'has_language', array(':language' => \Drupal\Core\Language\Language::LANGCODE_NOT_SPECIFIED));
        $q->orderBy('has_language');
      }
      $q->condition('language', $language);
    }

    $result = $q->execute();

    $titles = array();
    while ($row = $result->fetchAssoc()) {
      if (!isset($titles[$row['menu_name']])) {
        $link = $row + $item;
        if ($row['link_title'] == $router_title) {
          // Use the localized title from menu_router.
          // Fortunately, this is already computed by menu_get_item().
          $link['title'] = $item['title'];
        }
        else {
          // Use the untranslated title from menu_links.
          $link['title'] = $row['link_title'];
        }
        if (!is_array($link['options'])) {
          // hook_translated_menu_link_alter() expects options to be an array.
          $link['options'] = unserialize($link['options']);
        }
        if (1
          // Check if i18n_menu < 7.x-1.8 is installed.
          // We need to support older versions, because 7.x-1.8 is a bit buggy.
          // See http://drupal.org/node/1781112#comment-7163324
          && \Drupal::moduleHandler()->moduleExists('i18n_menu')
          && !function_exists('_i18n_menu_link_process')
        ) {
          if (1
            && isset($link['language'])
            && $link['language'] === \Drupal\Core\Language\Language::LANGCODE_NOT_SPECIFIED
          ) {
            // i18n_menu_translated_menu_link_alter() in older versions of
            // i18n_menu expects $link['language'] to be empty for language
            // neutral.
            unset($link['language']);
          }
          // Give other modules (e.g. i18n_menu) a chance to alter the title.
          \Drupal::moduleHandler()->alter('translated_menu_link', $link, $item['map']);
          // i18n_menu < 7.x-1.8 sets the 'link_title' instead of 'title'.
          $titles[$row['menu_name']] = $link['link_title'];
        }
        else {
          // Give other modules (e.g. i18n_menu) a chance to alter the title.
          \Drupal::moduleHandler()->alter('translated_menu_link', $link, $item['map']);
          $titles[$row['menu_name']] = $link['title'];
        }
      }
    }
    return $titles;
  }
}
