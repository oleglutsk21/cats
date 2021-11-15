<?php

namespace Drupal\oleg\Form;

use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;

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

    $form['submit-message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="form__submit-result"></div>',
    ];

    $form['cats_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat\'s name:'),
      '#required' => TRUE,
      '#placeholder' => $this->t('The minimum name length is 2 characters, the maximum is 32 characters'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#ajax' => [
        'callback' => '::ajaxSubmitForm',
        'event' => 'click',
      ]
    ];

    return $form;
  }

  /**
   * Custom validation form
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $catsName = $form_state->getValue('cats_name');
    if (strlen($catsName) < 2 || strlen($catsName) > 32) {
      return '<p class="result-invalid">' . $this->t('Sorry, the name you entered is not correct, please enter the correct name.') . '</p>';
    } else {
      return '<p class="result-valid">' . $this->t('Your cat\'s name is: ' . $catsName) . '</p>';
    }
  }

  public function ajaxSubmitForm(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response ->addCommand(
      new HtmlCommand(
        '.form__submit-result',
        $this->validateForm($form, $form_state)
      )
    );
    \Drupal::messenger()->deleteAll();
    return $response;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}