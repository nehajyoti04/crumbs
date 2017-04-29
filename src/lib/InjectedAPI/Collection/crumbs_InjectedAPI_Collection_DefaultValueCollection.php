<?php

namespace Drupal\crumbs\lib\InjectedAPI\Collection;

use Symfony\Component\Config\Definition\Exception\Exception;

class crumbs_InjectedAPI_Collection_DefaultValueCollection {

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
    print '<pre>'; print_r("default values - key - value"); print '</pre>';
    print '<pre>'; print_r($key . " ". $value ); print '</pre>';
  }

} 
