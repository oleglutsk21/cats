<?php

namespace Drupal\oleg\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;


/**
 * Class DeleteForm
 * @package Drupal\oleg\Form
 */
class DeleteForm extends ConfirmFormBase {

  public $id;
  public $cat_name ;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete_form';
  }

  public function getQuestion() {
    return t('Delete data');
  }

  public function getCancelUrl() {
    return new Url('oleg.cats');
  }

  public function getDescription() {
    return t('Do you want to delete this cat ?');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete it');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = \Drupal::database();
    $query->delete('oleg')
      ->condition('id', $this->id)
      ->execute();
    \Drupal::messenger()->addStatus('Successfully deleted.');
    $form_state->setRedirect('oleg.cats');
  }
}