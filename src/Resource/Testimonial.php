<?php

namespace Nails\Testimonial\Resource;

use Nails\Common\Resource\Entity;
use Nails\Common\Resource\DateTime;

/**
 * Class Testimonial
 *
 * @package Nails\Testimonial\Resource
 */
class Testimonial extends Entity
{
  /** @var string|null */
  public $quote;

  /** @var string|null */
  public $quote_by;

  /** @var DateTime|null */
  public $quote_dated;

  /** @var int|null */
  public $image_id
}
