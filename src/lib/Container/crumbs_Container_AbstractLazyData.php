<?php

namespace Drupal\crumbs\lib\Container;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Container for lazy-initialized data.
 */
abstract class crumbs_Container_AbstractLazyData {

  /**
   * @var mixed[]
   */
  private $data = array();

  /**
   * @param string $key
   * @return mixed
   */
  function __get($key) {
//    print '<pre>'; print_r("abstract lazy data cached - __get - key"); print '</pre>';
//    print '<pre>'; print_r($key); print '</pre>';exit;
    if (!array_key_exists($key, $this->data)) {
      $this->data[$key] = $this->$key();
    }
    return $this->data[$key];
  }

  /**
   * @param string $key
   * @param mixed $value
   *
   * @throws Exception
   */
  function __set($key, $value) {
    if (array_key_exists($key, $this->data)) {
      throw new Exception("Value at '$key' already initialized.");
    }
    $this->data[$key] = $value;
  }

}
