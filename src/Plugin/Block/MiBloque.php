<?php

namespace Drupal\registro_usuarios\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\registro_usuarios\Service\RegistroUsuariosService;
use Drupal\Core\DependencyInjection\ContainerInterface;

/**
 * Define un bloque para mostrar los usuarios registrados.
 *
 * @Block(
 *   id = "registro_usuarios_bloque",
 *   admin_label = @Translation("Bloque de Usuarios Registrados"),
 * )
 */
class MiBloque extends BlockBase {

  /**
   * El servicio de registro de usuarios.
   *
   * @var \Drupal\registro_usuarios\Service\RegistroUsuariosService
   */
  protected $registroUsuariosService;

  /**
   * Construye el bloque.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RegistroUsuariosService $registroUsuariosService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->registroUsuariosService = $registroUsuariosService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('registro_usuarios.registro_service') // Inyectamos el servicio desde el contenedor
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $usuarios = $this->registroUsuariosService->obtenerUsuarios();
    
    $rows = [];
    foreach ($usuarios as $usuario) {
      $rows[] = [
        'data' => [$usuario->nombre, $usuario->correo],
      ];
    }

    return [
      '#type' => 'table',
      '#header' => ['Nombre', 'Correo'],
      '#rows' => $rows,
    ];
  }
}
