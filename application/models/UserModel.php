<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserModel extends CI_Model
{
	private $table = 'users';

	public static $level_super_admin = 1;
	public static $level_admin = 2;
	public static $level_manager = 3;
	public static $level_team_leader = 4;
	public static $level_user = 5;
	public static $level_non_user = 6;

	private static $levels = [
		// 1 => 'Super Admin',
		2 => 'Admin',
		3 => 'Manager',
		4 => 'Team Leader',
		5 => 'User',
		6 => 'Non User'
	];
	private static $notifications = [
		1 => 'Off',
		2 => 'Text Only',
		3 => 'Email Only',
		4 => 'Both'
	];

	public function signup($data)
	{
		$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
		$data['level'] = self::$level_admin;
		return $this->insert($data);
	}

	public function authenticate($email_id, $password)
	{
		$this->db->where([
			'email_id' => $email_id,
			'is_deleted' => FALSE
		]);
		$user = $this->db->get($this->table)->row();
		if ($user != NULL) {
			if (password_verify($password, $user->password)) {
				return $user;
			} else {
				return false;
			}
		}
	}

	public function setVerificationToken($user)
	{
		$token = $user->username . '_' . rand() . '_' . $user->email_id . '_' . rand() . '_' . $user->notifications . '_' . rand() . '_' . time();
		$final_token = md5($user->email_id) . time() . $user->id . hash('sha256', $token) . md5('user_verification') . rand();
		$this->update($user->id, [
			'verification_token' => $final_token
		]);
		return $final_token;
	}

	public function setPasswordToken($user)
	{
		$token = $user->username . '_' . rand() . '_' . $user->email_id . '_' . rand() . '_' . $user->notifications . '_' . rand() . '_' . time();
		$final_token = md5($user->email_id) . time() . $user->id . hash('sha256', $token) . md5('password_reset') . rand();
		$this->db->set('token_expiry', 'DATE_ADD(NOW(), INTERVAL 1 HOUR)', FALSE);
		$this->update($user->id, [
			'password_token' => $final_token
		]);
		return $final_token;
	}

	public function resetPassword($user, $password)
	{
		$this->db->where('is_deleted', FALSE);
		$password = password_hash($password, PASSWORD_BCRYPT);
		$this->update($user->id, [
			'password' => $password,
			'password_token' => '',
			'token_expiry' => ''
		]);
	}

	public function verifyUser($user)
	{
		$this->db->where('is_deleted', FALSE);
		$this->update($user->id, [
			'verification_token' => '',
			'is_active' => TRUE
		]);
	}

	public function allUsers()
	{
		$this->db->select("
			users.*,
			user_cell_notif_suffixs.cell_provider as cell_1_provider_name,
			user_cell_notif_suffixs.suffix as cell_1_provider_suffix
		");
		$this->db->from($this->table);
		$this->db->join('user_cell_notif_suffixs', 'users.cell_1_provider=user_cell_notif_suffixs.id', 'left');
		$this->db->where('users.is_deleted', FALSE);
		$this->db->order_by('users.id', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	public function getCount()
	{
		$this->db->where('is_deleted', FALSE);
		return $this->db->count_all_results($this->table);
	}

	public function getUserById($id)
	{
		$this->db->select("
			users.*,
			user_cell_notif_suffixs.cell_provider as cell_1_provider_name,
			user_cell_notif_suffixs.suffix as cell_1_provider_suffix
		");
		$this->db->from($this->table);
		$this->db->join('user_cell_notif_suffixs', 'users.cell_1_provider=user_cell_notif_suffixs.id', 'left');
		$this->db->where([
			'users.id' => $id,
			'users.is_deleted' => FALSE
		]);
		$query = $this->db->get();
		$result = $query->first_row();
		return $result ? $result : false;
	}

	public function getUserByEmailId($email_id)
	{
		$this->db->from($this->table);
		$this->db->where([
			'email_id' => $email_id,
			'is_deleted' => FALSE
		]);
		$query = $this->db->get();
		$result = $query->first_row();
		return $result ? $result : false;
	}

	public function getUserByPasswordToken($token)
	{
		$this->db->from($this->table);
		$this->db->where([
			'password_token' => $token,
			'is_deleted' => FALSE
		]);
		$query = $this->db->get();
		$result = $query->first_row();
		return $result ? $result : false;
	}

	public function getUserByVerificationToken($token)
	{
		$this->db->from($this->table);
		$this->db->where([
			'verification_token' => $token,
			'is_deleted' => FALSE
		]);
		$query = $this->db->get();
		$result = $query->first_row();
		return $result ? $result : false;
	}

	public function insert($data)
	{
		$data['username'] = $this->genUserName($data['first_name'], $data['last_name']);
		$insert = $this->db->insert($this->table, $data);
		return $insert ? $this->db->insert_id() : $insert;
	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		$update = $this->db->update($this->table, $data);
		return $update;
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table, [
			'is_deleted' => TRUE
		]);
	}

	public function getUserList($select = "id, username, CONCAT(first_name, ' ', last_name) AS name")
	{
		$this->db->select($select);
		$this->db->from($this->table);
		$query = $this->db->get();
		return $query->result();
	}

	public function getUserIdArrByUserNames($usernames)
	{
		$this->db->select('id');
		$this->db->where_in('username', $usernames);
		$this->db->from($this->table);
		$query = $this->db->get();
		return array_column($query->result_array(), 'id');
	}

	public function getEmailIdArrByUserIds($userIds)
	{
		$this->db->select('email_id');
		$this->db->where_in('id', $userIds);
		$this->db->from($this->table);
		$query = $this->db->get();
		return array_column($query->result_array(), 'email_id');
	}

	public function getMobEmailIdArrByUserIds($userIds)
	{
		$this->db->select("CONCAT(users.cell_1, user_cell_notif_suffixs.suffix) AS mob_email_id");
		$this->db->from($this->table);
		$this->db->join('user_cell_notif_suffixs', 'users.cell_1_provider=user_cell_notif_suffixs.id', 'left');
		$this->db->where_in('users.id', $userIds);
		$this->db->where('users.cell_1 !=', null);
		$this->db->where('users.cell_1 !=', '');
		$this->db->where('users.cell_1_provider !=', null);
		$this->db->where('users.cell_1_provider !=', '');
		$query = $this->db->get();
		return array_column($query->result_array(), 'mob_email_id');
	}

	public function getPhoneArrByUserIds($userIds)
	{
		$this->db->select("cell_1 AS phone");
		$this->db->from($this->table);
		$this->db->where_in('id', $userIds);
		$this->db->where('cell_1 !=', null);
		$this->db->where('cell_1 !=', '');
		$query = $this->db->get();
		return array_column($query->result_array(), 'phone');
	}

	public function get_crm_data($table, $cols, $condition)
	{
		return $this->db->select($cols)
			->get_where($table, $condition)
			->row_array();
	}

    public function search($keywords)
    {
        if (count($keywords) <= 0) {
            return [];
		}
		$this->db->select("id, first_name, last_name, username, email_id, office_phone, home_phone, cell_1, cell_2");
        $this->db->from($this->table);
        $this->db->where([
            'is_deleted' => FALSE
        ]);
        $this->db->group_start();
        foreach ($keywords as $k) {
            $this->db->or_like('first_name', $k);
            $this->db->or_like('last_name', $k);
            $this->db->or_like('username', $k);
            $this->db->or_like('email_id', $k);
            $this->db->or_like('office_phone', $k);
            $this->db->or_like('home_phone', $k);
            $this->db->or_like('cell_1', $k);
            $this->db->or_like('cell_2', $k);
        }
        $this->db->group_end();
        $this->db->order_by('created_at', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function search_sales_rep($keywords)
    {
        if (count($keywords) <= 0) {
            return [];
		}
		$this->db->select("id, concat(first_name, ' ', last_name, ' <', email_id, '>') text");
        $this->db->from($this->table);
        $this->db->where([
            'is_deleted' => FALSE,
            'level' => 5,
        ]);
        $this->db->group_start();
        foreach ($keywords as $k) {
            $this->db->or_like('first_name', $k);
            $this->db->or_like('last_name', $k);
            $this->db->or_like('username', $k);
            $this->db->or_like('email_id', $k);
            $this->db->or_like('office_phone', $k);
            $this->db->or_like('home_phone', $k);
            $this->db->or_like('cell_1', $k);
            $this->db->or_like('cell_2', $k);
        }
        $this->db->group_end();
        $this->db->order_by('created_at', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

	/**
	 * Private Methods
	 */
	private function genUserName($first_name, $last_name)
	{
		$userName = strtolower($first_name . '_' . $last_name);
		$this->db->like('username', $userName);
		$count = $this->db->count_all_results($this->table);
		return $userName . ($count > 0 ? ('_' . ($count + 1)) : '');
	}

	/**
	 * Static Methods
	 */
	public static function levelToStr($level)
	{
		return (self::$levels[$level]) ? self::$levels[$level] : $level;
	}

	public static function getLevels()
	{
		return self::$levels;
	}

	public static function activeToStr($active)
	{
		return $active ? 'Active' : 'Inactive';
	}

	public static function notificationsToStr($id)
	{
		return (self::$notifications[$id]) ? self::$notifications[$id] : $id;
	}

	public static function getNotifications()
	{
		return self::$notifications;
	}
}
