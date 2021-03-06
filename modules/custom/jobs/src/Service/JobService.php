<?php


namespace Drupal\jobs\Service;


use Drupal;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\node\Entity\Node;

class JobService {

  /**
   * @var LoggerChannelFactory
   */
  private $logger;

  /**
   * @var bool
   */
  private $useLogger;

  public function __construct(LoggerChannelFactory $logger, $useLogger = FALSE) {
    $this->logger = $logger;
    $this->useLogger = $useLogger;
  }


  public function getAllJobs($length = 10) {
    $offers = [];
    $query = Drupal::entityQuery('node')
      ->condition('type', 'job')
      ->sort('created', 'DESC')
      ->pager($length);
    $nids = array_values($query->execute());
    $nodes = Node::loadMultiple($nids);
    foreach ($nodes as $node) {
      $offers[] = [
        'link' => "/offer/{$node->id()}",
        'title' => $node->getTitle(),
        'body' => substr($node->get('body')->value, 0, 600) . '...',
      ];
    }
    return $offers;
  }


  function getLastOffers($length = 3) {
    $offers = [];
    $query = Drupal::entityQuery('node')
      ->condition('type', 'job')
      ->sort('created', 'DESC')
      ->pager($length);
    $nids = array_values($query->execute());
    $nodes = Node::loadMultiple($nids);

    foreach ($nodes as $node) {
      $url = Drupal::service('path.alias_manager')
        ->getAliasByPath("/node/{$node->id()}");

      $offers[] = "<a href='{$url}'>{$node->getTitle()}</a>";
    }

    return implode("<br/>", $offers);
  }

  /**
   * Show the Offer Node by Node_ID
   *
   * @param $id
   * @param string $type
   *
   * @return array|null
   */
  public function getNodeOffer($id, $type = 'job_offer') {
    $node = Node::load($id);
    if (!$node) {
      return NULL;
    }

    $nodeType = $node->type->entity->label();
    $isOfferNode = ($nodeType === $type);

    return (!$isOfferNode) ? NULL :
      [
        'id' => $id,
        'title' => $node->get('title')->value,
        'body' => $node->get('body')->value,
      ];
  }

  /**
   *
   * @param int $length
   *
   * @return mixed
   */
  public function getApplies(int $length) {
    $query = Drupal::database()->select('applies_table', 'a');
    $query->addField('a', 'nid');
    $query->addField('a', 'full_name');
    $query->addField('a', 'email');
    $query->addField('a', 'phone');
    $query->addField('a', 'experiences');
    $query->addField('a', 'resume');
    $query->addField('a', 'cv');
    $query->addField('a', 'title_job');
    $query->addField('a', 'job_id');
    $query->orderBy('nid', 'DESC');

    return $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
  }

}
