<?php


namespace Drupal\jobs\Plugin\Block;

use Drupal;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\jobs\Form\SearchForm;

/**
 * Provides a user details block.
 *
 * @Block(
 * id = "search_job_block",
 * category="Job Offer Block",
 * admin_label = "Search Offers !"
 * )
 */
class SearchJobBlock extends BlockBase

{
  /**
   * @inheritDoc
   */
  public function build()
  {
    return Drupal::formBuilder()->getForm(SearchForm::class);
  }
}
