<?php
namespace Drupal\oleg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Our custom Oleg form class.
 */

class OlegForm extends FormBase {

  /**
   * {@inheritdoc }
   */
  public function getFormId() {
    return 'oleg_form';
  }

  /**
   * {@inheritdoc }
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['cats_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat\'s name:'),
      '#required' => TRUE,
      '#placeholder' => $this->t('The minimum name length is 2 characters, the maximum is 32 characters'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage($this->t("Your cat's name is: " . $form_state->getValue('cats_name')));
  }

}