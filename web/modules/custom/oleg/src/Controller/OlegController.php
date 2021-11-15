<?php
namespace Drupal\oleg\Controller;

use Drupal\Core\Controller\ControllerBase;

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
  public function catsPage() {
    return \Drupal::formBuilder()->getForm('\Drupal\oleg\Form\OlegForm');
  }
};
