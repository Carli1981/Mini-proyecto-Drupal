<?php

namespace Drupal\registro_usuarios\Controller;

use Drupal\Core\Controller\ControllerBase;

class RegistroUsuariosController extends ControllerBase {

  public function ListarUsuarios() {
    $contenido = [];


    // Enlace de regreso opcional.
    $contenido[] = [
      '#markup' => '<p><a href="/">Inicio</a></p>',
    ];

    return $contenido;
  }

}