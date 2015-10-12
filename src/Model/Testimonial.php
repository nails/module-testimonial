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
        $this->table = NAILS_DB_PREFIX . 'testimonial';
        $this->tablePrefix = 't';
    }

    // --------------------------------------------------------------------------

    /**
     * This method applies the conditionals which are common across the get_*()
     * methods and the count() method.
     * @param array  $data    Data passed from the calling method
     * @param string $_caller The name of the calling method
     * @return void
     **/
    protected function _getcount_common($data = array(), $_caller = null)
    {
        if (!empty($data['keywords'])) {

            if (empty($data['or_like'])) {

                $data['or_like'] = array();
            }

            $data['or_like'][] = array(
                'column' => 't.quote',
                'value'  => $data['keywords']
            );
            $data['or_like'][] = array(
                'column' => 't.quote_by',
                'value'  => $data['keywords']
            );
        }

        parent::_getcount_common($data, $_caller);
    }
}
