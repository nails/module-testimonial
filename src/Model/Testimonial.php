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
    /**
     * Model constructor
     **/
    public function __construct()
    {
        parent::__construct();
        $this->table             = NAILS_DB_PREFIX . 'testimonial';
        $this->defaultSortColumn = 'quote';
        $this->searchableFields  = ['quote', 'quote_by'];
    }
}
