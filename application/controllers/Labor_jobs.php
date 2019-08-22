<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Labor_jobs extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		authAdminAccess();
		$this->load->model(['LeadModel', 'TeamModel', 'TeamJobTrackModel']);
		$this->load->library(['pagination', 'form_validation']);
		$this->lead = new LeadModel();
		// $this->status = new LeadStatusModel();
		$this->team = new TeamModel();
		$this->team_job_track = new TeamJobTrackModel();
	}

	public function index($start = 0)
	{
		$limit = 10;
		$pagiConfig = [
			'base_url' => base_url('lead/labor-jobs'),
			'total_rows' => $this->lead->getLaborOnlyJobsCount(),
			'per_page' => $limit
		];
		$this->pagination->initialize($pagiConfig);
		$jobs = $this->lead->allLaborOnlyJobs($start, $limit);
		$this->load->view('header', ['title' => 'Labor Only Jobs']);
		$this->load->view('labor_jobs/index', [
			'jobs' => $jobs,
			'pagiLinks' => $this->pagination->create_links()
		]);
		$this->load->view('footer');
	}


	public function view($jobid)
	{
		$job = $this->lead->getLeadById($jobid);
		$add_info = $this->lead->get_all_where('job_add_party', array('job_id' => $jobid));
		$teams_detail = $this->team_job_track->getTeamName($jobid);
		$teams = $this->team->getTeamOnly(['is_deleted' => 0]);

		$this->load->view('header', ['title' => 'Labor Job Detail']);
		$this->load->view('labor_jobs/show', [
			'jobid' => $jobid,
			'job' => $job,
			'add_info' => $add_info,
			'teams_detail' => $teams_detail,
			'teams' => $teams
		]);
		$this->load->view('footer');
	}


	public function addTeam($jobid)
	{
		$this->form_validation->set_rules('team_id', 'Team', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$posts = $this->input->post();
			$params = array();
			$params['team_id'] 		= $posts['team_id'];
			$params['job_id'] 		= $jobid;
			$params['assign_date'] 		= date('Y-m-d h:i:s');
			$params['is_deleted'] 		= false;
			$this->team_job_track->add_record($params);
		} else {
			$this->session->set_flashdata('errors', validation_errors());
		}
		redirect('lead/labor-job/' . $jobid);
	}

	public function removeTeam($jobid)
	{
		$this->team_job_track->remove_team($jobid);
		redirect('lead/labor-job/' . $jobid);
	}
}
