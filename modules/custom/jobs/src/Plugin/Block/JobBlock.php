<?php

namespace Drupal\jobs\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\jobs\Service\JobService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a user details block.
 *
 * @Block(
 * id = "Job_block",
 *
 * category="Job Offer Block",
 * admin_label = "JOB OFFER!"
 * )
 */
class JobBlock extends BlockBase implements ContainerFactoryPluginInterface {


  /**
   * @var \Drupal\jobs\Service\JobService
   */
  private $service;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, JobService $service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->service = $service;
  }

  /**
   * @inheritDoc
   */
  public function build() {
    return [
      '#markup' => $this->service->getLastOffers(3),
    ];
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $jobService = $container->get('job_offer.work_trace');

    // create new instance of  {OfferController}
    // And passing {OfferJobService} as Params
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $jobService
    );
  }

}
