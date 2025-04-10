<?php

namespace Drupal\registro_usuarios\Service;

use Drupal\Core\Database\Database;

class RegistroUsuariosService {

  /**
   * Obtiene todos los usuarios registrados desde la base de datos.
   */
  public function obtenerUsuarios() {
    $connection = Database::getConnection();
    $query = $connection->select('registro_usuarios', 'ru')
                        ->fields('ru', ['nombre', 'correo'])
                        ->execute();

    return $query->fetchAll();
  }
}