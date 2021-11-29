<?php

namespace Drupal\oleg\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Form for edditing cats.
 */
class EditForm extends OlegForm {

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'oleg_EditForm';
  }

  protected $catId;

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $catId = NULL): array {
    $this->catId = \Drupal::routeMatch()->getParameter('id');
    $conn = Database::getConnection();
    $data = [];
    if (isset($this->catId)) {
      $query = $conn->select('oleg', 'ol')
        ->condition('id', $this->catId)
        ->fields('ol');
      $data = $query->execute()->fetchAssoc();
    }
    $form = parent::buildForm($form, $form_state);
    $form['cat_name']['#default_value'] = (isset($data['cat_name'])) ? $data['cat_name'] : '';
    $form['email']['#default_value'] = (isset($data['email'])) ? $data['email'] : '';
    $form['cat_photo']['#default_value'][] = (isset($data['cat_photo'])) ? $data['cat_photo'] : '';
    $form['submit']['#value'] = $this->t('Edit');
    $form['submit']['#ajax'] = NULL;
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $cat_photo = $form_state->getValue('cat_photo');
    $data = array(
      'cat_name' => $form_state->getValue('cat_name'),
      'email' => $form_state->getValue('email'),
      'cat_photo' => $cat_photo[0],
    );

    // Save file as Permanent.
    $file = File::load($cat_photo[0]);
    $file->setPermanent();
    $file->save();

    if (isset($this->catId) && ($this->error === 0)) {
      // Update data in database.
      \Drupal::database()->update('oleg')->fields([
        'cat_name' => $form_state->getValue('cat_name'),
        'email' => $form_state->getValue('email'),
        'cat_photo' => $cat_photo[0],
      ])->condition('id', $this->catId)->execute();
      \Drupal::messenger()->addStatus($this->t('Successfully saved'));
      $url = new Url('oleg.cats');
      $response = new RedirectResponse($url->toString());
      $response->send();
    }
    elseif (strlen($form_state->getValue('cat_name')) < 2 || strlen($form_state->getValue('cat_name')) > 32) {
      \Drupal::messenger()->addError($this->t('Sorry, the name you entered is not correct, please enter the correct name.'));
    }

  }

}
