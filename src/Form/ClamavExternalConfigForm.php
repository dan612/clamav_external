<?php

namespace Drupal\clamav_external\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Clamav External Config Form.
 */
class ClamavExternalConfigForm extends ConfigFormBase {

  const SETTINGS = 'clamav_external.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'access_control_settings';
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

    $form['external_scanner_username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('External Scanner Username'),
      '#description' => $this->t('Basic auth username'),
      '#default_value' => $config->get('external_scanner_username'),
    ];

    $form['external_scanner_pw'] = [
      '#type' => 'password',
      '#title' => $this->t('External Scanner Password'),
      '#description' => $this->t('Basic auth password.'),
      '#default_value' => $config->get('external_scanner_pw'),
    ];

    $form['external_scanner_endpoint'] = [
      '#type' => 'url',
      '#title' => $this->t('External Scanner Endpoint'),
      '#description' => $this->t('The URL endpoint to send files for scanning'),
      '#default_value' => $config->get('external_scanner_endpoint'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('external_scanner_username', $form_state->getValue('external_scanner_username'))
      ->set('external_scanner_pw', $form_state->getValue('external_scanner_pw'))
      ->set('external_scanner_endpoint', $form_state->getValue('external_scanner_endpoint'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
