<?php
namespace Drupal\crumbs;

use crumbs_MonoPlugin;

class blog_CrumbsMonoPlugin_authorName implements crumbs_MonoPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $api->titleWithLabel(t('"!name\'s blog", where !name is the author\'s username.'), t('Title'));
  }

  /**
   * Still under constructon..
   *
   * @param string $path
   * @param array $item
   *
   * @return null|string
   */
  function findTitle__blog_x($path, $item) {
    if (FALSE === $user = crumbs_Util::itemExtractEntity($item, 'user', 1)) {
      return NULL;
    }

    return t("!name's blog", array('!name' => format_username($user)));
  }
}
