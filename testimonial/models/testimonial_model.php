<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Name:			testimonial_model.php
 *
 * Description:		This model handles everything to do with Testimonials
 *
 **/

/**
 * OVERLOADING NAILS' MODELS
 *
 * Note the name of this class; done like this to allow apps to extend this class.
 * Read full explanation at the bottom of this file.
 *
 **/

class NAILS_Testimonial_model extends NAILS_Model
{
	/**
	 * Model constructor
	 *
	 * @access public
	 * @param none
	 * @return void
	 **/
	public function __construct()
	{
		parent::__construct();

		// --------------------------------------------------------------------------

		$this->_table = NAILS_DB_PREFIX . 'testimonial';
	}


	// --------------------------------------------------------------------------


	protected function _getcount_common( $search = NULL )
	{
		$this->db->order_by( 'order' );
	}
}


// --------------------------------------------------------------------------


/**
 * OVERLOADING NAILS' MODELS
 *
 * The following block of code makes it simple to extend one of the core Nails
 * models. Some might argue it's a little hacky but it's a simple 'fix'
 * which negates the need to massively extend the CodeIgniter Loader class
 * even further (in all honesty I just can't face understanding the whole
 * Loader class well enough to change it 'properly').
 *
 * Here's how it works:
 *
 * CodeIgniter instantiate a class with the same name as the file, therefore
 * when we try to extend the parent class we get 'cannot redeclare class X' errors
 * and if we call our overloading class something else it will never get instantiated.
 *
 * We solve this by prefixing the main class with NAILS_ and then conditionally
 * declaring this helper class below; the helper gets instantiated et voila.
 *
 * If/when we want to extend the main class we simply define NAILS_ALLOW_EXTENSION
 * before including this PHP file and extend as normal (i.e in the same way as below);
 * the helper won't be declared so we can declare our own one, app specific.
 *
 **/

if ( ! defined( 'NAILS_ALLOW_EXTENSION_TESTIMONIAL_MODEL' ) ) :

	class Testimonial_model extends NAILS_Testimonial_model
	{
	}

endif;

/* End of file testimonial_model.php */
/* Location: ./modules/testimonials/models/testimonial_model.php */