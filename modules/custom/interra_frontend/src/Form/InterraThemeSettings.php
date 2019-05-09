<?php

namespace Drupal\interra_frontend\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class InterraThemeSettings extends ConfigFormBase {
    /** @var string Config settings */
  const SETTINGS = 'interra_frontend.theme';
  
  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'interra_theme_settings';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
    $defaultSettings = $config->get('theme');

    $form['theme'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Theme'),
      '#open' => TRUE,
      '#tree' => TRUE
    ];

    foreach($defaultSettings as $setting => $value) {
      $form['theme'][$setting] = [
        '#type' => 'textfield',
        '#title' => $this->t($setting),
        '#default_value' => $value,
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      // Retrieve the configuration
    $values = $form_state->getValue('theme');
    $this->configFactory->getEditable(static::SETTINGS)
    ->set('theme', $values)
    ->save();

    parent::submitForm($form, $form_state);
  }
}
