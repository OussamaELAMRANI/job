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
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    // Name
    $form['full_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Full name'),
      '#description' => $this->t('Enter your full name'),
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
    $form['experiences_number'] = [
      '#type' => 'number',
      '#title' => t('Years of professional experience '),
    ];
    $form['resume'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Resume'),
      '#description' => $this->t('Enter your experiences above'),
    );
    $form['cv'] = array(
      '#type' => 'file',
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
   * @throws EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $job_title = $form_state->getValue('job_title');
    $job_desc = $form_state->getValue('job_description');
    $message = $this->createNode($job_title, $job_desc);

    $this->messenger()->addStatus($message);
  }

  /**
   * @param string $title
   * @param string $data
   * @return Drupal\Core\StringTranslation\TranslatableMarkup
   * @throws EntityStorageException
   */
  public function createNode($title, $data)
  {
    $node = Node::create([
      'type' => 'apply',
      'langcode' => 'en',
      'created' => REQUEST_TIME,
      'changed' => REQUEST_TIME,
// The user ID.
      'uid' => 1,
      'title' => $title,
// An array with taxonomy terms.
      'field_tags' => [1],
      'body' => [
        'summary' => '',
        'value' => $data,
        'format' => 'full_html',
      ],
    ]);
    $node->save();
    Drupal::service('path.alias_storage')->save("/node/" . $node->id(), "/applies/{$node->id()}", 'en');
    return t("You apply is taken as <strong>  {$node->getTitle()} </strong> en path <a href='/applies/{$node->id()}'>Consult your apply</a>  ");
  }


}
