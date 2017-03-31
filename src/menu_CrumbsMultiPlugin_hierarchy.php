<?php
namespace Drupal\crumbs;

use crumbs_MultiPlugin_FindParentInterface;

class menu_CrumbsMultiPlugin_hierarchy implements crumbs_MultiPlugin_FindParentInterface {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    foreach (menu_ui_get_menus() as $key => $title) {
      $api->ruleWithLabel($key, $title, t('Menu'));
    }
    $api->descWithLabel(t('The parent item\'s path'), t('Parent'));
  }

  /**
   * {@inheritdoc}
   */
  function findParent($path, $item) {
    // Support for special_menu_items module.
    /* @see crumbs_menu() */
    /* @see menu_link_load() */
    if ('crumbs/special-menu-item/%' === $item['route']) {
      return $this->specialMenuItemFindParent($item);
    }

    $q = db_select('menu_links', 'child');
    // Join the parent item, but allow for toplevel items without a parent.
    $q->leftJoin('menu_links', 'parent', 'parent.mlid = child.plid');
    $q->addExpression('parent.link_path', 'parent_path');
    $q->addExpression('child.menu_name', 'menu_name');
    $q->addExpression('child.plid', 'plid');
    $q->condition('child.link_path', $path);

    if (\Drupal::moduleHandler()->moduleExists('i18n_menu')) {
      // Filter and sort by language.
      // The 'language' column only exists if i18n_menu is installed.
      // (See i18n_menu_install())
      $language = \Drupal\Core\Language\Language::LANGCODE_NOT_SPECIFIED;
      if (isset(\Drupal::languageManager()->getCurrentLanguage())) {
        $language = array($language, \Drupal::languageManager()->getCurrentLanguage()->language);
      }
      $q->condition('child.language', $language);
    }

    // Top-level links have higher priority.
    $q->orderBy('child.depth', 'ASC');

    // Collect candidates for the parent path, keyed by menu name.
    $candidates = array();
    foreach ($q->execute() as $row) {
      if (!array_key_exists($row->menu_name, $candidates)) {
        if ('<separator>' === $row->parent_path) {
          // Ignore separator menu items added by special_menu_items.
          continue;
        }
        if ('<nolink>' === $row->parent_path) {
          $candidates[$row->menu_name] = 'crumbs/special-menu-item/' . $row->plid;
        }
        else {
          // This may add NULL values for toplevel items.
          $candidates[$row->menu_name] = $row->parent_path;
        }
      }
    }

    // Filter out NULL values for toplevel items.
    return array_filter($candidates);
  }

  /**
   * Finds the parent path for an artificial router item representing a special
   * menu item with '<nolink>' path.
   *
   * @param array $item
   *
   * @return string[]
   *   Parent path candidates (can't be more than one).
   */
  protected function specialMenuItemFindParent(array $item) {

    if (empty($item['map'][2]['menu_name']) || empty($item['map'][2]['plid'])) {
      return array();
    }
    $menu_name = $item['map'][2]['menu_name'];
    $parent_link = \Drupal::service('plugin.manager.menu.link')->createInstance($item['map'][2]['plid']);

    if (empty($parent_link['link_path'])) {
      return array();
    }
    $parent_path = $parent_link['link_path'];

    if ('<separator>' === $parent_path) {
      return array();
    }

    if ('<nolink>' === $parent_path) {
      $parent_path = 'crumbs/special-menu-item/' . $parent_link['mlid'];
    }

    return array($menu_name => $parent_path);
  }

}
