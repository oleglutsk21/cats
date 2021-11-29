<?php

namespace Drupal\oleg\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DeleteForm.
 */
class DeleteAdminForm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId():string {
    return 'delete_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion():object {
    return t('Remove these cats?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl():object {
    return new Url('oleg.admin_cats');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription():object {

    if ($_SESSION['id'] != NULL) {
      $descript = t('Are you sure?');
    }
    else {
      $descript = t('Nothing to delete');
    }

    return $descript;
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText():object {
    return t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state):array {
    return parent::buildForm($form, $form_state);
  }

  /**
   * Function delete record and change file status.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $result = $_SESSION['id'];

    $database = \Drupal::database();

    foreach ($result as $data) {
      $database
        ->delete('oleg')
        ->condition('id', $data)
        ->execute();
    }
    if ($_SESSION['id'] != NULL) {
      \Drupal::messenger()->addStatus('You successfully deleted records');
    }
    $url = new Url('oleg.admin_cats');
    $response = new RedirectResponse($url->toString());
    $response->send();
  }

}