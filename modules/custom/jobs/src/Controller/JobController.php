<?php


namespace Drupal\jobs\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\jobs\Form\ApplyForm;
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

  public function __construct(JobService $jobService, LoggerChannelFactoryInterface $logger)
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
   *
   * @param $id
   * @return array
   */
  public function getOffer($id)
  {
    $offer = $this->jobService->getNodeOffer($id);
    $build['head'] = [
      '#theme' => 'offer-show',
      '#offer' => ($offer) ?? ['error' => 'This offer doesnt exist !'],
      '#form' => ($offer) ? $this->formBuilder()->getForm(ApplyForm::class, $offer) : NULL
    ];


    return $build;
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
