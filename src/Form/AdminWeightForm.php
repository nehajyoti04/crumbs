<?php

namespace Drupal\crumbs\Form;

use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\crumbs\crumbsPluginManager;
use Drupal\crumbs\lib\DIC\crumbs_DIC_ServiceContainer;
use Drupal\crumbs\Plugin\Crumbs\menuPlugin;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminWeightForm extends ConfigFormBase {

  protected $pluginInfo;

//  /**
//   * Class constructor.
//   */
//  public function __construct(PluginInfo $pluginInfo) {
//    $this->pluginInfo = $pluginInfo;
//  }

  protected $serviceContainer;
  protected $crumbsPluginManager;
  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  protected $menuPlugin;

  public function __construct(crumbs_DIC_ServiceContainer $serviceContainer, crumbsPluginManager $crumbsPluginManager,
                              ThemeHandlerInterface $theme_handler){
    $this->serviceContainer = $serviceContainer;
    $this->crumbsPluginManager = $crumbsPluginManager;
    $this->themeHandler = $theme_handler;
//    $this->menuPlugin = $menuPlugin;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
//    $serviceContainer->pluginInfo();
      $container->get('crumbs_service_container'),
      $container->get('plugin.manager.crumbs'),
      $container->get('theme_handler')
//      $container->get('crumbs.plugin.menu_plugin')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'crumbs_weight_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [
      'crumbs.settings',
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $type = 'tabledrag') {
    $config = $this->config('crumbs.settings');



//    print '<pre>'; print_r("service container .. "); print '</pre>';
//    print '<pre>'; print_r($this->serviceContainer->pluginInfo()); print '</pre>';

    $form = array();
//    $output = $this->menuPlugin->getOutput();
//    print '<pre>'; print_r("output here"); print '</pre>';
//    print '<pre>'; print_r($output); print '</pre>';


    $manager = $this->crumbsPluginManager;
    $plugins = $manager->getDefinitions();






////    drupal_set_message("plugins");
////    drupal_set_message(print_r($plugins, TRUE));
//    foreach ($plugins as $flavor) {
////      print '<pre>'; print_r("flavor"); print '</pre>';
////      print '<pre>'; print_r($flavor); print '</pre>';
////      $instance = $manager->createInstance($flavor['id']);
//
////      print '<pre>'; print_r("instance get output"); print '</pre>';
////      print '<pre>'; print_r($instance->getOutput()->__tostring()); print '</pre>';
//
//
//
//      $disabled_default = t("disabled by default ");
//
//      foreach($plugins as $plugin => $plugin_definition) {
////      print '<pre>'; print_r("plugin_definition"); print '</pre>';
////      print '<pre>'; print_r($plugin_definition); print '</pre>';
//
////        if(isset($plugin_definition->custom_monoplugin_describe())) {
//////          $disabled_default .="Plugin = " . $plugin . " " . $plugin_definition['disabled_by_default_key'];
////
////
////        }
//
//
//      }
//
//
//
//
//
//
//
//      foreach($plugins as $plugin => $plugin_definition) {
//        if(isset($plugin_definition['disabled_by_default_key'])) {
//          $disabled_default .="Plugin = " . $plugin . " " . $plugin_definition['disabled_by_default_key'];
//
//        }
//      }
//
//      $manager = $this->crumbsPluginManager;
//      $plugins = $manager->getDefinitions();
//      foreach ($plugins as $flavor) {
//        $instance = $manager->createInstance($flavor['id']);
//        $build_output = $instance->getOutput()->__tostring();
//      }
//
//
//
////      print '<pre>'; print_r("instance"); print '</pre>';
////      print '<pre>'; print_r($instance); print '</pre>';`
//      $build[] = array(
//        '#type' => 'markup',
//        '#markup' => $build_output . t('<p>Flavor @name, cost $@price.</p>', array('@name' => $instance->getName(), '@price' => $instance->getPrice())). $disabled_default,
//      );
//
//    }



    $type = \Drupal::service('plugin.manager.crumbs');
//    Get a list of available plugins:

    $plugin_definitions = $type->getDefinitions();

//    print '<pre>'; print_r("plugin definition"); print '</pre>';
//    print '<pre>'; print_r($plugin_definitions); print '</pre>';



//    return $build;




//    $info = ServiceContainer::pluginInfo;
//    kint("info");
//    kint($info);
//    kint("user weight");
//    kint($info->userWeights);

    // Re-discover plugins, when the admin visits the weights configuration form.
//    $info->flushCaches();

    $form['crumbs_weights'] = array(
      '#title' => t('Weights'),
      // Fetching the default value is not automated by system_settings_form().
//      '#default_value' => $info->userWeights,
//      '#crumbs_plugin_info' => $info,
    );

//    switch ($type) {
//      case 'textual':
//        // You need to enable crumbs_labs to get this.
//        $form['crumbs_weights']['#type'] = 'crumbs_weights_textual';
//        break;
//      case 'expansible':
//        // You need to enable crumbs_labs to get this.
//        $form['crumbs_weights']['#type'] = 'crumbs_weights_expansible';
//        break;
//      case 'tabledrag':
//      default:
//        $form['crumbs_weights']['#type'] = 'crumbs_weights_tabledrag';
//    }

//    $form['#submit'][] = '_crumbs_admin_flush_cache';







// Disabled PLugins

    foreach($plugins as $plugin => $plugin_definition) {
      print '<pre>'; print_r("plugin"); print '</pre>';
      print '<pre>'; print_r($plugin); print '</pre>';
//      print '<pre>'; print_r("plugin definition"); print '</pre>';
//      print '<pre>'; print_r($plugin_definition); print '</pre>';
      if(isset($plugin_definition['disabled_by_default_key'])) {


        $form['crumbs_weights'] = array(
          '#type' => 'table',
          '#header' => array(t('Disabled'), t('Title/Parent'), t('Description'), t('Weight')),
          '#empty' => t('There are no items yet. Add an item.'),
          '#tabledrag' => array(
            array(
              'action' => 'order',
              'relationship' => 'sibling',
              'group' => 'disabled-order-weight',
            ),
          ),
        );


        foreach ($plugins as $flavor) {
          $mono_plugin_key = $flavor['monoplugin_key'];
          $instance = $manager->createInstance($flavor['id']);
          $title_parent = $instance->getOutput()->__tostring();
//          $id = $flavor['id'].'.'.$mono_plugin_key;
          $id = $mono_plugin_key;
//          print '<pre>'; print_r("id"); print '</pre>';
//          print '<pre>'; print_r($id); print '</pre>';
//          print '<pre>'; print_r("config"); print '</pre>';
//          print '<pre>'; print_r($config->get('crumbs_weights')[$id]); print '</pre>';
          $form['crumbs_weights'][$id]['#attributes']['class'][] = 'draggable';
//          $form['disabled_plugin_instances'][$id]['#weight'] = $id;

          $form['crumbs_weights'][$id]['label'] = array(
            '#plain_text' => $plugin . " " . $plugin_definition['disabled_by_default_key'],
          );
//          $form['disabled_plugin_instances'][$id]['id'] = array(
//            '#plain_text' => 1,
//          );


          //          $form['disabled_plugin_instances'][$id]['#title'] = $instance->getName();

          $form['crumbs_weights'][$id]['title_parent'] = array(
            '#plain_text' => $title_parent
          );

          $form['crumbs_weights'][$id]['description'] = array(
            '#plain_text' => $instance->getName(),
          );

          // TableDrag: Weight column element.
          $form['crumbs_weights'][$id]['weight'] = array(
            '#type' => 'weight',
            '#title' => t('Weight for @title', array('@title' => $plugin . " " . $plugin_definition['disabled_by_default_key'])),
//            '#title_display' => 'invisible',
            '#default_value' => $config->get('crumbs_weights')[$id]['weight'],
            // Classify the weight element for #tabledrag.
            '#attributes' => array('class' => array('disabled-order-weight')),
          );


        }


        // TableDrag: Sort the table row according to its existing/configured weight.


        // Some table columns containing raw markup.










//        $disabled_default .="Plugin = " . $plugin . " " . $plugin_definition['disabled_by_default_key'];

      }
    }










    $form['mytable'] = array(
      '#type' => 'table',
      '#header' => array(t('Plugin'), t('Title/Parent'), t('Description'), t('Weight')),
//      '#empty' => t('There are no items yet. Add an item.', array(
//        '@add-url' => Url::fromRoute('mymodule.manage_add'),
//      )),
      '#empty' => t('There are no items yet. Add an item.'),
      // TableSelect: Injects a first column containing the selection widget into
      // each table row.
      // Note that you also need to set #tableselect on each form submit button
      // that relies on non-empty selection values (see below).
      '#tableselect' => TRUE,
      // TableDrag: Each array value is a list of callback arguments for
      // drupal_add_tabledrag(). The #id of the table is automatically prepended;
      // if there is none, an HTML ID is auto-generated.
      '#tabledrag' => array(
        array(
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'mytable-order-weight',
        ),
      ),
    );







    $id = 1;

    // TableDrag: Mark the table row as draggable.
    $form['mytable'][$id]['#attributes']['class'][] = 'draggable';
    // TableDrag: Sort the table row according to its existing/configured weight.
    $form['mytable'][$id]['#weight'] = 1;

    // Some table columns containing raw markup.
    $form['mytable'][$id]['label'] = array(
      '#plain_text' =>"test label",
    );
    $form['mytable'][$id]['id'] = array(
      '#plain_text' => 1,
    );

    // TableDrag: Weight column element.
    $form['mytable'][$id]['weight'] = array(
      '#type' => 'weight',
      '#title' => t('Weight for @title', array('@title' => "test label")),
      '#title_display' => 'invisible',
      '#default_value' => 1,
      // Classify the weight element for #tabledrag.
      '#attributes' => array('class' => array('mytable-order-weight')),
    );

    // Operations (dropbutton) column.
    $form['mytable'][$id]['operations'] = array(
      '#type' => 'operations',
      '#links' => array(),
    );






    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save changes'),
      // TableSelect: Enable the built-in form validation for #tableselect for
      // this form button, so as to ensure that the bulk operations form cannot
      // be submitted without any selected items.
//      '#tableselect' => TRUE,
    );



//    return $form;

//return $build;
    return parent::buildForm($form, $form_state);


  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
//    print '<pre>'; print_r("config"); print '</pre>';
//    print '<pre>'; print_r("config"); print '</pre>';
//    print '<pre>'; print_r("form state"); print '</pre>';
//    print '<pre>'; print_r($form_state->getValue('crumbs_weights')); print '</pre>';
//    exit;
    $this->config('crumbs.settings')
      ->set('crumbs_weights', $form_state->getValue('crumbs_weights'))
//      ->set('client_secret', $form_state->getValue('client_secret'))
      ->save();
//    // This will only hit the 'cache_page' and 'cache_block' cache bins.
//    cache_clear_all();
//
//    // Clear plugin info in 'cache' cache bin.
//    crumbs()->pluginInfo->flushCaches();
  }
}