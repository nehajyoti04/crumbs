<?php
namespace Drupal\crumbs;

class comment_CrumbsMonoPlugin_reply implements crumbs_MonoPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $api->titleWithLabel(t("The comment's node"), t('Parent'));
  }

  /**
   * findParent callback for comment/reply/%.
   * Actually, system paths cam look more like comment/reply/%/%, but the router
   * path is comment/reply/%. Complain to the people who wrote comment module.
   *
   * @param string $path
   * @param array $item
   *
   * @return string
   */
  function findParent__comment_reply_x($path, $item) {
    $nid = $item['fragments'][2];
    return 'node/'. $nid;
  }
}
