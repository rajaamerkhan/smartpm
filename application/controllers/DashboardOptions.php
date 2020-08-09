<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardOptions extends CI_Controller
{
    private $title = 'Dashboard Options';

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['DashboardBoxNameModel']);

        $this->dashboardBoxName = new DashboardBoxNameModel();
    }

    public function index()
    {
        authAccess();

        $boxNames = $this->dashboardBoxName->allNames();

        $this->load->view('header', [
            'title' => $this->title
        ]);
        $this->load->view('setting/dashboard-options', [
            'boxNames' => $boxNames
        ]);
        $this->load->view('footer');
    }

    public function updateBoxName()
    {
        authAccess();

        $boxNames = $this->dashboardBoxName->allNames();

        foreach ($boxNames as $boxName) {
            $this->form_validation->set_rules('name_' . $boxName->id, $boxName->name, 'trim|required');
        }

        if ($this->form_validation->run() == TRUE) {
            $data = $this->input->post();

            $allUpdated = true;
            foreach ($boxNames as $boxName) {
                $update = $this->dashboardBoxName->update($boxName->id, [
                    'label' => $data['name_' . $boxName->id]
                ]);
                $allUpdated = $allUpdated && $update;
            }
            if (!$allUpdated) {
                $this->session->set_flashdata('errors', '<p>Unable to Update All Box Names.</p>');
            }

            // update session for custom labels
			$box_names = [];
			foreach ($this->dashboardBoxName->allNames() as $box_name) {
				if(isset($box_names[$box_name->name])) continue;
				$box_names[$box_name->name] = $box_name->label;
			}
			$this->session->set_userdata('box_names', $box_names);
        } else {
            $this->session->set_flashdata('errors', validation_errors());
        }

        redirect('setting/dashboard-options');
    }
}
