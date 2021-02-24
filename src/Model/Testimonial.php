<?php

/**
 * Testimonial model
 *
 * @package     Nails
 * @subpackage  module-testimonial
 * @category    Model
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\Testimonial\Model;

use Nails\Common\Model\Base;

class Testimonial extends Base
{
    const TABLE               = NAILS_DB_PREFIX . 'testimonial';
    const DEFAULT_SORT_COLUMN = 'quote';

    // --------------------------------------------------------------------------

    /**
     * Model constructor
     **/
    public function __construct()
    {
        parent::__construct();
        $this->searchableFields  = ['quote', 'quote_by'];
    }
}
