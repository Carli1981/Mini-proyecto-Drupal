<?php

namespace Drupal\registro_usuarios\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\registro_usuarios\Service\RegistroUsuariosService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class ApiUsuariosController extends ControllerBase {

  protected $registroUsuariosService;

  public function __construct(RegistroUsuariosService $registroUsuariosService) {
    $this->registroUsuariosService = $registroUsuariosService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('registro_usuarios.registro_service')
    );
  }

  public function obtenerUsuario($id) {
    $usuario = $this->registroUsuariosService->obtenerUsuarioPorId($id);
    if (!$usuario) {
      return new JsonResponse([
        'message' => 'Usuario no encontrado.',
      ], Response::HTTP_NOT_FOUND);
    }

    return new JsonResponse([
      'id' => $usuario->id,
      'nombre' => $usuario->nombre,
      'correo' => $usuario->correo,
      'creado' => \Drupal::service('date.formatter')->format($usuario->creado, 'custom', 'Y-m-d H:i:s'),
    ]);
  }
}
