<?php

namespace Drupal\registro_usuarios\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\registro_usuarios\Service\RegistroUsuariosService;

/**
 * Proporciona un bloque con los últimos usuarios registrados.
 *
 * @Block(
 *   id = "registro_usuarios_ultimos_bloque",
 *   admin_label = @Translation("Últimos usuarios registrados"),
 * )
 */
class UltimosUsuariosBloque extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Servicio de registro de usuarios.
   *
   * @var \Drupal\registro_usuarios\Service\RegistroUsuariosService
   */
  protected $registroUsuariosService;

  /**
   * Constructor del bloque.
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
      $container->get('registro_usuarios.registro_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $usuarios = $this->registroUsuariosService->obtenerUltimosUsuarios(5);

    $items = [];
    foreach ($usuarios as $usuario) {
      $items[] = [
        '#markup' => $usuario->nombre . ' - ' . \Drupal::service('date.formatter')->format($usuario->creado, 'short'),
      ];
    }

    return [
      '#theme' => 'item_list',
      '#title' => $this->t('Últimos usuarios registrados'),
      '#items' => $items,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    // Desactiva completamente la caché del bloque para mostrar siempre datos frescos
    return 0;
  }

}
