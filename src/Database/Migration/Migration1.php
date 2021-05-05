<?php

/**
 * Migration: 1
 * Started:   05/05/2021
 */

namespace Nails\Testimonial\Database\Migration;

use Nails\Common\Console\Migrate\Base;

class Migration1 extends Base
{
    /**
     * Execute the migration
     * @return Void
     */
    public function execute()
    {
        $this->query('ALTER TABLE `{{NAILS_DB_PREFIX}}testimonial` ADD `image_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `quote_dated`;');
        $this->query('ALTER TABLE `{{NAILS_DB_PREFIX}}testimonial` ADD FOREIGN KEY (`image_id`) REFERENCES `{{NAILS_DB_PREFIX}}cdn_object` (`id`) ON DELETE RESTRICT;');
    }
}
