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

class Testimonial extends \AdminController
{
    /**
     * Announces this controller's navGroups
     * @return stdClass
     */
    public static function announce()
    {
        $navGroup = new \Nails\Admin\Nav('Testimonials');
        $navGroup->addMethod('Manage Testimonials');

        return $navGroup;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns an array of extra permissions for this controller
     * @param  string $classIndex The class_index value, used when multiple admin instances are available
     * @return array
     */
    public static function permissions($classIndex = null)
    {
        $permissions = parent::permissions($classIndex);

        // --------------------------------------------------------------------------

        $permissions['can_manage'] = 'Can manage testimonials';
        $permissions['can_create'] = 'Can create testimonials';
        $permissions['can_edit']   = 'Can edit testimonials';
        $permissions['can_delete'] = 'Can delete testimonials';

        // --------------------------------------------------------------------------

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

        $this->load->model('testimonial/testimonial_model');
        $this->lang->load('admin_testimonials');
    }

    // --------------------------------------------------------------------------

    /**
     * Browse testimonials
     * @return void
     */
    public function index()
    {
        if (!userHasPermission('admin.testimonial:0.can_manage')) {

            unauthorised();
        }

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
        $totalRows                  = $this->testimonial_model->count_all($data);
        $this->data['testimonials'] = $this->testimonial_model->get_all($page, $perPage, $data);

        //  Set Search and Pagination objects for the view
        $this->data['search']     = \Nails\Admin\Helper::searchObject(true, $sortColumns, $sortOn, $sortOrder, $perPage, $keywords);
        $this->data['pagination'] = \Nails\Admin\Helper::paginationObject($page, $perPage, $totalRows);

        //  Add a header button
        if (userHasPermission('admin.testimonial:0.can_create')) {

             \Nails\Admin\Helper::addHeaderButton(
                'admin/testimonial/testimonial/create',
                lang('testimonials_nav_create')
            );
        }

        // --------------------------------------------------------------------------

        \Nails\Admin\Helper::loadView('index');
    }

    // --------------------------------------------------------------------------

    /**
     * Create a new testimonial
     * @return void
     */
    public function create()
    {
        if (!userHasPermission('admin.testimonial:0.can_create')) {

            unauthorised();
        }

        // --------------------------------------------------------------------------

        //  Page Title
        $this->data['page']->title = lang('testimonials_create_title');

        // --------------------------------------------------------------------------

        if ($this->input->post()) {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('quote', '', 'xss_clean|required');
            $this->form_validation->set_rules('quote_by', '', 'xss_clean|required');

            $this->form_validation->set_message('required', lang('fv_required'));

            if ($this->form_validation->run()) {

                $data             = array();
                $data['quote']    = $this->input->post('quote');
                $data['quote_by'] = $this->input->post('quote_by');

                if ($this->testimonial_model->create($data)) {

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
        \Nails\Admin\Helper::loadView('edit');
    }

    // --------------------------------------------------------------------------

    /**
     * Edit a testimonial
     * @return void
     */
    public function edit()
    {
        if (!userHasPermission('admin.testimonial:0.can_edit')) {

            unauthorised();
        }

        // --------------------------------------------------------------------------

        $this->data['testimonial'] = $this->testimonial_model->get_by_id($this->uri->segment(5));

        if (!$this->data['testimonial']) {

            $this->session->set_flashdata('error', lang('testimonials_common_bad_id'));
            redirect('admin/testimonial/testimonial/index');
        }

        // --------------------------------------------------------------------------

        //  Page Title
        $this->data['page']->title = lang('testimonials_edit_title');

        // --------------------------------------------------------------------------

        if ($this->input->post()) {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('quote', '', 'xss_clean|required');
            $this->form_validation->set_rules('quote_by', '', 'xss_clean|required');

            $this->form_validation->set_message('required', lang('fv_required'));

            if ($this->form_validation->run()) {

                $data             = array();
                $data['quote']    = $this->input->post('quote');
                $data['quote_by'] = $this->input->post('quote_by');

                if ($this->testimonial_model->update($this->data['testimonial']->id, $data)) {

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
        \Nails\Admin\Helper::loadView('edit');
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a testimonial
     * @return void
     */
    public function delete()
    {
        if (!userHasPermission('admin.testimonial:0.can_delete')) {

            unauthorised();
        }

        // --------------------------------------------------------------------------

        $testimonial = $this->testimonial_model->get_by_id($this->uri->segment(5));

        if (!$testimonial) {

            $this->session->set_flashdata('error', lang('testimonials_common_bad_id'));
            redirect('admin/testimonial/testimonial/index');
        }

        // --------------------------------------------------------------------------

        if ($this->testimonial_model->delete($testimonial->id)) {

            $this->session->set_flashdata('success', lang('testimonials_delete_ok'));

        } else {

            $this->session->set_flashdata('error', lang('testimonials_delete_fail'));
        }

        // --------------------------------------------------------------------------

        redirect('admin/testimonial/testimonial/index');
    }
}
