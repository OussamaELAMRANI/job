<?php


namespace Drupal\jobs\Service;


use Drupal;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Psr\Log\LogLevel;

class JobService
{
  /**
   * @var LoggerChannelFactory
   */
  private $logger;
  /**
   * @var bool
   */
  private $useLogger;

  public function __construct(LoggerChannelFactory $logger, $useLogger = false)
  {
    $this->logger = $logger;
    $this->useLogger = $useLogger;
  }


  public function getAllJobs($length = 10)
  {
    $offers = [];
    $query = Drupal::entityQuery('node')
      ->condition('type', 'job')
      ->sort('created', 'DESC')
      ->pager($length);
//    dump($query->execute());
    $nids = array_values($query->execute());
    $nodes = Node::loadMultiple($nids);
    foreach ($nodes as $node) {
      $offers[] = [
        'link' => "/offer/{$node->id()}",
        'title' => $node->getTitle(),
        'body' => substr($node->get('body')->value, 0, 600) . '...',
      ];
    }
//  dump($offers);
//  die();
    return $offers;
  }

  /**
   * Show the Offer Node by Node_ID
   *
   * @param $id
   * @param string $type
   * @return array|null
   */
  public function getNodeOffer($id, $type = 'job_offer')
  {
    $node = Node::load($id);
    if (!$node) return null;

    $nodeType = $node->type->entity->label();
    $isOfferNode = ($nodeType === $type);

    return (!$isOfferNode) ? null :
      [
        'id' => $id,
        'title' => $node->get('title')->value,
        'body' => $node->get('body')->value,
      ];
  }
}
