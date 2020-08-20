<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Insurance_jobs extends CI_Controller
{
	private $title = 'Insurance Jobs';

	public function __construct()
	{
		parent::__construct();

		$this->load->model(['LeadModel', 'TeamModel', 'TeamJobTrackModel', 'InsuranceJobDetailsModel', 'InsuranceJobAdjusterModel', 'PartyModel', 'FinancialModel', 'ClientLeadSourceModel',  'ClientClassificationModel', 'ActivityLogsModel', 'VendorModel', 'ItemModel', 'LeadMaterialModel', 'UserModel']);
		$this->load->library(['form_validation']);
		$this->lead = new LeadModel();
		$this->team = new TeamModel();
		$this->team_job_track = new TeamJobTrackModel();
		$this->insurance_job_details = new InsuranceJobDetailsModel();
		$this->insurance_job_adjuster = new InsuranceJobAdjusterModel();
		$this->party = new PartyModel();
		$this->financial = new FinancialModel();
		$this->leadSource = new ClientLeadSourceModel();
		$this->classification = new ClientClassificationModel();
		$this->activityLogs = new ActivityLogsModel();
		$this->vendor = new VendorModel();
		$this->item = new ItemModel();
		$this->lead_material = new LeadMaterialModel();
		$this->user = new UserModel();
	}

	public function index()
	{
		authAccess();

		$jobs = $this->lead->allInsuranceJobs();
		$this->load->view('header', ['title' => $this->title]);
		$this->load->view('insurance_job/index', [
			'jobs' => $jobs
		]);
		$this->load->view('footer');
	}

	public function view($jobid)
	{
		authAccess();

		$job = $this->lead->getLeadById($jobid);
		if ($job) {
			$next_lead = $this->lead->getNextLeadAfterId($job->status, $job->id, $job->category);
			$prev_lead = $this->lead->getPreviousLeadAfterId($job->status, $job->id, $job->category);
			$add_info = $this->party->getPartyByLeadId($jobid);
			$financial_record = $this->financial->getContractDetailsByJobId($jobid);
			$teams_detail = $this->team_job_track->getTeamName($jobid);
			$teams = $this->team->getTeamOnly(['is_deleted' => 0]);
			$insurance_job_details = false;
			$insurance_job_adjusters = false;
			if ($job->category === '0') {
				$insurance_job_details = $this->insurance_job_details->getInsuranceJobDetailsByLeadId($jobid);
				$insurance_job_adjusters = $this->insurance_job_adjuster->allAdjusters($jobid);
			}
			$job_type_tags = LeadModel::getType();
			$lead_status_tags = LeadModel::getStatus();
			$lead_category_tags = LeadModel::getCategory();
			$clientLeadSource = $this->leadSource->allLeadSource();
			$classification = $this->classification->allClassification();
			$aLogs = $this->activityLogs->getLogsByLeadId($jobid);
			$vendors = $this->vendor->getVendorList();
			$items = $this->item->getItemList();
			$materials = $this->lead_material->getMaterialsByLeadId($jobid);
			$lead_statuses = [0,1,2,3,4,];
			$prospect_statuses = [5,6,12,13,14,];
			$prospect2_statuses = [7,8,9,10,11,];

			$sales_rep = false;
			if(!empty($job->sales_rep_id)) {
				$sales_rep = $this->user->getUserById($job->sales_rep_id);
			}

			$this->load->view('header', ['title' => $this->title]);
			$this->load->view('insurance_job/show', [
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
				'materials' => $materials,
				'lead_statuses' => $lead_statuses,
				'prospect_statuses' => $prospect_statuses,
				'prospect2_statuses' => $prospect2_statuses,
				'sales_rep' => $sales_rep,
				'next_lead' => $next_lead,
				'prev_lead' => $prev_lead,
			]);
			$this->load->view('footer');
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect('lead/insurance-jobs');
		}
	}

	public function insertInsuranceDetails($jobid, $sub_base_path)
	{
		authAccess();

		$posts = $this->input->post();
		$insert = $this->insurance_job_details->insert([
			'insurance_carrier' => $posts['insurance_carrier'],
			'carrier_phone' => $posts['carrier_phone'],
			'carrier_email' => $posts['carrier_email'],
			'policy_number' => $posts['policy_number'],
			'date_of_loss' => $posts['date_of_loss'],
			'job_id' => $jobid
		]);
		redirect('lead/' . $sub_base_path . '/' . $jobid);
	}

	public function updateInsuranceDetails($jobid, $sub_base_path)
	{
		authAccess();

		$posts = $this->input->post();
		$update = $this->insurance_job_details->updateByLeadId($jobid, [
			'insurance_carrier' => $posts['insurance_carrier'],
			'carrier_phone' => $posts['carrier_phone'],
			'carrier_email' => $posts['carrier_email'],
			'policy_number' => $posts['policy_number'],
			'date_of_loss' => $posts['date_of_loss']
		]);
		redirect('lead/' . $sub_base_path . '/' . $jobid);
	}

	public function insertAdjuster($jobid, $sub_base_path)
	{
		authAccess();

		$posts = $this->input->post();
		$insert = $this->insurance_job_adjuster->insertAdjuster([
			'adjuster' => $posts['adjuster'],
			'adjuster_phone' => $posts['adjuster_phone'],
			'adjuster_email' => $posts['adjuster_email'],
			'job_id' => $jobid
		]);
		redirect('lead/' . $sub_base_path . '/' . $jobid);
	}

	public function deleteAdjuster($jobid, $id, $sub_base_path)
	{
		authAccess();

		$delete = $this->insurance_job_adjuster->deleteAdjuster($id, $jobid);
		redirect('lead/' . $sub_base_path . '/' . $jobid);
	}
}
