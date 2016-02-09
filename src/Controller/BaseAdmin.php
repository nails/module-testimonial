<?php

/**
 * This class provides some common Testimonial controller functionality in admin
 *
 * @package     Nails
 * @subpackage  module-redirect
 * @category    Controller
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\Testimonial\Controller;

use Nails\Admin\Controller\Base;

class BaseAdmin extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->asset->load('admin.css', 'nailsapp/module-testimonial');
    }
}
