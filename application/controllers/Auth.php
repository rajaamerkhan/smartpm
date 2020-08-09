<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library(['new_company', 'notify']);
		$this->load->model(['UserModel', 'CompanyModel', 'M_CompanyModel', 'M_EmailCredModel', 'M_TwilioCredModel', 'M_DatabaseModel', 'AdminSettingModel', 'DashboardBoxNameModel']);
		$this->m_company = new M_CompanyModel();
		$this->m_emailCred = new M_EmailCredModel();
		$this->m_database = new M_DatabaseModel();
		$this->m_twilioCred = new M_TwilioCredModel();
		$this->dashboardBoxName = new DashboardBoxNameModel();
	}

	public function index()
	{
		if ($this->session->logged_in) {
			redirect('dashboard');
			die();
		}
		redirect('login');
	}

	public function login()
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$this->load->view('auth/index');
	}

	public function auth()
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$this->form_validation->set_rules('company_code', 'Company Code', 'trim|required|numeric');
		$this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			$authData = $this->input->post();
			$db = 'smartpm_' . $authData['company_code'];
			if (verifyDB($db, TRUE)) {
				dbSelect($db);
				$this->user = new UserModel();
				if ($user = $this->user->authenticate($authData['email'], $authData['password'])) {
					if (empty($user->verification_token)) {
						if ($user->is_active == 1) {
							$this->admin_setting = new AdminSettingModel();
							$admin_conf = $this->admin_setting->getAdminSetting();
							$this->session->set_userdata([
								'first_name' => $user->first_name,
								'last_name' => $user->last_name,
								'username' => $user->username,
								'id' => $user->id,
								'email_id' => $user->email_id,
								'level' => $user->level,
								'company_id' => $user->company_id,
								'company_code' => $authData['company_code'],
								'database' => $db,
								'logoUrl' => $admin_conf->url,
								'logged_in' => TRUE
							]);
							$result1 = $this->user->get_crm_data('admin_setting', ['color', 'url', 'favicon'], ['company_id' => $user->company_id]);
							$this->session->set_userdata('admindata', $result1);

							// set custom labels
							$box_names = [];
							foreach ($this->dashboardBoxName->allNames() as $box_name) {
								if(isset($box_names[$box_name->name])) continue;
								$box_names[$box_name->name] = $box_name->label;
							}
							$this->session->set_userdata('box_names', $box_names);

							redirect('dashboard');
						} else {
							$message = '<div class="error"><p>Your account is not activated.</p></div>';
							$this->session->set_flashdata('errors', $message);
							redirect('login');
						}
					} else {
						$message = '<div class="error"><p>Complete Email ID verification before login. <a href="' . base_url('resend-verification-email/' . $authData['company_code'] . '/' . rawurlencode($authData['email'])) . '" data-method="POST">Resend Email</a></p></div>';
						$this->session->set_flashdata('errors', $message);
						redirect('login');
					}
				} else {
					$message = '<div class="error"><p>Email ID or Password Invalid.</p></div>';
					$this->session->set_flashdata('errors', $message);
					redirect('login');
				}
			} else {
				$message = '<div class="error"><p>Unable to find database for entered Company Code.</p></div>';
				$this->session->set_flashdata('errors', $message);
				redirect('login');
			}
		} else {
			$message = '<div class="error">' . validation_errors() . '</div>';
			$this->session->set_flashdata('errors', $message);
			redirect('login');
		}
	}

	public function forgotPassword()
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$this->load->view('auth/forgot-password');
	}

	public function sendPasswordToken()
	{
		$this->form_validation->set_rules('company_code', 'Company Code', 'trim|required|numeric');
		$this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email');
		if ($this->form_validation->run() == TRUE) {
			$data = $this->input->post();
			$db = 'smartpm_' . $data['company_code'];
			if (verifyDB($db, TRUE)) {
				dbSelect($db);
				$this->user = new UserModel();
				$user = $this->user->getUserByEmailId($data['email']);
				if ($user) {
					if ($user->is_active == 1) {
						$token = $this->user->setPasswordToken($user);
						$this->notify->resetPassword($user->email_id, $token);
						$message = '<div class="error" title="Error:" style="color:white;background-color: green;border: green;">Reset Password link successfully sent to your Email ID.</div>';
						$this->session->set_flashdata('errors', $message);
						redirect('forgot-password');
					} else if (empty($user->verification_token)) {
						$message = '<div class="error"><p>Your account is not activated.</p></div>';
						$this->session->set_flashdata('errors', $message);
						redirect('forgot-password');
					} else {
						$message = '<div class="error"><p>Your Email ID is not verified.</p></div>';
						$this->session->set_flashdata('errors', $message);
						redirect('forgot-password');
					}
				} else {
					$message = '<div class="error"><p>Unable to find your email in our system.</p></div>';
					$this->session->set_flashdata('errors', $message);
					redirect('forgot-password');
				}
			} else {
				$message = '<div class="error"><p>Unable to find database for entered Company Code.</p></div>';
				$this->session->set_flashdata('errors', $message);
				redirect('forgot-password');
			}
		} else {
			$message = '<div class="error">' . validation_errors() . '</div>';
			$this->session->set_flashdata('errors', $message);
			redirect('forgot-password');
		}
	}

	public function resetPassword($token)
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$this->load->view('auth/reset-password', [
			'token' => $token
		]);
	}

	public function setTokenVerifiedPassword($token)
	{
		$this->form_validation->set_rules('company_code', 'Company Code', 'trim|required|numeric');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
		if ($this->form_validation->run() == TRUE) {
			$data = $this->input->post();
			$db = 'smartpm_' . $data['company_code'];
			if (verifyDB($db, TRUE)) {
				dbSelect($db);
				$this->user = new UserModel();
				$user = $this->user->getUserByPasswordToken($token);
				if ($user) {
					if ($user->is_active == 1) {
						if ($user->token_expiry > date('Y-m-d H:i:s')) {
							if ($token === $user->password_token) {
								$this->user->resetPassword($user, $data['password']);
								redirect('login');
							} else {
								$message = '<div class="error"><p>Invalid Password Reset Token.</p></div>';
								$this->session->set_flashdata('errors', $message);
								redirect('reset-password/' . $token);
							}
						} else {
							$message = '<div class="error"><p>Your Password Reset token is expired.</p></div>';
							$this->session->set_flashdata('errors', $message);
							redirect('reset-password/' . $token);
						}
					} else if (empty($user->password_token)) {
						$message = '<div class="error"><p>Your account is not activated.</p></div>';
						$this->session->set_flashdata('errors', $message);
						redirect('reset-password/' . $token);
					}
				} else {
					$message = '<div class="error"><p>Unable to find your token in our system.</p></div>';
					$this->session->set_flashdata('errors', $message);
					redirect('reset-password/' . $token);
				}
			} else {
				$message = '<div class="error"><p>Unable to find database for entered Company Code.</p></div>';
				$this->session->set_flashdata('errors', $message);
				redirect('reset-password/' . $token);
			}
		} else {
			$message = '<div class="error">' . validation_errors() . '</div>';
			$this->session->set_flashdata('errors', $message);
			redirect('reset-password/' . $token);
		}
	}

	public function createPassword($token)
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$this->load->view('auth/create-password', [
			'token' => $token
		]);
	}

	public function createTokenVerifiedPassword($token)
	{
		$this->form_validation->set_rules('company_code', 'Company Code', 'trim|required|numeric');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
		if ($this->form_validation->run() == TRUE) {
			$data = $this->input->post();
			$db = 'smartpm_' . $data['company_code'];
			if (verifyDB($db, TRUE)) {
				dbSelect($db);
				$this->user = new UserModel();
				$user = $this->user->getUserByPasswordToken($token);
				if ($user) {
					if ($token === $user->password_token) {
						$this->user->resetPassword($user, $data['password']);
						redirect('login');
					} else {
						$message = '<div class="error"><p>Invalid Password Token.</p></div>';
						$this->session->set_flashdata('errors', $message);
						redirect('create-password/' . $token);
					}
				} else {
					$message = '<div class="error"><p>Unable to find your token in our system.</p></div>';
					$this->session->set_flashdata('errors', $message);
					redirect('create-password/' . $token);
				}
			} else {
				$message = '<div class="error"><p>Unable to find database for entered Company Code.</p></div>';
				$this->session->set_flashdata('errors', $message);
				redirect('create-password/' . $token);
			}
		} else {
			$message = '<div class="error">' . validation_errors() . '</div>';
			$this->session->set_flashdata('errors', $message);
			redirect('create-password/' . $token);
		}
	}

	public function signup()
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$this->load->view('auth/register');
	}

	public function register()
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('conf_password', 'Confirm Password', 'trim|required|matches[password]');
		$this->form_validation->set_rules('email_id', 'Email ID', 'trim|required|valid_email');
		// $this->form_validation->set_rules('email_id', 'Email ID', 'trim|required|valid_email|is_unique[users.email_id]', [
		// 	'is_unique' => 'The user with this Email ID is already exist.'
		// ]);
		$this->form_validation->set_rules('office_phone', 'Office Phone', 'trim|numeric');
		$this->form_validation->set_rules('home_phone', 'Home Phone', 'trim|numeric');
		$this->form_validation->set_rules('cell_1', 'Cell 1', 'trim|numeric');
		$this->form_validation->set_rules('cell_2', 'Cell 2', 'trim|numeric');
		$this->form_validation->set_rules('company_email_id', 'Company Email ID', 'trim|valid_email');
		$this->form_validation->set_rules('company_email_id', 'Company Email ID', 'trim|required|valid_email|is_unique[companies.email_id]', [
			'is_unique' => 'The company with this Email ID is already exist.'
		]);
		$this->form_validation->set_rules('company_alt_email_id', 'Company Alt Email ID', 'trim|valid_email');
		if ($this->form_validation->run() == TRUE) {
			$userData = $this->input->post();
			$m_companyInsert = $this->m_company->insert([
				'name' => $userData['company_name'],
				'email_id' => $userData['company_email_id'],
				'alt_email_id' => $userData['company_alt_email_id'],
				'address' => $userData['company_address'],
				'city' => $userData['company_city'],
				'state' => $userData['company_state'],
				'zip' => $userData['company_zip']
			]);
			if ($m_companyInsert) {
				$this->m_company->updateCompanyCode($m_companyInsert);
				$company_code = 154236 + $m_companyInsert;
				$database = 'smartpm_' . $company_code;
				$this->m_emailCred->insert([
					'smtp_host' => '',
					'smtp_port' => '',
					'smtp_user' => '',
					'smtp_pass' => '',
					'smtp_crypto' => '',
					'company_id' => $m_companyInsert
				]);
				$this->m_twilioCred->insert([
					'account_sid' => '',
					'auth_token' => '',
					'twilio_number' => '',
					'company_id' => $m_companyInsert
				]);
				$this->m_database->insert([
					'name' => $database,
					'company_id' => $m_companyInsert
				]);
				if ($this->new_company->createDB($database)) {
					dbSelect($database);
					$this->user = new UserModel();
					$this->company = new CompanyModel();
					$this->admin_setting = new AdminSettingModel();
					$companyInsert = $this->company->insert([
						'name' => $userData['company_name'],
						'email_id' => $userData['company_email_id'],
						'alt_email_id' => $userData['company_alt_email_id'],
						'address' => $userData['company_address'],
						'city' => $userData['company_city'],
						'state' => $userData['company_state'],
						'zip' => $userData['company_zip']
					]);
					if ($companyInsert) {
						$message = '';
						$admin_setting = $this->admin_setting->insert([
							'company_id' => $companyInsert
						]);
						if (!$admin_setting) {
							$message .= '<div class="error" title="Error:" >Setting options not created. Please inform Admin!</div>';
						}
						$signup = $this->user->signup([
							'first_name' => $userData['first_name'],
							'last_name' => $userData['last_name'],
							'password' => $userData['password'],
							'email_id' => $userData['email_id'],
							'office_phone' => $userData['office_phone'],
							'home_phone' => $userData['home_phone'],
							'cell_1' => $userData['cell_1'],
							'cell_2' => $userData['cell_2'],
							'company_id' => $companyInsert
						]);
						if ($signup) {
							$user = $this->user->getUserById($signup);
							$token = $this->user->setVerificationToken($user);
							$this->notify->sendWelcomeUserNotification($user->email_id, ($userData['first_name'] . ' ' . $userData['last_name']));
							$this->notify->emailVerification($user->email_id, $company_code, $token);
							$message .= '<div class="error" title="Error:" style="color:white;background-color: green;border: green;">Registered Successfully. Check your email for email verification!</div>';
							$this->session->set_flashdata('errors', $message);
							redirect('login');
						} else {
							$message .= '<div class="error" title="Error:" >User not created. Please try again!</div>';
							$this->session->set_flashdata('errors', $message);
							redirect('signup');
						}
					} else {
						$message .= '<div class="error" title="Error:" >Unable to create your company. Please try again!</div>';
						$this->session->set_flashdata('errors', $message);
						redirect('signup');
					}
				} else {
					$this->new_company->createDB($database);
					$message .= '<div class="error" title="Error:" >Unable to create your company\'s database. Please try again!</div>';
					$this->session->set_flashdata('errors', $message);
					redirect('signup');
				}
			} else {
				$message .= '<div class="error" title="Error:" >Unable to create your company. Please try again!</div>';
				$this->session->set_flashdata('errors', $message);
				redirect('signup');
			}
		} else {
			$message = '<div class="error">' . validation_errors() . '</div>';
			$this->session->set_flashdata('errors', $message);
			redirect('signup');
		}
	}

	public function verification($company_code, $token)
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$db = 'smartpm_' . $company_code;
		if (verifyDB($db, TRUE)) {
			dbSelect($db);
			$this->user = new UserModel();
			$user = $this->user->getUserByVerificationToken($token);
			$message = '';
			if ($user) {
				$this->user->verifyUser($user);
				$message = '<div class="error" title="Error:" style="color:white;background-color: green;border: green;">Your Email ID is successfully verified. <br /> <a href="' . base_url('login') . '" style="color: white;">You can Login now.</a></div>';
			} else {
				$company_code = false;
				$message = '<div class="error"><p>Unable to find your token in our system.</p></div>';
			}
		} else {
			$company_code = false;
			$message = '<div class="error"><p>Unable to find your token in our system.</p></div>';
		}
		$this->load->view('auth/verification', [
			'message' => $message,
			'company_code' => $company_code
		]);
	}

	public function forgotCompanyCode()
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$this->load->view('auth/forgot-company-code');
	}

	public function sendCompanyCode()
	{
		$this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email');
		if ($this->form_validation->run() == TRUE) {
			$data = $this->input->post();
			$company = $this->m_company->getCompanyByEmailId($data['email']);
			if ($company) {
				$this->notify->sendCompanyCode($company->email_id, $company->company_code);
				$message = '<div class="error" title="Error:" style="color:white;background-color: green;border: green;">Your company code successfully sent to your company\'s Email ID.</div>';
				$this->session->set_flashdata('errors', $message);
				redirect('forgot-company-code');
			} else {
				$message = '<div class="error"><p>Unable to find your company\'s email in our system.</p></div>';
				$this->session->set_flashdata('errors', $message);
				redirect('forgot-company-code');
			}
		} else {
			$message = '<div class="error">' . validation_errors() . '</div>';
			$this->session->set_flashdata('errors', $message);
			redirect('forgot-company-code');
		}
	}

	public function resendVerificationEmail($company_code, $email)
	{
		if ($this->session->logged_in) {
			redirect();
			die();
		}
		$db = 'smartpm_' . $company_code;
		if (verifyDB($db, TRUE)) {
			dbSelect($db);
			$this->user = new UserModel();
			$user = $this->user->getUserByEmailId($email);
			if (!($user->verification_token == null || empty($user->verification_token))) {
				$this->notify->emailVerification($user->email_id, $company_code, $user->verification_token);
				$message = '<div class="error"><p>Verification Email Resent.</p></div>';
				$this->session->set_flashdata('errors', $message);
			} else {
				$message = '<div class="error"><p>Your account is already verified.</p></div>';
				$this->session->set_flashdata('errors', $message);
			}
		} else {
			$message = '<div class="error"><p>Unable to find your token in our system.</p></div>';
			$this->session->set_flashdata('errors', $message);
		}
		redirect('login');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
