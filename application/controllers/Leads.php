<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Leads extends CI_Controller
{
	private $title = 'Leads / Clients';

	public function __construct()
	{
		parent::__construct();

		$this->load->model(['LeadModel', 'LeadNoteModel', 'LeadNoteReplyModel', 'UserModel', 'PartyModel', 'InsuranceJobDetailsModel', 'InsuranceJobAdjusterModel', 'TeamModel', 'TeamJobTrackModel', 'PartyModel', 'ClientLeadSourceModel', 'ClientClassificationModel', 'ActivityLogsModel']);
		$this->load->library(['form_validation', 'notify']);

		$this->lead = new LeadModel();
		$this->lead_note = new LeadNoteModel();
		$this->lead_note_reply = new LeadNoteReplyModel();
		$this->user = new UserModel();
		$this->party = new PartyModel();
		$this->insurance_job_details = new InsuranceJobDetailsModel();
		$this->insurance_job_adjuster = new InsuranceJobAdjusterModel();
		$this->team = new TeamModel();
		$this->team_job_track = new TeamJobTrackModel();
		$this->leadSource = new ClientLeadSourceModel();
		$this->classification = new ClientClassificationModel();
		$this->activityLogs = new ActivityLogsModel();
	}

	public function index()
	{
		authAccess();

		$leads = $this->lead->allLeads();
		$this->load->view('header', ['title' => $this->title]);
		$this->load->view('leads/index', [
			'leads' => $leads
		]);
		$this->load->view('footer');
	}

	public function status($status)
	{
		authAccess();

		$leads = $this->lead->allLeadsByStatus($status);
		$this->load->view('header', ['title' => $this->title]);
		$this->load->view('leads/index', [
			'leads' => $leads
		]);
		$this->load->view('footer');
	}

	public function create()
	{
		authAccess();

		$job_type_tags = LeadModel::getType();
		$lead_status_tags = LeadModel::getStatus();
		$lead_category_tags = LeadModel::getCategory();
		$clientLeadSource = $this->leadSource->allLeadSource();
		$classification = $this->classification->allClassification();

		$this->load->view('header', ['title' => $this->title]);
		$this->load->view('leads/create', [
			'job_type_tags' => $job_type_tags,
			'lead_status_tags' => $lead_status_tags,
			'lead_category_tags' => $lead_category_tags,
			'leadSources' => $clientLeadSource,
			'classification' => $classification
		]);
		$this->load->view('footer');
	}

	public function store()
	{
		authAccess();

		$clientLeadSourceKeys = implode(',', array_column($this->leadSource->allLeadSource(), 'id'));

		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('zip', 'Postal Code', 'trim|required');
		$this->form_validation->set_rules('phone1', 'Cell Phone', 'trim|required');
		$this->form_validation->set_rules('phone2', 'Home Phone', 'trim');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
		$this->form_validation->set_rules('lead_source', 'Lead Source', 'trim|numeric|in_list[' . $clientLeadSourceKeys . ']');
		$this->form_validation->set_rules('status', 'Status', 'trim|required|numeric');
		$this->form_validation->set_rules('category', 'Category', 'trim|required|numeric');
		$this->form_validation->set_rules('type', 'Type', 'trim|required|numeric');
		$this->form_validation->set_rules('classification', 'Classification', 'trim|required|numeric');

		if ($this->form_validation->run() == TRUE) {
			$posts = $this->input->post();
			$insert = $this->lead->insert([
				'firstname' => $posts['firstname'],
				'lastname' => $posts['lastname'],
				'address' => $posts['address'],
				'city' => $posts['city'],
				'state' => $posts['state'],
				'zip' => $posts['zip'],
				'phone1' => $posts['phone1'],
				'phone2' => $posts['phone2'],
				'email' => $posts['email'],
				'lead_source' => $posts['lead_source'],
				'status' => $posts['status'],
				'category' => $posts['category'],
				'type' => $posts['type'],
				'classification' => $posts['classification'],
				'entry_date' => date('Y-m-d h:i:s')
			]);

			if ($insert) {
				$al_insert = $this->activityLogs->insert([
					'module' => 0,
					'module_id' => $insert,
					'type' => 0
				]);
				$ap_insert = $this->party->insert([
					'job_id' => $insert,
					'fname' => $posts['ap_firstname'],
					'lname' => $posts['ap_lastname'],
					'email' => $posts['ap_email'],
					'phone' => $posts['ap_phone']
				]);
				redirect('lead/' . $insert);
			} else {
				$this->session->set_flashdata('errors', '<p>Unable to Create Lead.</p>');
				redirect('lead/create');
			}
		} else {
			$this->session->set_flashdata('errors', validation_errors());
			redirect('lead/create');
		}
	}

	public function edit($jobid, $sub_base_path = '')
	{
		authAccess();

		$o_sub_base_path = $sub_base_path;
		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$lead = $this->lead->getLeadById($jobid);
		if ($lead) {
			$add_info = $this->party->getPartyByLeadId($jobid);
			$teams_detail = false;
			$teams = [];
			if (in_array($lead->status, [7, 14])) {
				$teams_detail = $this->team_job_track->getTeamName($jobid);
				$teams = $this->team->getTeamOnly(['is_deleted' => 0]);
			}
			$insurance_job_details = false;
			$insurance_job_adjusters = false;
			if ($lead->category === '0') {
				$insurance_job_details = $this->insurance_job_details->getInsuranceJobDetailsByLeadId($jobid);
				$insurance_job_adjusters = $this->insurance_job_adjuster->allAdjusters($jobid);
			}
			$job_type_tags = LeadModel::getType();
			$lead_status_tags = LeadModel::getStatus();
			$lead_category_tags = LeadModel::getCategory();
			$clientLeadSource = $this->leadSource->allLeadSource();
			$this->load->view('header', ['title' => $this->title]);
			$this->load->view('leads/edit', [
				'job_type_tags' => $job_type_tags,
				'lead_status_tags' => $lead_status_tags,
				'lead_category_tags' => $lead_category_tags,
				'lead' => $lead,
				'add_info' => $add_info,
				'jobid' => $jobid,
				'sub_base_path' => $sub_base_path,
				'insurance_job_details' => $insurance_job_details,
				'insurance_job_adjusters' => $insurance_job_adjusters,
				'teams_detail' => $teams_detail,
				'teams' => $teams,
				'leadSources' => $clientLeadSource
			]);
			$this->load->view('footer');
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect($o_sub_base_path != '' ? ('lead/' . $o_sub_base_path . 's') : 'leads');
		}
	}

	public function update($id, $sub_base_path = '')
	{
		authAccess();

		$clientLeadSourceKeys = implode(',', array_column($this->leadSource->allLeadSource(), 'id'));

		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('zip', 'Postal Code', 'trim|required');
		$this->form_validation->set_rules('phone1', 'Phone 1', 'trim|required');
		$this->form_validation->set_rules('phone2', 'Phone 2', 'trim');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
		$this->form_validation->set_rules('lead_source', 'Lead Source', 'trim|numeric|in_list[' . $clientLeadSourceKeys . ']');

		if ($this->form_validation->run() == TRUE) {
			$posts = $this->input->post();
			$update = $this->lead->update($id, [
				'firstname' => $posts['firstname'],
				'lastname' => $posts['lastname'],
				'address' => $posts['address'],
				'city' => $posts['city'],
				'state' => $posts['state'],
				'zip' => $posts['zip'],
				'phone1' => $posts['phone1'],
				'phone2' => $posts['phone2'],
				'email' => $posts['email'],
				'lead_source' => $posts['lead_source'],
			]);
			if ($update) {
				redirect('lead/' . $sub_base_path . $id);
			} else {
				$this->session->set_flashdata('errors', '<p>Unable to Update Task.</p>');

				redirect('lead/' . $sub_base_path . $id);
			}
		} else {
			$this->session->set_flashdata('errors', validation_errors());
			redirect('lead/' . $sub_base_path . $id);
		}
	}

	public function updatestatus($id, $sub_base_path = '')
	{
		authAccess();

		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$this->form_validation->set_rules('status', 'Status', 'trim|required|numeric');
		$this->form_validation->set_rules('category', 'Category', 'trim|required|numeric');
		$this->form_validation->set_rules('type', 'Type', 'trim|required|numeric');
		$this->form_validation->set_rules('classification', 'Classification', 'trim|required|numeric');
		$this->form_validation->set_rules('dumpster_status', 'Dumpster', 'trim|numeric');
		$this->form_validation->set_rules('materials_status', 'Materials', 'trim|numeric');
		$this->form_validation->set_rules('labor_status', 'Labor', 'trim|numeric');
		$this->form_validation->set_rules('permit_status', 'Permit', 'trim|numeric');

		if ($this->form_validation->run() == TRUE) {
			$_lead = $this->lead->getLeadById($id);
			$posts = $this->input->post();
			$updateData = [
				'status' => $posts['status'],
				'category' => $posts['category'],
				'type' => $posts['type'],
				'classification' => $posts['classification']
			];
			if ($_lead->status === '8') {
				$updateData['dumpster_status'] = $posts['dumpster_status'];
				$updateData['materials_status'] = $posts['materials_status'];
				$updateData['labor_status'] = $posts['labor_status'];
				$updateData['permit_status'] = $posts['permit_status'];
			}
			$update = $this->lead->update($id, $updateData);

			if ($update) {
				$lead = $this->lead->getLeadById($id);
				if ($_lead->status != $lead->status) {
					$al_insert = $this->activityLogs->insert([
						'module' => 0,
						'module_id' => $id,
						'type' => 4,
						'activity_data' => json_encode([
							'status' => $lead->status
						])
					]);
				}
				if ($_lead->dumpster_status != $lead->dumpster_status) {
					$al_insert = $this->activityLogs->insert([
						'module' => 0,
						'module_id' => $id,
						'type' => 6,
						'activity_data' => json_encode([
							'dumpster_status' => $lead->dumpster_status
						])
					]);
				}
				if ($_lead->materials_status != $lead->materials_status) {
					$al_insert = $this->activityLogs->insert([
						'module' => 0,
						'module_id' => $id,
						'type' => 7,
						'activity_data' => json_encode([
							'materials_status' => $lead->materials_status
						])
					]);
				}
				if ($_lead->labor_status != $lead->labor_status) {
					$al_insert = $this->activityLogs->insert([
						'module' => 0,
						'module_id' => $id,
						'type' => 8,
						'activity_data' => json_encode([
							'labor_status' => $lead->labor_status
						])
					]);
				}
				if ($_lead->permit_status != $lead->permit_status) {
					$al_insert = $this->activityLogs->insert([
						'module' => 0,
						'module_id' => $id,
						'type' => 9,
						'activity_data' => json_encode([
							'permit_status' => $lead->permit_status
						])
					]);
				}
				$sub_base_path = '';
				if ($lead->status === '8') {
					$sub_base_path = 'production-job/';
				} else if ($lead->status === '9') {
					$sub_base_path = 'completed-job/';
				} else if ($lead->category === '0') {
					$sub_base_path = 'insurance-job/';
				} else if ($lead->category === '1') {
					$sub_base_path = 'cash-job/';
				} else if ($lead->category === '2') {
					$sub_base_path = 'labor-job/';
				} else if ($lead->category === '3') {
					$sub_base_path = 'financial-job/';
				} else {
					$sub_base_path = '';
				}
				redirect('lead/' . $sub_base_path . $id);
			} else {
				$this->session->set_flashdata('errors', '<p>Unable to Update Task.</p>');
				redirect('lead/' . $sub_base_path . $id);
			}
		} else {
			$this->session->set_flashdata('errors', validation_errors());
			redirect('lead/' . $sub_base_path . $id);
		}
	}

	public function show($jobid)
	{
		authAccess();

		$lead = $this->lead->getLeadById($jobid);
		if ($lead) {
			$add_info = $this->party->getPartyByLeadId($jobid);
			$teams_detail = false;
			$teams = [];
			if (in_array($lead->status, [7, 14])) {
				$teams_detail = $this->team_job_track->getTeamName($jobid);
				$teams = $this->team->getTeamOnly(['is_deleted' => 0]);
			}
			$insurance_job_details = false;
			$insurance_job_adjusters = false;
			if ($lead->category === '0') {
				$insurance_job_details = $this->insurance_job_details->getInsuranceJobDetailsByLeadId($jobid);
				$insurance_job_adjusters = $this->insurance_job_adjuster->allAdjusters($jobid);
			}
			$job_type_tags = LeadModel::getType();
			$lead_status_tags = LeadModel::getStatus();
			$lead_category_tags = LeadModel::getCategory();
			$clientLeadSource = $this->leadSource->allLeadSource();
			$classification = $this->classification->allClassification();
			$aLogs = $this->activityLogs->getLogsByLeadId($jobid);

			$this->load->view('header', ['title' => $this->title]);
			$this->load->view('leads/show', [
				'lead' => $lead,
				'add_info' => $add_info,
				'jobid' => $jobid,
				'job_type_tags' => $job_type_tags,
				'lead_status_tags' => $lead_status_tags,
				'lead_category_tags' => $lead_category_tags,
				'insurance_job_details' => $insurance_job_details,
				'insurance_job_adjusters' => $insurance_job_adjusters,
				'teams_detail' => $teams_detail,
				'teams' => $teams,
				'leadSources' => $clientLeadSource,
				'classification' => $classification,
				'aLogs' => $aLogs
			]);
			$this->load->view('footer');
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect('leads');
		}
	}

	public function delete($id, $sub_base_path = '')
	{
		authAccess();

		$this->lead->delete($id);
		redirect($sub_base_path != '' ? ('lead/' . $sub_base_path . 's') : 'leads');
	}

	public function notes($leadId, $sub_base_path = '')
	{
		authAccess();

		$o_sub_base_path = $sub_base_path;
		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$lead = $this->lead->getLeadById($leadId);
		if ($lead) {
			$notes = $this->lead_note->getNotesByLeadId($leadId);
			$users = $this->user->getUserList();

			$this->load->view('header', ['title' => $this->title . ' Notes']);
			$this->load->view('leads/notes', [
				'lead' => $lead,
				'notes' => $notes,
				'users' => $users,
				'sub_base_path' => $sub_base_path
			]);
			$this->load->view('footer');
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect($o_sub_base_path != '' ? ('lead/' . $o_sub_base_path . 's') : 'leads');
		}
	}

	public function addNote($leadId, $sub_base_path = '')
	{
		authAccess();

		$o_sub_base_path = $sub_base_path;
		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$lead = $this->lead->getLeadById($leadId);
		if ($lead) {
			$this->form_validation->set_rules('note', 'Note', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$noteData = $this->input->post();
				$insert = $this->lead_note->insert([
					'note' => nl2br($noteData['note']),
					'job_id' => $leadId
				]);
				$al_insert = $this->activityLogs->insert([
					'module' => 0,
					'module_id' => $leadId,
					'type' => 1,
					'activity_data' => json_encode([
						'note' => nl2br($noteData['note'])
					])
				]);
				if ($insert) {
					$userIds = [];
					if (preg_match_all('~(@\w+)~', $noteData['note'], $matches, PREG_PATTERN_ORDER)) {
						$usernames = array_map(function ($val) {
							return ltrim($val, '@');
						}, $matches[1]);
						$userIds = $this->user->getUserIdArrByUserNames($usernames);
					}
					// use userIds to send emails to those users
					$users_insert = $userIds;
					if (count($users_insert)) {
						$userEmailIds = $this->user->getEmailIdArrByUserIds($users_insert);
						foreach ($userEmailIds as $userEmailId) {
							$this->notify = new Notify();
							$this->notify->sendNoteTagNotification($userEmailId, ($lead->firstname . ' ' . $lead->lastname), $noteData['note'], base_url('lead/' . $sub_base_path . $leadId . '/notes'));
						}

						$phoneNos = $this->user->getPhoneArrByUserIds($users_insert);
						foreach ($phoneNos as $phoneNo) {
							$this->notify = new Notify();
							$this->notify->sendNoteTagNotificationMob($phoneNo, ($lead->firstname . ' ' . $lead->lastname), $noteData['note'], base_url('lead/' . $sub_base_path . $leadId . '/notes'));
						}
					}
				} else {
					$this->session->set_flashdata('errors', '<p>Unable to add Note.</p>');
				}
			} else {
				$this->session->set_flashdata('errors', validation_errors());
			}
			redirect('lead/' . $sub_base_path . $leadId . '/notes');
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect($o_sub_base_path != '' ? ('lead/' . $o_sub_base_path . 's') : 'leads');
		}
	}

	public function updateNote($leadId, $noteId, $sub_base_path = '')
	{
		authAccess();

		$o_sub_base_path = $sub_base_path;
		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$lead = $this->lead->getLeadById($leadId);
		if ($lead) {
			$this->form_validation->set_rules('note', 'Note', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$noteData = $this->input->post();
				$update = $this->lead_note->update($noteId, [
					'note' => nl2br($noteData['note'])
				]);
				// ======================== change to update log ========================
				// $al_insert = $this->activityLogs->insert([
				// 	'module' => 0,
				// 	'module_id' => $leadId,
				// 	'type' => 1,
				// 	'activity_data' => json_encode([
				// 		'note' => nl2br($noteData['note'])
				// 	])
				// ]);
				if ($update) {
					// $userIds = [];
					// ======================== delete if not need to send notification ========================
					// if (preg_match_all('~(@\w+)~', $noteData['note'], $matches, PREG_PATTERN_ORDER)) {
					// 	$usernames = array_map(function ($val) {
					// 		return ltrim($val, '@');
					// 	}, $matches[1]);
					// 	$userIds = $this->user->getUserIdArrByUserNames($usernames);
					// }
					// $users_insert = $userIds;
					// if (count($users_insert)) {
					// 	$userEmailIds = $this->user->getEmailIdArrByUserIds($users_insert);
					// 	foreach ($userEmailIds as $userEmailId) {
					// 		$this->notify = new Notify();
					// 		$this->notify->sendNoteTagNotification($userEmailId, ($lead->firstname . ' ' . $lead->lastname));
					// 	}

					// 	$userMobEmailIds = $this->user->getMobEmailIdArrByUserIds($users_insert);
					// 	foreach ($userMobEmailIds as $userMobEmailId) {
					// 		$this->notify = new Notify();
					// 		$this->notify->sendNoteTagNotificationMob($userMobEmailId, ($lead->firstname . ' ' . $lead->lastname));
					// 	}
					// }
				} else {
					$this->session->set_flashdata('errors', '<p>Unable to Update Note.</p>');
				}
			} else {
				$this->session->set_flashdata('errors', validation_errors());
			}
			redirect('lead/' . $sub_base_path . $leadId . '/notes');
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect($o_sub_base_path != '' ? ('lead/' . $o_sub_base_path . 's') : 'leads');
		}
	}

	public function deleteNote($leadId, $noteId, $sub_base_path = '')
	{
		authAccess();

		$o_sub_base_path = $sub_base_path;
		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$lead = $this->lead->getLeadById($leadId);
		if ($lead) {
			$delete = $this->lead_note->delete($noteId, $leadId);
			if (!$delete) {
				$this->session->set_flashdata('errors', '<p>Unable to Delete Note.</p>');
			}
			redirect('lead/' . $sub_base_path . $leadId . '/notes');
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect($o_sub_base_path != '' ? ('lead/' . $o_sub_base_path . 's') : 'leads');
		}
	}

	public function replies($leadId, $noteId, $sub_base_path = '')
	{
		authAccess();

		$o_sub_base_path = $sub_base_path;
		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$lead = $this->lead->getLeadById($leadId);
		if ($lead) {
			$note = $this->lead_note->getNoteById($noteId, $leadId);
			if ($note) {
				$note_replies = $this->lead_note_reply->getRepliesByNoteId($noteId);
				$users = $this->user->getUserList();

				$this->load->view('header', ['title' => $this->title . ' Notes']);
				$this->load->view('leads/note_replies', [
					'lead' => $lead,
					'note' => $note,
					'note_replies' => $note_replies,
					'users' => $users,
					'sub_base_path' => $sub_base_path
				]);
				$this->load->view('footer');
			} else {
				$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
				redirect('lead/' . $sub_base_path . $leadId . '/notes');
			}
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect($o_sub_base_path != '' ? ('lead/' . $o_sub_base_path . 's') : 'leads');
		}
	}

	public function addNoteReply($leadId, $noteId, $sub_base_path = '')
	{
		authAccess();

		$o_sub_base_path = $sub_base_path;
		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$lead = $this->lead->getLeadById($leadId);
		if ($lead) {
			$note = $this->lead_note->getNoteById($noteId, $leadId);
			if ($note) {
				$this->form_validation->set_rules('reply', 'Reply', 'trim|required');

				if ($this->form_validation->run() == TRUE) {
					$replyData = $this->input->post();
					$insert = $this->lead_note_reply->insert([
						'reply' => nl2br($replyData['reply']),
						'note_id' => $noteId
					]);
					$al_insert = $this->activityLogs->insert([
						'module' => 0,
						'module_id' => $leadId,
						'type' => 1,
						'activity_data' => json_encode([
							'note' => nl2br($replyData['reply'])
						])
					]);
					if ($insert) {
						$userIds = [];
						if (preg_match_all('~(@\w+)~', $replyData['reply'], $matches, PREG_PATTERN_ORDER)) {
							$usernames = array_map(function ($val) {
								return ltrim($val, '@');
							}, $matches[1]);
							$userIds = $this->user->getUserIdArrByUserNames($usernames);
						}
						// use userIds to send emails to those users
						$users_insert = $userIds;
						if (count($users_insert)) {
							$userEmailIds = $this->user->getEmailIdArrByUserIds($users_insert);
							foreach ($userEmailIds as $userEmailId) {
								$this->notify = new Notify();
								$this->notify->sendNoteTagNotification($userEmailId, ($lead->firstname . ' ' . $lead->lastname), $replyData['reply'], base_url('lead/' . $sub_base_path . $leadId . '/note/' . $noteId . '/replies'));
							}

							$phoneNos = $this->user->getPhoneArrByUserIds($users_insert);
							foreach ($phoneNos as $phoneNo) {
								$this->notify = new Notify();
								$this->notify->sendNoteTagNotificationMob($phoneNo, ($lead->firstname . ' ' . $lead->lastname), $replyData['reply'], base_url('lead/' . $sub_base_path . $leadId . '/note/' . $noteId . '/replies'));
							}
						}
					} else {
						$this->session->set_flashdata('errors', '<p>Unable to add Note.</p>');
					}
				} else {
					$this->session->set_flashdata('errors', validation_errors());
				}
				redirect('lead/' . $sub_base_path . $leadId . '/note/' . $noteId . '/replies');
			} else {
				$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
				redirect('lead/' . $sub_base_path . $leadId . '/notes');
			}
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect($o_sub_base_path != '' ? ('lead/' . $o_sub_base_path . 's') : 'leads');
		}
	}

	public function updateNoteReply($leadId, $noteId, $replyId, $sub_base_path = '')
	{
		authAccess();

		$o_sub_base_path = $sub_base_path;
		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$lead = $this->lead->getLeadById($leadId);
		if ($lead) {
			$note = $this->lead_note->getNoteById($noteId, $leadId);
			if ($note) {
				$this->form_validation->set_rules('reply', 'Reply', 'trim|required');

				if ($this->form_validation->run() == TRUE) {
					$replyData = $this->input->post();
					$update = $this->lead_note_reply->update($replyId, [
						'reply' => nl2br($replyData['reply'])
					]);
					// $al_insert = $this->activityLogs->insert([
					// 	'module' => 0,
					// 	'module_id' => $leadId,
					// 	'type' => 1,
					// 	'activity_data' => json_encode([
					// 		'note' => nl2br($replyData['reply'])
					// 	])
					// ]);
					if ($update) {
						// $userIds = [];
						// if (preg_match_all('~(@\w+)~', $replyData['reply'], $matches, PREG_PATTERN_ORDER)) {
						// 	$usernames = array_map(function ($val) {
						// 		return ltrim($val, '@');
						// 	}, $matches[1]);
						// 	$userIds = $this->user->getUserIdArrByUserNames($usernames);
						// }
						// // use userIds to send emails to those users
						// $users_insert = $userIds;
						// if (count($users_insert)) {
						// 	$userEmailIds = $this->user->getEmailIdArrByUserIds($users_insert);
						// 	foreach ($userEmailIds as $userEmailId) {
						// 		$this->notify = new Notify();
						// 		$this->notify->sendNoteTagNotification($userEmailId, ($lead->firstname . ' ' . $lead->lastname));
						// 	}

						// 	$userMobEmailIds = $this->user->getMobEmailIdArrByUserIds($users_insert);
						// 	foreach ($userMobEmailIds as $userMobEmailId) {
						// 		$this->notify = new Notify();
						// 		$this->notify->sendNoteTagNotificationMob($userMobEmailId, ($lead->firstname . ' ' . $lead->lastname));
						// 	}
						// }
					} else {
						$this->session->set_flashdata('errors', '<p>Unable to Update Note.</p>');
					}
				} else {
					$this->session->set_flashdata('errors', validation_errors());
				}
				redirect('lead/' . $sub_base_path . $leadId . '/note/' . $noteId . '/replies');
			} else {
				$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
				redirect('lead/' . $sub_base_path . $leadId . '/notes');
			}
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect($o_sub_base_path != '' ? ('lead/' . $o_sub_base_path . 's') : 'leads');
		}
	}

	public function deleteNoteReply($leadId, $noteId, $replyId, $sub_base_path = '')
	{
		authAccess();

		$o_sub_base_path = $sub_base_path;
		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$lead = $this->lead->getLeadById($leadId);
		if ($lead) {
			$note = $this->lead_note->getNoteById($noteId, $leadId);
			if ($note) {
				$delete = $this->lead_note_reply->delete($replyId, $noteId);
				if (!$delete) {
					$this->session->set_flashdata('errors', '<p>Unable to Delete Note.</p>');
				}
				redirect('lead/' . $sub_base_path . $leadId . '/note/' . $noteId . '/replies');
			} else {
				$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
				redirect('lead/' . $sub_base_path . $leadId . '/notes');
			}
		} else {
			$this->session->set_flashdata('errors', '<p>Invalid Request.</p>');
			redirect($o_sub_base_path != '' ? ('lead/' . $o_sub_base_path . 's') : 'leads');
		}
	}

	public function addTeam($jobid, $sub_base_path = '')
	{
		authAccess();

		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$this->form_validation->set_rules('team_id', 'Team', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$posts = $this->input->post();
			$params = array();
			$params['team_id'] = $posts['team_id'];
			$params['job_id'] = $jobid;
			$params['assign_date'] = date('Y-m-d h:i:s');
			$params['is_deleted'] = false;
			$this->team_job_track->add_record($params);
		} else {
			$this->session->set_flashdata('errors', validation_errors());
		}
		redirect('lead/' . $sub_base_path . $jobid);
	}

	public function removeTeam($jobid, $sub_base_path = '')
	{
		authAccess();

		$sub_base_path = $sub_base_path != '' ? ($sub_base_path . '/') : $sub_base_path;
		$this->team_job_track->remove_team($jobid);
		redirect('lead/' . $sub_base_path . $jobid);
	}
}
