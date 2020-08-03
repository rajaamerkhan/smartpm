<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search extends CI_Controller
{
    private $title = 'Search';

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['form_validation']);
        $this->load->model(['LeadModel', 'TaskModel', 'UserModel']);
        $this->lead = new LeadModel();
        $this->task = new TaskModel();
        $this->user = new UserModel();
    }

    public function leads()
    {
        // authAccess();

        $this->form_validation->set_rules('keyword', 'Keyword', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $posts = $this->input->post();

            $search = $posts['keyword'];
            $keywords = [];
            foreach (explode(' ', $search) as $k) {
                if ($k) {
                    $keywords[] = $k;
                }
            }

            $result = $this->lead->search($keywords);

            echo json_encode([
                'type' => 'leads',
                'search' => $search,
                'result' => $result
            ]);
        } else {
            echo json_encode([
                'type' => 'leads',
                'errors' => validation_errors()
            ]);
        }
    }

    public function tasks()
    {
        // authAccess();

        $this->form_validation->set_rules('keyword', 'Keyword', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $posts = $this->input->post();

            $search = $posts['keyword'];
            $keywords = [];
            foreach (explode(' ', $search) as $k) {
                if ($k) {
                    $keywords[] = $k;
                }
            }

            $result = $this->task->search($keywords);

            echo json_encode([
                'type' => 'tasks',
                'search' => $search,
                'result' => $result
            ]);
        } else {
            echo json_encode([
                'type' => 'tasks',
                'errors' => validation_errors()
            ]);
        }
    }

    public function users()
    {
        // authAccess();

        $this->form_validation->set_rules('keyword', 'Keyword', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $posts = $this->input->post();

            $search = $posts['keyword'];
            $keywords = [];
            foreach (explode(' ', $search) as $k) {
                if ($k) {
                    $keywords[] = $k;
                }
            }

            $result = $this->user->search($keywords);

            echo json_encode([
                'type' => 'users',
                'search' => $search,
                'result' => $result
            ]);
        } else {
            echo json_encode([
                'type' => 'users',
                'errors' => validation_errors()
            ]);
        }
    }

    public function sales_rep()
    {
        // authAccess();

        $this->form_validation->set_rules('term', 'Search Term', 'trim|required');
		$results = [];

        if ($this->form_validation->run() == TRUE) {
            $posts = $this->input->post();

            $search = $posts['term'];
            $keywords = [];
            foreach (explode(' ', $search) as $k) {
                if ($k) {
                    $keywords[] = $k;
                }
            }

            $results = $this->user->search_sales_rep($keywords);
        }

		echo json_encode([
			'results' => $results,
			'pagination' => false,
		]);
    }
}
