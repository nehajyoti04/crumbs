<?php
namespace Drupal\crumbs;

class taxonomy_CrumbsMultiPlugin_termParent implements crumbs_MultiPlugin {

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    foreach (\Drupal\taxonomy\Entity\Vocabulary::loadMultiple() as $voc) {
      $api->ruleWithLabel($voc->machine_name, $voc->name, t('Vocabulary'));
    }
    // Now set a generic title for the entire plugin.
    $api->descWithLabel(t('The parent term'), t('Parent'));
  }

  /**
   * Terms get their parent terms as breadcrumb parent.
   * The method name matches the router path "taxonomy/term/%".
   *
   * @param string $path
   * @param array $item
   *
   * @return array
   */
  function findParent__taxonomy_term_x($path, $item) {
    if (FALSE === $term = crumbs_Util::itemExtractEntity($item, 'taxonomy_term', 2)) {
      return NULL;
    }

    $parents = taxonomy_get_parents($term->tid);
    foreach ($parents as $parent_tid => $parent_term) {
      if ($parent_term->vocabulary_machine_name == $term->vocabulary_machine_name) {
        $uri = entity_uri('taxonomy_term', $parent_term);
        if (!empty($uri)) {
          return array($term->vocabulary_machine_name => $uri['path']);
        }
      }
    }

    return NULL;
  }
}
