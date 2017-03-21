<?php
namespace Drupal\crumbs;

class crumbs_CrumbsEntityPlugin_TokenEnabled extends crumbs_CrumbsEntityPlugin_TokenDisabled {

  /**
   * {@inheritdoc}
   */
  function entityFindCandidate($entity, $entity_type, $distinction_key) {

    // This is cached..
    // @FIXME
// // @FIXME
// // The correct configuration object could not be determined. You'll need to
// // rewrite this call manually.
// $patterns = variable_get('crumbs_' . $entity_type . '_parent_patterns', array());


    if (empty($patterns[$distinction_key])) {
      return NULL;
    }

    // Use token to resolve the pattern.
    $info = \Drupal::entityManager()->getDefinition($entity_type);
    $token_data = array($info['token type'] => $entity);
    $token_options = array(
      'language' => \Drupal::languageManager()->getCurrentLanguage(),
      'callback' => 'crumbs_clean_token_values',
    );
    $parent = \Drupal::token()->replace($patterns[$distinction_key], $token_data, $token_options);
    if (empty($parent)) {
      // Token collapsed..
    }

    // Only accept candidates where all tokens are fully resolved.
    // This means we can't have literal '[' in the path - so be it.
    if (FALSE !== strpos($parent, '[')) {
      return NULL;
    }

    return $parent;
  }

}
