<?php

namespace Drupal\jobs\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

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
class JobBlock extends BlockBase
{

  /**
   * @inheritDoc
   */
  public function build()
  {
    return [
      '#markup' => getLastOffers(),
    ];
  }
}
