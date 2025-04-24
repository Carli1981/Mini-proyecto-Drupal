<?php

namespace Drupal\registro_usuarios\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EditarUsuarioForm extends FormBase {

  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  public function getFormId() {
    return 'editar_usuario_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $usuario = $this->database->select('registro_usuarios', 'ru')
      ->fields('ru', ['id', 'nombre', 'correo'])
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();

    if (!$usuario) {
      $this->messenger()->addError($this->t('Usuario no encontrado.'));
      return $form;
    }

    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $usuario['id'],
    ];

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#default_value' => $usuario['nombre'],
      '#required' => TRUE,
    ];

    $form['correo'] = [
      '#type' => 'email',
      '#title' => $this->t('Correo'),
      '#default_value' => $usuario['correo'],
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Guardar cambios'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getValue('id');
    $nombre = $form_state->getValue('nombre');
    $correo = $form_state->getValue('correo');

    $this->database->update('registro_usuarios')
      ->fields([
        'nombre' => $nombre,
        'correo' => $correo,
      ])
      ->condition('id', $id)
      ->execute();

    $this->messenger()->addMessage($this->t('Usuario actualizado correctamente.'));
    $form_state->setRedirect('registro_usuarios.lista');
  }
}
