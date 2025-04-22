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

  public function obtenerUltimosUsuarios($cantidad = 5) {
    $connection = Database::getConnection();
    $query = $connection->select('registro_usuarios', 'ru')
      ->fields('ru', ['nombre', 'creado'])
      ->orderBy('creado', 'DESC')
      ->range(0, $cantidad)
      ->execute();
  
    return $query->fetchAll();
  }
 
  public function obtenerUsuarioPorId($id) {
    $connection = Database::getConnection();
    $query = $connection->select('registro_usuarios', 'ru')
      ->fields('ru', ['id', 'nombre', 'correo', 'creado'])
      ->condition('id', $id)
      ->execute();
  
    return $query->fetchObject(); // Devuelve FALSE si no encuentra
  }
  
}