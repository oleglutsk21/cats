<?php
namespace Drupal\oleg\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\Core\Link;
use Drupal\Core\Url;

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
    return [
      '#theme' => 'cats-list',
      '#form' => $catForm,
      '#row' => $this->showDataList(),
      ];
  }

  public function showDataList() {

    // get data from database
    $query = \Drupal::database()->select('oleg', 'ol');
    $query->fields('ol', ['id', 'cat_name', 'email', 'cat_photo', 'date']);
    $query->orderBy('ol.date', 'DESC');
    $result = $query->execute()->fetchAll();
    $rows = [];

    foreach ($result as $data) {
      $uri = File::load($data->cat_photo)->getFileUri();
      $url = file_create_url($uri);
      $urlDelete = Url::fromRoute('oleg.delete_form', ['id' => $data->id], []);
      $linkDelete = [
        '#type' => 'link',
        '#title' => 'Delete',
        '#url' => $urlDelete,
        '#options' => [
          'attributes' => [
            'class' => ['use-ajax btn btn-danger'],
            'data-dialog-type' => 'modal',
          ],
        ],
      ];
        //Link::fromTextAndUrl('Delete', $urlDelete);

      $cat_photo = [
        '#theme' => 'image',
        '#uri' => $uri,
        '#attributes' => [
          'class' => 'cat-photo',
          'alt' => $data->cat_name . ' photo',
        ]
      ];

      //get data
      $rows[] = [
        'id' => $data->id,
        'cat_name' => $data->cat_name,
        'email' => $data->email,
        'cat_photo' => $cat_photo,
        'date' => $data->date,
        'url' => $url,
        'delete' => $linkDelete,
      ];
    }

    return $rows;
  }
}
