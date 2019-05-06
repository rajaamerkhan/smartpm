<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TaskJobTagsModel extends CI_Model
{
    private $table = 'task_job_tags';

    public function insertByJobArr($jobs, $task_id)
    {
        if (is_array($jobs) && count($jobs) > 0) {
            $data = $this->buildByUserArr($jobs, $task_id);
            $insert = $this->db->insert_batch($this->table, $data);
            return $insert;
        } else {
            return false;
        }
    }

    private function buildByUserArr($jobs, $task_id)
    {
        $return = [];
        foreach ($jobs as $job) {
            $return[] = [
                'task_id' => $task_id,
                'job_id' => $job
            ];
        }
        return $return;
    }
}