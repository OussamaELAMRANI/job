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
//    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $form['job_title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Job title'),
      '#description' => $this->t('Enter a job title to apply for'),
    );
    $form['job_description'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Resume'),
      '#description' => $this->t('Enter your experiences above'),
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
