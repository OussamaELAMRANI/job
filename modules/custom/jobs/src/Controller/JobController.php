<?php


namespace Drupal\jobs\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\jobs\Service\JobService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JobController extends ControllerBase
{
  /**
   * @var JobService
   */
  private $jobService;
  /**
   * @var LoggerChannelFactory
   */
  private $logger;

  public function __construct(JobService $jobService, LoggerChannelFactory $logger)
  {
    $this->jobService = $jobService;
    $this->logger = $logger;
  }

  public function index()
  {
    return [
      '#theme' => 'offers-view',
      '#offers' => $this->jobService->getAllJobs(),
    ];
  }

  /**
   * @override
   *
   * @param ContainerInterface $container
   * @return ControllerBase|static
   */
  public static function create(ContainerInterface $container)
  {
    $jobService = $container->get('job_offer.work_trace');
    $logFactoryService = $container->get('logger.factory');

    // create new instance of  {OfferController}
    // And passing {OfferJobService} as Params
    return new static($jobService, $logFactoryService);
  }
}
