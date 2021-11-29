<?php

namespace Drupal\oleg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\file\Entity\File;


class AdminForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'admin_cats_list';
  }

  /**
   * Function for creating module.
   */
  public function createDataList():array {

    $dataList = \Drupal::database()
      ->select('oleg', 'cats-list')
      ->fields('cats-list', ['id', 'cat_name', 'email', 'cat_photo', 'date'])
      ->execute()
      ->fetchAll();

    $rows = [];

    foreach ($dataList as $row) {
      $catPhotoPath = File::load($row->cat_photo)->getFileUri();

      $cat_photo = [
        '#theme' => 'image',
        '#uri' => $catPhotoPath,
        '#attributes' => [
          'class' => 'cat-photo',
          'alt' => $row->cat_name . ' photo',
          'width' => 200,
          'height' => 200,
        ],
      ];

      $urlDelete = Url::fromRoute('oleg.delete_form', ['id' => $row->id], []);
      $linkDelete = Link::fromTextAndUrl('Delete', $urlDelete);
      $urlEdit = Url::fromRoute('oleg.edit_form', ['id' => $row->id], []);
      $linkEdit = Link::fromTextAndUrl('Edit', $urlEdit);

      $rows[$row->id] = [
        'cat_name' => $row->cat_name,
        'email' => $row->email,
        'cat_photo' => ['data' => $cat_photo],
        'date' => date('d-m-Y H:i:s', $row->date),
        'delete' => $linkDelete,
        'edit' => $linkEdit,
      ];

    }

    if (!$rows == NULL) {
      krsort($rows);
    }

    return $rows;

  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $header = [
      'cat_name' => $this->t('Cat name'),
      'email' => $this->t('Email'),
      'cat_photo' => $this->t('Cat photo'),
      'date' => $this->t('Date'),
      'delete' => $this->t('Delete'),
      'edit' => $this->t('Edit'),
    ];

    $rows = $this->createDataList();

    $form['table'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $rows,
      '#title' => t('Cats list'),
      '#empty' => t('No records found'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Delete'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $_SESSION['id'] = $form_state->getValue(['table']);
    $form_state->setRedirect('oleg.delete_admin_form');
  }

}