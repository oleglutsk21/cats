<?php

namespace Drupal\oleg\Form;

use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\file\Entity\File;

/**
 * Our custom Oleg form class.
 */

class OlegForm extends FormBase {

  /**
   * {@inheritdoc }
   */
  public function getFormId(): string {
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
      '#attributes' => [
        'autocomplete' => 'off',
      ],
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#required' => TRUE,
      '#placeholder' => $this->t('Please use only latin, "_" and  "-" characters.'),
      '#ajax' => [
        'callback' => '::ajaxEmailValidate',
        'event' => 'keyup',
        'progress' => [
          'type' => 'none'
        ]
      ],
      '#attributes' => [
        'autocomplete' => 'off',
      ],
    ];

    $form['cats_photo'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Your cat\'s photo:'),
      '#required' => TRUE,
      '#upload_validators' => [
        'file_validate_extensions' => ['jpeg jpg png'],
        'file_validate_size' => [2097152]
      ],
      '#upload_location' => 'public://cats_photo/',
      '#attributes' => [
        'autocomplete' => 'off',
      ],
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
    $userEmail = $form_state->getValue('email');
    if (strlen($catsName) < 2 || strlen($catsName) > 32) {
      return '<p class="result-invalid">' . $this->t('Sorry, the name you entered is not correct, please enter the correct name.') . '</p>';
    }
    if (strlen($userEmail) == 0) {
      return '<p class="result-invalid">' . $this->t('Please enter your email.') . '</p>';
    }
    else {
      return '<p class="result-valid">' . $this->t('Your cat\'s name is: ' . $catsName) . '</p>';
    }
  }

  public function ajaxSubmitForm(array $form, FormStateInterface $form_state) {
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

  public function ajaxEmailValidate (array $form, FormStateInterface $form_state) {
    $userEmail = $form_state->getValue('email');
    $response = new AjaxResponse();
    if(!preg_match('/^[_A-Za-z0-9-\\+]*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/', $userEmail)) {
      $response->addCommand(
        new HtmlCommand(
          '.form__submit-result',
          '<p class="result-invalid">' . $this->t('Sorry, you email is not correct, please enter the correct email.') . '</p>'
        )
      );
    } else {
      $response->addCommand(
        new HtmlCommand(
          '.form__submit-result',
          ''
        )
      );
    }
    return $response;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $cat_photo = $form_state->getValue('cats_photo');
    //date_default_timezone_set('UTC');
    $data = [
      'cat_name' => $form_state->getValue('cats_name'),
      'email' => $form_state->getValue('email'),
      'cat_photo' => $cat_photo[0],
      'date' => \Drupal::time()->getCurrentTime(),
    ];

    // save file as Permanent
    $file = File::load($cat_photo[0]);
    $file->setPermanent();
    $file->save();

    // insert data to database
    \Drupal::database()->insert('oleg')->fields($data)->execute();
  }

}