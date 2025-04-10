<?php

namespace Drupal\registro_usuarios\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class RegistroUsuariosFormulario extends FormBase {

  public function getFormId() {
    return 'registro_usuarios_formulario';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => TRUE,
    ];

    $form['correo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Correo electrónico'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Registrar'),
    ];

    $form['#attached']['library'][] = 'registro_usuarios/estilos_personalizados';


    // Mostrar solo el último usuario registrado
    $ultimo_usuario = \Drupal::state()->get('registro_usuarios.ultimo_usuario');

    if (!empty($ultimo_usuario)) {
      $form['usuario_registrado'] = [
        '#markup' => '<div class="usuario-registrado"><strong>Último usuario registrado:</strong><br>Nombre: ' . $ultimo_usuario['nombre'] . '<br>Correo: ' . $ultimo_usuario['correo'] . '</div>',
      ];
    }

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $nombre = trim($form_state->getValue('nombre'));
    $correo = trim($form_state->getValue('correo'));
    $connection = Database::getConnection();

    if (empty($nombre)) {
      $form_state->setErrorByName('nombre', $this->t('Introduzca un nombre.'));
    }

    $nombre_existente = $connection->select('registro_usuarios', 'ru')
      ->fields('ru', ['nombre'])
      ->condition('nombre', $nombre)
      ->execute()
      ->fetchField();

    if ($nombre_existente) {
      $form_state->setErrorByName('nombre', $this->t('Este nombre ya está registrado.'));
    }

    if (!\Drupal::service('email.validator')->isValid($correo)) {
      $form_state->setErrorByName('correo', $this->t('El correo electrónico no es válido.'));
    }

    $correo_existente = $connection->select('registro_usuarios', 'ru')
      ->fields('ru', ['correo'])
      ->condition('correo', $correo)
      ->execute()
      ->fetchField();

    if ($correo_existente) {
      $form_state->setErrorByName('correo', $this->t('Este correo ya está registrado.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nombre = trim($form_state->getValue('nombre'));
    $correo = trim($form_state->getValue('correo'));

    $connection = Database::getConnection();
    $connection->insert('registro_usuarios')
      ->fields([
        'nombre' => $nombre,
        'correo' => $correo,
        'creado' => time(),
      ])
      ->execute();

    // Guardar en el estado de Drupal el último usuario registrado
    \Drupal::state()->set('registro_usuarios.ultimo_usuario', [
      'nombre' => $nombre,
      'correo' => $correo,
    ]);

    \Drupal::messenger()->addMessage($this->t('El usuario @nombre ha sido registrado correctamente.', ['@nombre' => $nombre]));

    \Drupal::logger('registro_usuarios')->notice('Usuario registrado: @nombre - @correo', [
      '@nombre' => $nombre,
      '@correo' => $correo,
    ]);

    $form_state->setRebuild(TRUE);
  }
}