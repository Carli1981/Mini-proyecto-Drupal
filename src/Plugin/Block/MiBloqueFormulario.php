<?php

namespace Drupal\registro_usuarios\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Define un bloque con un formulario de registro de usuarios.
 *
 * @Block(
 *   id = "registro_usuarios_bloque_formulario",
 *   admin_label = @Translation("Bloque de Registro de Usuarios"),
 * )
 */
class MiBloqueFormulario extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\registro_usuarios\Form\RegistroUsuariosFormulario');
  }
}