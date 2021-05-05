<?php

/**
 * Manage testimonials
 *
 * @package     Nails
 * @subpackage  module-testimonial
 * @category    Controller
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\Admin\Testimonial;

use Nails\Admin\Controller\DefaultController;
use Nails\Testimonial\Constants;

class Testimonial extends DefaultController
{
    const CONFIG_MODEL_NAME     = 'Testimonial';
    const CONFIG_MODEL_PROVIDER = Constants::MODULE_SLUG;
    const CONFIG_SORT_OPTIONS   = [
        'Quote Date' => 'quote_dated',
        'Quote By'   => 'quote_by',
        'Quote'      => 'quote',
        'Created'    => 'created',
        'Modified'   => 'modified',
    ];
    const CONFIG_INDEX_FIELDS   = [
        'Quote'       => null,
        'Created'     => 'created',
        'Modified'    => 'modified',
        'Modified By' => 'modified_by',
    ];

    // --------------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();
        $this->aConfig['INDEX_FIELDS']['Quote'] = function (\Nails\Testimonial\Resource\Testimonial $oTestimonial) {
            return sprintf(
                '%s%s<small>%s</small>',
                $oTestimonial->quote_by,
                $oTestimonial->quote_dated ? ' &mdash; ' . $oTestimonial->quote_dated->formatted : '',
                word_limiter(strip_tags($oTestimonial->quote), 50)
            );
        };
    }
}
