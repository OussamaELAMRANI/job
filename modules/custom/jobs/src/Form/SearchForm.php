<?php


namespace Drupal\jobs\Form;


use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class SearchForm extends FormBase
{

  /**
   * @inheritDoc
   */
  public function getFormId()
  {
    return 'search_job';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $form['job_title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Job Title'),
      '#description' => $this->t('Enter a portion of the title job to search for'),
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Search'),
      '#ajax' => array(
        'callback' => array($this, 'searchNow'),
      )
    );
    $form['#title'] = 'Search for a new job ';

    return $form;
  }

  public static function searchNow(array &$form, FormStateInterface $form_state)
  {
    $response = new AjaxResponse();
    $job_title = $form_state->getValue('job_title');

    $query = \Drupal::entityQuery('node')
//      ->condition('type', 'job')
      ->condition('title', $job_title, 'CONTAINS');
    $entity = $query->execute();

//    dump($entity);
//    die();
    $key = array_values($entity);
    $id = !empty($key[0]) ? $key[0] : NULL;

    if ($id !== NULL) {
      $jobs = [];
      $nodes = Node::loadMultiple($key);
      foreach ($nodes as $node) {
        $jobs[] = "<a href='/offer/{$node->id()}'>{$node->get('title')->value}</a>";
      }
      $result = implode("<br/>", $jobs);
      $content =
        "<div class='test-popup-content'>
        {$result}
      </div>";
    } else
      $content = "Not found record with this title <strong>${job_title}</strong>";

    $options = array(
      'dialogClass' => 'popup-dialog-class',
      'width' => '300',
      'height' => '300',
    );
    $response->addCommand(new OpenModalDialogCommand("Search result", $content, $options));

    return $response;
  }


  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->messenger()->addMessage("You find that job successfully !");
  }
}
