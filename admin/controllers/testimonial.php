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

use Nails\Factory;
use Nails\Admin\Helper;
use Nails\Admin\Controller\Base;

class Testimonial extends Base
{
    /**
     * Announces this controller's navGroups
     * @return stdClass
     */
    public static function announce()
    {
        if (userHasPermission('admin:testimonial:testimonial:manage')) {

            $navGroup = Factory::factory('Nav', 'nailsapp/module-admin');
            $navGroup->setLabel('Testimonials');
            $navGroup->setIcon('fa-comments');
            $navGroup->addAction('Manage Testimonials');

            return $navGroup;
        }
    }

    // --------------------------------------------------------------------------

    /**
     * Returns an array of extra permissions for this controller
     * @return array
     */
    public static function permissions()
    {
        $permissions = parent::permissions();

        $permissions['manage'] = 'Can manage testimonials';
        $permissions['create'] = 'Can create testimonials';
        $permissions['edit']   = 'Can edit testimonials';
        $permissions['delete'] = 'Can delete testimonials';

        return $permissions;
    }

    // --------------------------------------------------------------------------

    /**
     * Constructs the controller
     */
    public function __construct()
    {
        parent::__construct();

        // --------------------------------------------------------------------------

        $this->lang->load('admin_testimonials');
    }

    // --------------------------------------------------------------------------

    /**
     * Browse testimonials
     * @return void
     */
    public function index()
    {
        if (!userHasPermission('admin:testimonial:testimonial:manage')) {

            unauthorised();
        }

        // --------------------------------------------------------------------------

        $oTestimonialModel = Factory::model('Testimonial', 'nailsapp/module-testimonial');

        // --------------------------------------------------------------------------

        //  Set method info
        $this->data['page']->title = lang('testimonials_index_title');

        // --------------------------------------------------------------------------

        //  Get pagination and search/sort variables
        $page      = $this->input->get('page')      ? $this->input->get('page')      : 0;
        $perPage   = $this->input->get('perPage')   ? $this->input->get('perPage')   : 50;
        $sortOn    = $this->input->get('sortOn')    ? $this->input->get('sortOn')    : 't.created';
        $sortOrder = $this->input->get('sortOrder') ? $this->input->get('sortOrder') : 'desc';
        $keywords  = $this->input->get('keywords')  ? $this->input->get('keywords')  : '';

        // --------------------------------------------------------------------------

        //  Define the sortable columns
        $sortColumns = array(
            't.created'  => 'Created Date',
            't.modified' => 'Modified Date',
            't.quote_by' => 'Quotee'
        );

        // --------------------------------------------------------------------------

        //  Define the $data variable for the queries
        $data = array(
            'sort' => array(
                array($sortOn, $sortOrder)
            ),
            'keywords' => $keywords
        );

        //  Get the items for the page
        $totalRows                  = $oTestimonialModel->count_all($data);
        $this->data['testimonials'] = $oTestimonialModel->get_all($page, $perPage, $data);

        //  Set Search and Pagination objects for the view
        $this->data['search']     = Helper::searchObject(true, $sortColumns, $sortOn, $sortOrder, $perPage, $keywords);
        $this->data['pagination'] = Helper::paginationObject($page, $perPage, $totalRows);

        //  Add a header button
        if (userHasPermission('admin:testimonial:testimonial:create')) {

             Helper::addHeaderButton(
                'admin/testimonial/testimonial/create',
                lang('testimonials_nav_create')
            );
        }

        // --------------------------------------------------------------------------

        Helper::loadView('index');
    }

    // --------------------------------------------------------------------------

    /**
     * Create a new testimonial
     * @return void
     */
    public function create()
    {
        if (!userHasPermission('admin:testimonial:testimonial:create')) {

            unauthorised();
        }

        // --------------------------------------------------------------------------

        //  Page Title
        $this->data['page']->title = lang('testimonials_create_title');

        // --------------------------------------------------------------------------

        if ($this->input->post()) {

            $oFormValidation = Factory::service('FormValidation');
            $oFormValidation->set_rules('quote', '', 'xss_clean|required');
            $oFormValidation->set_rules('quote_by', '', 'xss_clean|required');
            $oFormValidation->set_rules('quote_dated', '', 'xss_clean|required');

            $oFormValidation->set_message('required', lang('fv_required'));

            if ($oFormValidation->run()) {

                $data                = array();
                $data['quote']       = $this->input->post('quote');
                $data['quote_by']    = $this->input->post('quote_by');
                $data['quote_dated'] = $this->input->post('quote_dated');

                $oTestimonialModel = Factory::model('Testimonial', 'nailsapp/module-testimonial');

                if ($oTestimonialModel->create($data)) {

                    $this->session->set_flashdata('success', lang('testimonials_create_ok'));
                    redirect('admin/testimonial/testimonial/index');

                } else {

                    $this->data['error'] = lang('testimonials_create_fail');
                }

            } else {

                $this->data['error'] = lang('fv_there_were_errors');
            }
        }

        // --------------------------------------------------------------------------

        //  Load views
        Helper::loadView('edit');
    }

    // --------------------------------------------------------------------------

    /**
     * Edit a testimonial
     * @return void
     */
    public function edit()
    {
        if (!userHasPermission('admin:testimonial:testimonial:edit')) {

            unauthorised();
        }

        // --------------------------------------------------------------------------

        $oTestimonialModel = Factory::model('Testimonial', 'nailsapp/module-testimonial');

        $this->data['testimonial'] = $oTestimonialModel->get_by_id($this->uri->segment(5));

        if (!$this->data['testimonial']) {

            $this->session->set_flashdata('error', lang('testimonials_common_bad_id'));
            redirect('admin/testimonial/testimonial/index');
        }

        // --------------------------------------------------------------------------

        //  Page Title
        $this->data['page']->title = lang('testimonials_edit_title');

        // --------------------------------------------------------------------------

        if ($this->input->post()) {

            $oFormValidation = Factory::service('FormValidation');
            $oFormValidation->set_rules('quote', '', 'xss_clean|required');
            $oFormValidation->set_rules('quote_by', '', 'xss_clean|required');
            $oFormValidation->set_rules('quote_dated', '', 'xss_clean|required');

            $oFormValidation->set_message('required', lang('fv_required'));

            if ($oFormValidation->run()) {

                $data                = array();
                $data['quote']       = $this->input->post('quote');
                $data['quote_by']    = $this->input->post('quote_by');
                $data['quote_dated'] = $this->input->post('quote_dated');

                if ($oTestimonialModel->update($this->data['testimonial']->id, $data)) {

                    $this->session->set_flashdata('success', lang('testimonials_edit_ok'));
                    redirect('admin/testimonial/testimonial/index');

                } else {

                    $this->data['error'] = lang('testimonials_edit_fail');
                }

            } else {

                $this->data['error'] = lang('fv_there_were_errors');
            }
        }

        // --------------------------------------------------------------------------

        //  Load views
        Helper::loadView('edit');
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a testimonial
     * @return void
     */
    public function delete()
    {
        if (!userHasPermission('admin:testimonial:testimonial:delete')) {

            unauthorised();
        }

        // --------------------------------------------------------------------------

        $oTestimonialModel = Factory::model('Testimonial', 'nailsapp/module-testimonial');

        $testimonial = $oTestimonialModel->get_by_id($this->uri->segment(5));

        if (!$testimonial) {

            $this->session->set_flashdata('error', lang('testimonials_common_bad_id'));
            redirect('admin/testimonial/testimonial/index');
        }

        // --------------------------------------------------------------------------

        if ($oTestimonialModel->delete($testimonial->id)) {

            $this->session->set_flashdata('success', lang('testimonials_delete_ok'));

        } else {

            $this->session->set_flashdata('error', lang('testimonials_delete_fail'));
        }

        // --------------------------------------------------------------------------

        redirect('admin/testimonial/testimonial/index');
    }
}
