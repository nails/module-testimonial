<?php


/**
 * Manage testimonials
 *
 * @package     Nails
 * @subpackage  module-admin
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

        //  Page Title
        $this->data['page']->title = lang('testimonials_index_title');

        // --------------------------------------------------------------------------

        $this->data['testimonials'] = $this->testimonial_model->get_all();

        // --------------------------------------------------------------------------

        //  Load views
        $this->load->view('structure/header', $this->data);
        $this->load->view('admin/testimonial/index', $this->data);
        $this->load->view('structure/footer', $this->data);
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
            $this->form_validation->set_rules('order', '', 'xss_clean');

            $this->form_validation->set_message('required', lang('fv_required'));

            if ($this->form_validation->run()) {

                $data             = array();
                $data['quote']    = $this->input->post('quote');
                $data['quote_by'] = $this->input->post('quote_by');
                $data['order']    = (int) $this->input->post('order');

                if ($this->testimonial_model->create($data)) {

                    $this->session->set_flashdata('success', lang('testimonials_create_ok'));
                    redirect('admin/testimonial');

                } else {

                    $this->data['error'] = lang('testimonials_create_fail');
                }

            } else {

                $this->data['error'] = lang('fv_there_were_errors');
            }
        }

        // --------------------------------------------------------------------------

        //  Load views
        $this->load->view('structure/header', $this->data);
        $this->load->view('admin/testimonial/create', $this->data);
        $this->load->view('structure/footer', $this->data);
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

        $this->data['testimonial'] = $this->testimonial_model->get_by_id($this->uri->segment(4));

        if (!$this->data['testimonial']) {

            $this->session->set_flashdata('error', lang('testimonials_common_bad_id'));
            redirect('admin/testimonial');
        }

        // --------------------------------------------------------------------------

        //  Page Title
        $this->data['page']->title = lang('testimonials_edit_title');

        // --------------------------------------------------------------------------

        if ($this->input->post()) {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('quote', '', 'xss_clean|required');
            $this->form_validation->set_rules('quote_by', '', 'xss_clean|required');
            $this->form_validation->set_rules('order', '', 'xss_clean');

            $this->form_validation->set_message('required', lang('fv_required'));

            if ($this->form_validation->run()) {

                $data             = array();
                $data['quote']    = $this->input->post('quote');
                $data['quote_by'] = $this->input->post('quote_by');
                $data['order']    = (int) $this->input->post('order');

                if ($this->testimonial_model->update($this->data['testimonial']->id, $data)) {

                    $this->session->set_flashdata('success', lang('testimonials_edit_ok'));
                    redirect('admin/testimonial');

                } else {

                    $this->data['error'] = lang('testimonials_edit_fail');
                }

            } else {

                $this->data['error'] = lang('fv_there_were_errors');
            }
        }

        // --------------------------------------------------------------------------

        //  Load views
        $this->load->view('structure/header', $this->data);
        $this->load->view('admin/testimonial/edit', $this->data);
        $this->load->view('structure/footer', $this->data);
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

        $testimonial = $this->testimonial_model->get_by_id($this->uri->segment(4));

        if (!$testimonial) {

            $this->session->set_flashdata('error', lang('testimonials_common_bad_id'));
            redirect('admin/testimonial');
        }

        // --------------------------------------------------------------------------

        if ($this->testimonial_model->delete($testimonial->id)) {

            $this->session->set_flashdata('success', lang('testimonials_delete_ok'));

        } else {

            $this->session->set_flashdata('error', lang('testimonials_delete_fail'));
        }

        // --------------------------------------------------------------------------

        redirect('admin/testimonial');
    }
}
