<?php

namespace Drupal\registro_usuarios\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\registro_usuarios\Service\RegistroUsuariosService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controlador para la visualización de los usuarios registrados.
 */
class RegistroUsuariosController extends ControllerBase {

  /**
   * El servicio de registro de usuarios.
   *
   * @var \Drupal\registro_usuarios\Service\RegistroUsuariosService
   */
  protected $registroUsuariosService;

  /**
   * Constructor.
   */
  public function __construct(RegistroUsuariosService $registroUsuariosService) {
    $this->registroUsuariosService = $registroUsuariosService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('registro_usuarios.registro_service')
    );
  }

  /**
   * Página que lista todos los usuarios registrados.
   */
  public function listarUsuarios() {
    $usuarios = $this->registroUsuariosService->obtenerUsuarios();
    
    $rows = [];
    foreach ($usuarios as $usuario) {
      $rows[] = [
        'data' => [$usuario->nombre, $usuario->correo],
      ];
    }

    return [
      '#theme' => 'table',
      '#header' => ['Nombre', 'Correo'],
      '#rows' => $rows,
    ];
  }
}