<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notify
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('email');
        $this->CI->load->model(['M_EmailCredModel', 'M_TwilioCredModel']);

        $this->m_emailCred = new M_EmailCredModel();
        $this->m_twilioCred = new M_TwilioCredModel();

        $this->twilioClient = false;
        $this->logoUrl = 'https://smartpm.app/assets/img/logo.png';
        
        if (isset($this->session->logoUrl) && $this->session->logoUrl != '') {
            $this->logoUrl = base_url('assets/company_photo/' . $this->session->logoUrl);
        }

        if (isset($this->CI->session->company_code)) {
            $smtpSettings = $this->m_emailCred->getSMTPSettings($this->CI->session->company_code);
            $twilioSettings = $this->m_twilioCred->getTwilioSettings($this->CI->session->company_code);
            if ($twilioSettings && !empty($twilioSettings->account_sid) && !empty($twilioSettings->auth_token)) {
                $this->twilioClient = new Twilio\Rest\Client($twilioSettings->account_sid, $twilioSettings->auth_token);
                $this->twilio_number = $twilioSettings->twilio_number;
            }
        }

        $this->CI->email->initialize([
            'protocol' => 'smtp',
            'smtp_crypto' => (isset($smtpSettings) && !empty($smtpSettings->smtp_host)) ? $smtpSettings->smtp_crypto : getenv('EMAIL_SMTP_CRYPTO'),
            'smtp_host' => (isset($smtpSettings) && !empty($smtpSettings->smtp_host)) ? $smtpSettings->smtp_host : getenv('EMAIL_SMTP_HOST'),
            'smtp_port' => intval((isset($smtpSettings) && $smtpSettings->smtp_port != 0) ? $smtpSettings->smtp_port : getenv('EMAIL_SMTP_PORT')),
            'smtp_user' => (isset($smtpSettings) && !empty($smtpSettings->smtp_user)) ? $smtpSettings->smtp_user : getenv('EMAIL_SMTP_USER'),
            'smtp_pass' => (isset($smtpSettings) && !empty($smtpSettings->smtp_pass)) ? $smtpSettings->smtp_pass : getenv('EMAIL_SMTP_PASS'),
            'mailtype' => 'html'
        ]);
        $this->CI->email->set_newline("\r\n");
        $this->CI->email->from(((isset($smtpSettings) && !empty($smtpSettings->smtp_user)) ? $smtpSettings->smtp_user : getenv('EMAIL_SMTP_USER')), 'SmartPM Notification');
    }

    public function resetPassword($email, $token)
    {
        $this->CI->email->to($email);
        $this->CI->email->subject('Reset Password - SmartPM');
        $html_message = $this->CI->load->view('template/email/reset-password.php', [
            'token' => $token
        ], true);
        $this->CI->email->message($html_message);
        if (!$this->CI->email->send(false)) {
            $this->CI->email->print_debugger();
            die();
        }
    }

    public function createPassword($email, $token, $logoUrl)
    {
        $this->CI->email->to($email);
        $this->CI->email->subject('Create Password - SmartPM');
        $html_message = $this->CI->load->view('template/email/create-password.php', [
            'token' => $token,
            'logoUrl' => $logoUrl
        ], true);
        $this->CI->email->message($html_message);
        $this->CI->email->send();
    }

    public function emailVerification($email, $company_code, $token)
    {
        $this->CI->email->to($email);
        $this->CI->email->subject('Email Verification - SmartPM');
        $html_message = $this->CI->load->view('template/email/email-verification.php', [
            'company_code' => $company_code,
            'token' => $token
        ], true);
        $this->CI->email->message($html_message);
        $this->CI->email->send();
    }

    public function sendCompanyCode($email, $company_code)
    {
        $this->CI->email->to($email);
        $this->CI->email->subject('Forgot Company Code - SmartPM');
        $html_message = $this->CI->load->view('template/email/forgot-company-code.php', [
            'company_code' => $company_code
        ], true);
        $this->CI->email->message($html_message);
        $this->CI->email->send();
    }

    public function sendNoteTagNotification($email, $task_name, $note, $link)
    {
        $this->CI->email->to($email);
        $this->CI->email->subject('You have been tagged in a Note - SmartPM');
        $html_message = $this->CI->load->view('template/email/note-tag-notification.php', [
            'logoUrl' => $this->logoUrl,
            'task_name' => $task_name,
            'note' => $note,
            'link' => $link
        ], true);
        $this->CI->email->message($html_message);
        $this->CI->email->send();
    }

    public function sendNoteTagNotificationMob($phone, $task_name, $note, $link)
    {
        if ($this->twilioClient) {
            $this->twilioClient->messages->create(
                $phone,
                [
                    'from' => $this->twilio_number,
                    'body' => 'Smartpm.app: ' . $task_name . ' (' . $link . ') "' . $note . '"'
                ]
            );
        }
    }

    public function sendTaskTagNotification($email, $task_id, $task_name, $note, $link)
    {
        $this->CI->email->to($email);
        $this->CI->email->subject('You have been tagged in a Task - SmartPM');
        $html_message = $this->CI->load->view('template/email/task-tag-notification.php', [
            'logoUrl' => $this->logoUrl,
            'task_id' => $task_id,
            'task_name' => $task_name,
            'note' => $note,
            'link' => $link
        ], true);
        $this->CI->email->message($html_message);
        $this->CI->email->send();
    }

    public function sendTaskTagNotificationMob($phone, $task_id, $task_name, $note, $link)
    {
        if ($this->twilioClient) {
            $this->twilioClient->messages->create(
                $phone,
                [
                    'from' => $this->twilio_number,
                    'body' => 'Smartpm.app: #' . $task_id . ' ' . $task_name . ' (' . $link . ') "' . $note . '"'
                ]
            );
        }
    }

    public function sendTaskAssignNotification($email, $task_id, $task_name)
    {
        $this->CI->email->to($email);
        $this->CI->email->subject('A Task has been assign to You - SmartPM');
        $html_message = $this->CI->load->view('template/email/task-assign-notification.php', [
            'logoUrl' => $this->logoUrl,
            'task_id' => $task_id,
            'task_name' => $task_name
        ], true);
        $this->CI->email->message($html_message);
        $this->CI->email->send();
    }

    public function sendLeadAssignNotification($email, $lead_id, $lead_name, $link)
    {
        $this->CI->email->to($email);
        $this->CI->email->subject("You have a new Lead: {$lead_name}");
        $html_message = $this->CI->load->view('template/email/lead-assign-notification.php', [
            'logoUrl' => $this->logoUrl,
            'lead_id' => $lead_id,
            'lead_name' => $lead_name,
            'link' => $link,
        ], true);
        $this->CI->email->message($html_message);
        $this->CI->email->send();
    }

	public function sendLeadAssignNotificationMob($phone, $lead_id, $lead_name, $link)
	{
		if ($this->twilioClient) {
			$this->twilioClient->messages->create(
				$phone,
				[
					'from' => $this->twilio_number,
					'body' => "Smartpm.app: # {$lead_id} {$lead_name} ({$link})",
				]
			);
		}
	}

    public function sendWelcomeUserNotification($email, $name, $logoUrl = false)
    {
        $this->CI->email->to($email);
        $this->CI->email->subject('Welcome to SmartPM');
        $html_message = $this->CI->load->view('template/email/welcome-user-notification.php', [
            'name' => $name,
            'logoUrl' => $logoUrl
        ], true);
        $this->CI->email->message($html_message);
        $this->CI->email->send();
    }
}
