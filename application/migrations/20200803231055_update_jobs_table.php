<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Update_jobs_table extends CI_Migration
{
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
	}

	public function up()
	{
		$fields = [
			'sales_rep_id' => [
				'type' => 'INT',
				'constraint' => 10,
				'after' => 'permit_status',
			]
		];

		$this->dbforge->add_column('jobs', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('jobs', 'sales_rep_id');
	}
}
