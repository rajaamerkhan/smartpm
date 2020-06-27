<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Labor_jobs extends CI_Controller
{
	private $title = 'Labor Only Jobs';

	public function __construct()
	{
		parent::__construct();

		$this->load->model(['LeadModel', 'TeamModel', 'TeamJobTrackModel', 'PartyModel', 'FinancialModel', 'ClientLeadSourceModel',  'ClientClassificationModel', 'ActivityLogsModel', 'VendorModel', 'ItemModel', 'LeadMaterialModel']);
		$this->load->library(['form_validation']);
		$this->lead = new LeadModel();
		$this->team = new TeamModel();
		$this->team_job_track = new TeamJobTrackModel();
		$this->party = new PartyModel();
		$this->financial = new FinancialModel();
		$this->leadSource = new ClientLeadSourceModel();
		$this->classification = new ClientClassificationModel();
		$this->activityLogs = new ActivityLogsModel();
		$this->vendor = new VendorModel();
		$this->item = new ItemModel();
		$this->lead_material = new LeadMaterialModel();
	}

	public function index()
	{
		authAccess();

		$jobs = $this->lead->allLaborOnlyJobs();
		$this->load->view('header', ['title' => $this->title]);
		$this->load->view('labor_jobs/index', [
			'jobs' => $jobs
		]);
		$this->load->view('footer');
	}


	public function view($jobid)
	{
		authAccess();

		$job = $this->lead->getLeadById($jobid);
		if ($job) {
			$add_info = $this->party->getPartyByLeadId($jobid);
			$financial_record = $this->financial->getContractDetailsByJobId($jobid);
			$teams_detail = $this->team_job_track->getTeamName($jobid);
			$teams = $this->team->getTeamOnly(['is_deleted' => 0]);
			$insurance_job_details = false;
			$insurance_job_adjusters = false;
			$job_type_tags = LeadModel::getType();
			$lead_status_tags = LeadModel::getStatus();
			$lead_category_tags = LeadModel::getCategory();
			$clientLeadSource = $this->leadSource->allLeadSource();
			$classification = $this->classification->allClassification();
			$aLogs = $this->activityLogs->getLogsByLeadId($jobid);
			$vendors = $this->vendor->getVendorList();
			$items = $this->item->getItemList();
			$materials = $this->lead_material->getMaterialsByLeadId($jobid);

			$this->load->view('header', ['title' => $this->title]);
			$this->load->view('labor_jobs/show', [
				'jobid' => $jobid,
				'job' => $job,
				'add_info' => $add_info,
				'financial_record' => $financial_record,
				'teams_detail' => $teams_detail,
				'teams' => $teams,
				'insurance_job_details' => $insurance_job_details,
				'insurance_job_adjusters' => $insurance_job_adjusters,
				'job_type_tags' => $job_type_tags,
				'lead_status_tags' => $lead_status_tags,
				'lead_category_tags' => $lead_category_tags,
				'leadSources' => $clientLeadSource,
				'classification' => $classification,
				'aLogs' => $aLogs,
				'items' => $items,
				'vendors' => $vendors,
				'materials' => $materials
			]);
			$this->load->view('footer');
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect('lead/labor-jobs');
		}
	}
}
