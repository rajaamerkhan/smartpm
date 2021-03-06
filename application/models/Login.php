<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Model
{
    private $table = 'users';

    function __construct()
    {
        parent::__construct();
    }

    /*
     * Get user 
     */
    function get_user($table, $cols, $condition)
    {
        return $this->db->select($cols)
            ->get_where($table, $condition)
            ->row_array();
    }

    public function getUserList($select = 'id, username, fullname')
    {
        $this->db->select($select);
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }
}
