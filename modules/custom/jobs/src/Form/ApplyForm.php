<?php


namespace Drupal\jobs\Form;


use Drupal;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class ApplyForm extends FormBase
{

  /**
   * @inheritDoc
   */
  public function getFormId()
  {
    return 'apply_modal';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state, array $gated_content_context = NULL)
  {
    // Name
    $form['full_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Full name'),
      '#description' => $this->t('Enter your full name'),
    );
    $form['title_job'] = array(
      '#type' => 'hidden',
      '#value' => $gated_content_context['title'],
    );
    $form['job_id'] = array(
      '#type' => 'hidden',
      '#value' => $gated_content_context['id'],
    );
    // Email
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#description' => 'Enter your email address',
    ];
    // Tel.
    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone number'),
      '#description' => $this->t('Enter your phone number, beginning with country code e-g +212 668 238 123'),
    ];
    // Number.
    $form['experiences'] = [
      '#type' => 'number',
      '#title' => t('Years of professional experience '),
    ];
    $form['resume'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Resume'),
      '#description' => $this->t('Enter your experiences above'),
    );
    $form['cv'] = array(
      '#type' => 'managed_file',
      '#upload_location' => 'public://applies/cv',
      '#title' => $this->t('upload your CV'),
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Apply'),
    );
    $form['#title'] = 'Apply for a new job';

    return $form;
  }

  /**
   * @inheritDoc
   * @throws \Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $db = Drupal::database()->insert('applies_table');
    $values = $form_state->getUserInput();
    $cvPath = $this->insertFile($form_state->getValue('cv'));

    $db->fields([
      "full_name" => $values['full_name'],
      "title_job" => $values['title_job'],
      "job_id" => $values['job_id'],
      "email" => $values['email'],
      "phone" => $values['phone'],
      "experiences" => $values['experiences'],
      "resume" => $values['resume'],
      "cv" => $cvPath
    ])->execute();
    $this->messenger()->addMessage('Your apply is added successfully !');
  }

  private function insertFile($file)
  {
    $newFile = Drupal\file\Entity\File::load($file[0]);
    $newFile->setPermanent();
    $newFile->save();
    return $newFile->getFileUri();
  }

}
