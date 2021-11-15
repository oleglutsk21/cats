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

  /**
   * Custom validation form
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $catsName = $form_state->getValue('cats_name');
    if (strlen($catsName) < 2 || strlen($catsName) > 32) {
      $form_state->setErrorByName('cats_name', $this->t('Sorry, the name you entered is not correct, please enter the correct name.'));
    } else {
      \Drupal::messenger()->addMessage($this->t('Your cat\'s name is: ' . $catsName));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}