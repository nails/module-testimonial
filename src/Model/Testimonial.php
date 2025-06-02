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
    const FIELD_CLASSES       = [
        'body'     => 'ModelFieldWysiwygBasic',
        'image_id' => ['ModleFieldObject', \Nails\Cdn\Constants::MODULE_SLUG],
    ];

    // --------------------------------------------------------------------------

    /**
     * Returns the searchable columns for this module
     *
     * @return string[]
     */
    public function getSearchableColumns(): array
    {
        return [
            'quote',
            'quote_by',
        ];
    }

    // --------------------------------------------------------------------------

    /**
     * @inheritDoc
     */
    public function describeFields($sTable = null)
    {
        $aFields = parent::describeFields($sTable);

        $aFields['quote']
            ->setIsRequired(true);

        $aFields['image_id']
            ->setLabel('Image');

        return $aFields;
    }
}
