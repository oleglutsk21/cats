<?php

namespace Drupal\oleg\Form;

use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\file\Entity\File;

/**
 * Our custom Oleg form class.
 */
class OlegForm extends FormBase {

  protected int $error = 0;

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
      '#type'   => 'markup',
      '#markup' => '<div class="form__submit-result"></div>',
    ];

    $form['cat_name'] = [
      '#type'        => 'textfield',
      '#title'       => $this->t('Your cat\'s name:'),
      '#required'    => TRUE,
      '#placeholder' => $this->t('The minimum name length is 2 characters, the maximum is 32 characters'),
      '#attributes'  => [
        'autocomplete' => 'off',
      ],
    ];

    $form['email'] = [
      '#type'        => 'email',
      '#title'       => $this->t('Your email:'),
      '#required'    => TRUE,
      '#placeholder' => $this->t('Please use only latin, "_" and  "-" characters.'),
      '#ajax'        => [
        'callback' => '::ajaxEmailValidate',
        'event'    => 'keyup',
        'progress' => [
          'type' => 'none',
        ],
      ],
      '#attributes'  => [
        'autocomplete' => 'off',
      ],
    ];

    $form['cat_photo'] = [
      '#type'              => 'managed_file',
      '#title'             => $this->t('Your cat\'s photo:'),
      '#required'          => TRUE,
      '#upload_validators' => [
        'file_validate_extensions' => ['jpeg jpg png'],
        'file_validate_size'       => [2097152],
      ],
      '#upload_location'   => 'public://cats_photo/',
      '#attributes'        => [
        'autocomplete' => 'off',
      ],
    ];

    $form['submit'] = [
      '#type'  => 'submit',
      '#value' => $this->t('Add cat'),
      '#ajax'  => [
        'callback' => '::ajaxSubmitForm',
        'event'    => 'click',
      ],
    ];

    return $form;
  }

  /**
   * Custom validation form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state): string {
    $catsName  = $form_state->getValue('cat_name');
    $userEmail = $form_state->getValue('email');
    if (strlen($catsName) < 2 || strlen($catsName) > 32) {
      $this->error++;
      return '<p class="result-invalid">' . $this->t('Sorry, the name you entered is not correct, please enter the correct name.') . '</p>';
    }
    if (strlen($userEmail) == 0) {
      $this->error++;
      return '<p class="result-invalid">' . $this->t('Please enter your email.') . '</p>';
    }
    if (empty($form_state->getValue('cat_photo'))) {
      $this->error++;
      return '<p class="result-invalid">' . $this->t('Please add photo of your cat.') . '</p>';
    }
    else {
      $this->error = 0;
      return '<p class="result-valid">' . $this->t('Your cat\'s name is: ' . $catsName) . '</p>';
    }
  }

  /**
   * Custom ajax submit form.
   */
  public function ajaxSubmitForm(array $form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand(
        '.form__submit-result',
        $this->validateForm($form, $form_state)
      )
    );
    \Drupal::messenger()->deleteAll();
    return $response;
  }

  /**
   * Custom ajax email validate.
   */
  public function ajaxEmailValidate(array $form, FormStateInterface $form_state): AjaxResponse {
    $userEmail = $form_state->getValue('email');
    $response  = new AjaxResponse();
    if (!preg_match('/^[_A-Za-z0-9-\\+]*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/', $userEmail)) {
      $response->addCommand(
        new HtmlCommand(
          '.form__submit-result',
          '<p class="result-invalid">' . $this->t('Sorry, you email is not correct, please enter the correct email.') . '</p>'
        )
      );
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.form__submit-result',
          ''
        )
      );
    }
    return $response;
  }

  /**
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($this->error === 0) {
      $cat_photo = $form_state->getValue('cat_photo');
      $data      = [
        'cat_name'  => $form_state->getValue('cat_name'),
        'email'     => $form_state->getValue('email'),
        'cat_photo' => $cat_photo[0],
        'date'      => \Drupal::time()->getCurrentTime(),
      ];

      // Save file as Permanent.
      $file = File::load($cat_photo[0]);
      $file->setPermanent();
      $file->save();

      // Insert data to database.
      \Drupal::database()->insert('oleg')->fields($data)->execute();
    }
  }

}
