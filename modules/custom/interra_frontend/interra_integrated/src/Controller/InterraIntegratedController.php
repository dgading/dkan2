<?php

namespace Drupal\interra_integrated\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\HttpResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\interra_frontend\InterraPage;
use Drupal\rest\ResourceResponse;
use Drupal\views\Views;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
* An ample controller.
*/
class InterraIntegratedController extends ControllerBase {

  public function getMenu($entity) {
    $menu_name = $entity;
    $menu_parameters = \Drupal::menuTree()->getCurrentRouteMenuTreeParameters($menu_name);
    $tree = \Drupal::menuTree()->load($menu_name, $menu_parameters);
    $result = array();
  
    foreach ($tree as $element) {
      $link = $element->link;
      array_push($result, array(
          'title' => $link->getTitle(),
          'url' => $link->getUrlObject()->getInternalPath(),
          'weight' => $link->getWeight()
        )
      );
    }
    return $result;
  }

  public function getView($viewId, $displayId, array $arguments) {
    $result = false;
    $view = Views::getView($viewId);

    if (is_object($view)) {
        $view->setDisplay($displayId);
        $view->setArguments($arguments);
        $view->execute();

        // Render the view
        $result = \Drupal::service('renderer')->render($view->render());
    }

    return json_decode($result);
  }

  public function getConfig() {
    $config = \Drupal::config('interra_frontend.theme')->get();
    return $config;
  }


  public function default_page() {
    return [
      '#theme' => 'interra_integrated',
      '#attached' => [
          'library' => [
              'interra_integrated/interra'
          ],
          'drupalSettings' => [
              'interraProps' => [
                'menu' => $this->getMenu('main'),
                'test_view' => $this->getView('views_test', 'rest_export_1', []),
                'config' => $this->getConfig()
              ]
          ]
      ]
    ];
  }

}



