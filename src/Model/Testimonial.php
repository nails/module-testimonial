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

use Nails\Common;
use Nails\Common\Model\Base;
use Nails\Cdn;
use Nails\Testimonial\Constants;

/**
 * Class Testimonial
 *
 * @package Nails\Testimonial\Model
 */
class Testimonial extends Base
{
    const TABLE               = NAILS_DB_PREFIX . 'testimonial';
    const RESOURCE_NAME       = 'Testimonial';
    const RESOURCE_PROVIDER   = Constants::MODULE_SLUG;
    const DEFAULT_SORT_COLUMN = 'quote';

    // --------------------------------------------------------------------------

    /**
     * Model constructor
     **/
    public function __construct()
    {
        parent::__construct();
        $this->searchableFields = ['quote', 'quote_by'];
    }

    // --------------------------------------------------------------------------

    /**
     * @inheritDoc
     */
    public function describeFields($sTable = null)
    {
        $aFields = parent::describeFields($sTable);

        $aFields['quote']->type         = Common\Helper\Form::FIELD_WYSIWYG_BASIC;
        $aFields['quote']->required     = true;
        $aFields['quote']->validation[] = Common\Service\FormValidation::RULE_REQUIRED;

        $aFields['image_id']->label = 'Image';
        $aFields['image_id']->type  = Cdn\Helper\Form::FIELD_OBJECT_PICKER;

        return $aFields;
    }
}
