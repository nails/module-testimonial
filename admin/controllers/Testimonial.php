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

            $oNavGroup = Factory::factory('Nav', 'nails/module-admin');
            $oNavGroup->setLabel('Testimonials');
            $oNavGroup->setIcon('fa-comments');
            $oNavGroup->addAction('Manage Testimonials');

            return $oNavGroup;
        }
    }

    // --------------------------------------------------------------------------

    /**
     * Returns an array of extra permissions for this controller
     * @return array
     */
    public static function permissions(): array
    {
        $aPermissions = parent::permissions();

        $aPermissions['manage'] = 'Can manage testimonials';
        $aPermissions['create'] = 'Can create testimonials';
        $aPermissions['edit']   = 'Can edit testimonials';
        $aPermissions['delete'] = 'Can delete testimonials';

        return $aPermissions;
    }

    // --------------------------------------------------------------------------

    /**
     * Constructs the controller
     */
    public function __construct()
    {
        parent::__construct();
        get_instance()->lang->load('admin_testimonials');
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

        $oTestimonialModel = Factory::model('Testimonial', 'nails/module-testimonial');

        // --------------------------------------------------------------------------

        //  Set method info
        $this->data['page']->title = lang('testimonials_index_title');

        // --------------------------------------------------------------------------

        //  Get pagination and search/sort variables
        $oInput     = Factory::service('Input');
        $iPage      = (int) $oInput->get('page') ?: 0;
        $iPerPage   = (int) $oInput->get('perPage') ?: 50;
        $sSortOn    = $oInput->get('sortOn') ?: 'created';
        $sSortOrder = $oInput->get('sortOrder') ?: 'desc';
        $sKeywords  = $oInput->get('keywords') ?: '';

        // --------------------------------------------------------------------------

        //  Define the sortable columns
        $sortColumns = [
            'created'  => 'Created Date',
            'modified' => 'Modified Date',
            'quote_by' => 'Quotee',
        ];

        // --------------------------------------------------------------------------

        //  Define the $aData variable for the queries
        $aData = [
            'sort'     => [
                [$sSortOn, $sSortOrder],
            ],
            'keywords' => $sKeywords,
        ];

        //  Get the items for the page
        $totalRows                  = $oTestimonialModel->countAll($aData);
        $this->data['testimonials'] = $oTestimonialModel->getAll($iPage, $iPerPage, $aData);

        //  Set Search and Pagination objects for the view
        $this->data['search']     = Helper::searchObject(true, $sortColumns, $sSortOn, $sSortOrder, $iPerPage, $sKeywords);
        $this->data['pagination'] = Helper::paginationObject($iPage, $iPerPage, $totalRows);

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

        $oInput = Factory::service('Input');
        if ($oInput->post()) {

            $oFormValidation = Factory::service('FormValidation');
            $oFormValidation->set_rules('quote', '', 'required');
            $oFormValidation->set_rules('quote_by', '', 'required');
            $oFormValidation->set_rules('quote_dated', '', 'required');

            $oFormValidation->set_message('required', lang('fv_required'));

            if ($oFormValidation->run()) {

                $aData = [
                    'quote'       => $oInput->post('quote'),
                    'quote_by'    => $oInput->post('quote_by'),
                    'quote_dated' => $oInput->post('quote_dated'),
                ];

                $oTestimonialModel = Factory::model('Testimonial', 'nails/module-testimonial');

                if ($oTestimonialModel->create($aData)) {

                    $oSession = Factory::service('Session', 'nails/module-auth');
                    $oSession->setFlashData('success', lang('testimonials_create_ok'));
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

        $oUri              = Factory::service('Uri');
        $oSession          = Factory::service('Session', 'nails/module-auth');
        $oTestimonialModel = Factory::model('Testimonial', 'nails/module-testimonial');

        $this->data['testimonial'] = $oTestimonialModel->getById($oUri->segment(5));

        if (!$this->data['testimonial']) {
            $oSession->setFlashData('error', lang('testimonials_common_bad_id'));
            redirect('admin/testimonial/testimonial/index');
        }

        // --------------------------------------------------------------------------

        //  Page Title
        $this->data['page']->title = lang('testimonials_edit_title');

        // --------------------------------------------------------------------------

        $oInput = Factory::service('Input');
        if ($oInput->post()) {

            $oFormValidation = Factory::service('FormValidation');
            $oFormValidation->set_rules('quote', '', 'required');
            $oFormValidation->set_rules('quote_by', '', 'required');
            $oFormValidation->set_rules('quote_dated', '', 'required');

            $oFormValidation->set_message('required', lang('fv_required'));

            if ($oFormValidation->run()) {

                $aData = [
                    'quote'       => $oInput->post('quote'),
                    'quote_by'    => $oInput->post('quote_by'),
                    'quote_dated' => $oInput->post('quote_dated'),
                ];

                if ($oTestimonialModel->update($this->data['testimonial']->id, $aData)) {

                    $oSession->setFlashData('success', lang('testimonials_edit_ok'));
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

        $oUri              = Factory::service('Uri');
        $oSession          = Factory::service('Session', 'nails/module-auth');
        $oTestimonialModel = Factory::model('Testimonial', 'nails/module-testimonial');

        $testimonial = $oTestimonialModel->getById($oUri->segment(5));

        if (!$testimonial) {
            $oSession->setFlashData('error', lang('testimonials_common_bad_id'));
            redirect('admin/testimonial/testimonial/index');
        }

        // --------------------------------------------------------------------------

        if ($oTestimonialModel->delete($testimonial->id)) {
            $oSession->setFlashData('success', lang('testimonials_delete_ok'));
        } else {
            $oSession->setFlashData('error', lang('testimonials_delete_fail'));
        }

        // --------------------------------------------------------------------------

        redirect('admin/testimonial/testimonial/index');
    }
}
