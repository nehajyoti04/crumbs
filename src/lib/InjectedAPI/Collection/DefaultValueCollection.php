<?php

namespace Drupal\crumbs\lib\injectedAPI\Collection;

use Prophecy\Exception\Exception;

class DefaultValueCollection {

  /**
   * Default weights for some plugin keys.
   *
   * @var mixed[]
   *   Format: $[$key] = $weight|false
   */
  protected $defaultValues = array();

  /**
   * @return mixed[]
   *   Format: $[$key] = false|$weight
   * @throws Exception
   */
  function getDefaultValues() {
    return $this->defaultValues;
  }

  /**
   * @param string $key
   * @param int|false $value
   */
  function setDefaultValue($key, $value) {
    $this->defaultValues[$key] = $value;
  }

} 
