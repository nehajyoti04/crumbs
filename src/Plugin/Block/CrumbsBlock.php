<?php

namespace Drupal\crumbs\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\crumbs\lib\crumbs_CurrentPageInfo;
use Drupal\crumbs\lib\DIC\crumbs_DIC_ServiceContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Crumbs' block.
 *
 * @Block(
 *   id = "crumbs_block",
 *   admin_label = @Translation("Breadcrumb (Crumbs)"),
 * )
 */
class CrumbsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;
  protected $serviceContainer;
  protected $currentPageInfo;

  /**
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   A config factory for retrieving required config objects.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, crumbs_DIC_ServiceContainer $serviceContainer, crumbs_CurrentPageInfo $currentPageInfo) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->serviceContainer = $serviceContainer;
    $this->currentPageInfo = $currentPageInfo;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('crumbs_service_container'),
      $container->get('crumbs.current_page_info')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $breadcrumbHtml = $this->currentPageInfo->breadcrumbHtml();
//    print '<pre>'; print_r("breadcrumbHtml"); print '</pre>';
//    print '<pre>'; print_r($breadcrumbHtml); print '</pre>';

//    $serviceContainer = $this->serviceContainer->page->breadcrumbHtml;
//    print '<pre>'; print_r("service container"); print '</pre>';
//    print '<pre>'; print_r($serviceContainer); print '</pre>';


//    $current_page_info = crumbs_CurrentPageInfo::breadcrumbHtml();

//    print '<pre>'; print_r("current page info"); print '</pre>';
//    print '<pre>'; print_r($current_page_info); print '</pre>';


    $build['#cache'] = [
      'max-age' => 0,
    ];

    $build[] = $breadcrumbHtml;
    return $build;

  }

}
