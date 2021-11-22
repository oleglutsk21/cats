<?php
namespace Drupal\oleg\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;

/**
 * Provides route responses for the Example module.
 */
class OlegController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function catsPage(): array {
    $catForm = \Drupal::formBuilder()->getForm('\Drupal\oleg\Form\OlegForm');
    $dataList = $this->showDataList();
    return [$catForm, $dataList] ;
  }

  public function showDataList() {

    // get data from database
    $query = \Drupal::database()->select('oleg', 'ol');
    $query->fields('ol', ['cat_name', 'email', 'cat_photo', 'date']);
    $query->orderBy('ol.date', 'DESC');
    $result = $query->execute()->fetchAll();
    $rows = [];

    foreach ($result as $data) {
      $cat_photo = [
        '#theme' => 'image',
        '#uri' => File::load($data->cat_photo)->getFileUri(),
        '#attributes' => [
          'class' => 'cat-picture',
          'alt' => $data->cat_name . ' photo',
          'width' => 200,
        ]
      ];

      //get data
      $rows[] = [
        'cat_name' => $data->cat_name,
        'email' => $data->email,
        'cat_photo' => $cat_photo,
        'date' => $data->date,
      ];
    }
    // render table
    $createList['table'] = [
      '#type' => 'table',
      '#rows' => $rows,
      '#empty' => t('No data found')
    ];
    return $createList;
  }
}
