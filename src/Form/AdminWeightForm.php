<?php

namespace Drupal\crumbs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AdminWeightForm extends ConfigFormBase {

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

    $form = array();

    $info = crumbs()->pluginInfo;
//    kint("info");
//    kint($info);
//    kint("user weight");
//    kint($info->userWeights);

    // Re-discover plugins, when the admin visits the weights configuration form.
    $info->flushCaches();

    $form['crumbs_weights'] = array(
      '#title' => t('Weights'),
      // Fetching the default value is not automated by system_settings_form().
      '#default_value' => $info->userWeights,
      '#crumbs_plugin_info' => $info,
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
    return parent::buildForm($form, $form_state);


  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
//    // This will only hit the 'cache_page' and 'cache_block' cache bins.
//    cache_clear_all();
//
//    // Clear plugin info in 'cache' cache bin.
//    crumbs()->pluginInfo->flushCaches();
  }
}