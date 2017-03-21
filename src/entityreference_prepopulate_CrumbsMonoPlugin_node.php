<?php
namespace Drupal\crumbs;

class entityreference_prepopulate_CrumbsMonoPlugin_node implements crumbs_MonoPlugin_FindParentInterface {

  /**
   * @var string
   *   The node type, e.g. 'article'.
   */
  protected $nodeType;

  /**
   * @var string
   *   Field name of the entityreference field.
   */
  protected $fieldName;

  /**
   * @var string
   *   The target entity type for the entityreference field.
   */
  protected $targetType;

  /**
   * @param string $node_type
   * @param string $field_name
   * @param string $target_type
   */
  function __construct($node_type, $field_name, $target_type) {
    $this->nodeType = $node_type;
    $this->fieldName = $field_name;
    $this->targetType = $target_type;
  }

  /**
   * {@inheritdoc}
   */
  function describe($api) {
    $api->titleWithLabel(t('!field_name from request', array(
      '!field_name' => '<code>?' . $this->fieldName . '=*</code>',
    )), t('Parent'));
  }

  /**
   * {@inheritdoc}
   */
  function findParent($path, $item) {
    if (empty($_GET[$this->fieldName])) {
      return NULL;
    }

    $v = $_GET[$this->fieldName];
    if (!($v > 0)) {
      return NULL;
    }

    $target_entities = \Drupal::entityManager()->getStorage($this->targetType, array($v));
    if (empty($target_entities[$v])) {
      return NULL;
    }

    $uri = entity_uri($this->targetType, $target_entities[$v]);
    if (empty($uri['path'])) {
      return NULL;
    }

    return $uri['path'];
  }
}
