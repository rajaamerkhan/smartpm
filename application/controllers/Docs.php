<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Docs extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper(['form', 'security', 'cookie']);
		$this->load->library(['session', 'image_lib']);
		$this->load->model(['JobsDocModel', 'Common_model']);
		$this->doc = new JobsDocModel();
	}
	public function index($job_id)
	{
		$params = array();
		$params['job_id'] = $job_id;
		$params['is_active'] = 1;
		$docs = $this->Common_model->get_all_where('jobs_doc', $params);
		$this->load->view('header', ['title' => 'Add Doucment']);
		$this->load->view('doc/index', ['docs' => $docs, 'jobid' => $job_id]);
		$this->load->view('footer');
	}

	public function ajaxupload_jobdoc()
	{
		if (is_array($_FILES) && !empty($_FILES['doc'])) {
			$doc = array();
			$doc_name = array();
			$i = 0;
			foreach ($_FILES['doc']['name'] as $key => $filename) {
				$file_name = explode(".", $filename);
				$allowed_extension = array("pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "txt", "zip");
				if (in_array($file_name[1], $allowed_extension)) {
					if ($file_name[1] != 'zip') {
						$new_name = $_FILES["doc"]["name"][$key];
						$sourcePath = $_FILES["doc"]["tmp_name"][$key];
						$targetPath = "assets/job_doc/" . $new_name;
						move_uploaded_file($sourcePath, $targetPath);
						$doc[$i] = $_FILES["doc"]["name"][$key];
						$i++;
					} else {
						$targetPath = 'assets/job_doc/';
						$location = $targetPath . $filename;
						if (move_uploaded_file($_FILES['doc']['tmp_name'][$key], $location)) {
							$zip = new ZipArchive;
							if ($zip->open($location)) {
								$zip->extractTo($targetPath);
								$dir = trim($zip->getNameIndex(0), '/');
								$destinationFolder = $targetPath . "$dir";

								if (!is_dir($destinationFolder)) {
									mkdir($targetPath . "/$file_name[0]");
									$zip->extractTo($targetPath . "/$file_name[0]");
									$zip->close();
									$files = scandir($targetPath . "/$file_name[0]");

									foreach ($files as $file) {
										$tmp = explode(".", $file);
										$file_ext = end($tmp);
										$allowed_ext = array("pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "txt");
										$new_name = '';
										if (in_array($file_ext, $allowed_ext)) {
											$new_name = md5(rand()) . '.' . $file_ext;
											copy($targetPath . "/$file_name[0]" . '/' . $file, $targetPath . $new_name);
											unlink($targetPath . "/$file_name[0]" . '/' . $file);
										}
										if ($new_name != '') {
											$doc[$i] = $new_name;
											$i++;
										}
									}
									unlink($location);
									$this->rrmdir($targetPath . "/$file_name[0]");
								} else {
									$dir = trim($zip->getNameIndex(0), '/');
									$zip->close();
									$files = scandir($targetPath . $dir);

									foreach ($files as $file) {
										$tmp = explode(".", $file);
										$file_ext = end($tmp);
										$allowed_ext = array("pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "txt");
										$new_name = '';
										if (in_array($file_ext, $allowed_ext)) {
											$new_name = md5(rand()) . '.' . $file_ext;
											copy($targetPath . $dir . '/' . $file, $targetPath . $new_name);
											unlink($targetPath . $dir . '/' . $file);
										}
										if ($new_name != '') {
											$doc[$i] = $new_name;
											$i++;
										}
									}
									unlink($location);
									$this->rrmdir($targetPath . $dir);
								}
							}
						}
					}
				}
			}
			echo json_encode($doc);
		}
	}

	public function ajaxsave_jobdoc()
	{
		$posts = $this->input->post();
		$data = json_decode($posts['name'], true);
		for ($i = 0; $i < count($data); $i++) {
			$search = '.' . strtolower(pathinfo($data[$i], PATHINFO_EXTENSION));
			$trimmed = str_replace($search, '', $data[$i]);
			$params = array();
			$params['job_id'] = $posts['id'];
			$params['doc_name'] = $data[$i];
			$params['name'] = $trimmed;
			$params['entry_date'] = date('Y-m-d h:i:s');
			$params['is_active'] = TRUE;
			$this->db->insert('jobs_doc', $params);
			$insertId = $this->db->insert_id();
			$total = $this->doc->getCount(['is_active' => 1, 'job_id' => $posts['id']]);
			echo '<tr id="doc' . $insertId . '"><td style="width: 30px">' . $total . '</td><td style="width: 30px"><i class="del-doc pe-7s-trash" id="' . $insertId . '"></i></td><td style="width: 30px"><a href="' . base_url() . 'assets/job_doc/' . $data[$i] . '"  target="_blank"><i class="pe-7s-news-paper" style="font-size: 30px"></i></a></td><td><span class="' . $insertId . '"><i class="del-edit pe-7s-note"></i></span></td><td><p id="docp' . $insertId . '">' . $trimmed . '</p><input style="width: 100%;display:none" name="' . $insertId . '" type="text"  class="docname" id="doctext' . $insertId . '" /></td><td >' . $data[$i] . '</td></tr>';
		}
	}

	public function deletedoc()
	{
		$posts = $this->input->post();
		$this->db->query("UPDATE jobs_doc SET is_active=0 WHERE id='" . $posts['id'] . "'");
		return true;
	}

	public function updatedocname()
	{
		$posts = $this->input->post();
		$name = $posts['na'];
		$this->db->set('name', $name);
		$this->db->where('id', $posts['id']);
		$this->db->update('jobs_doc');
		return true;
		echo $name;
	}

	function rrmdir($dir)
	{
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir . "/" . $object) == "dir")
						$this->rrmdir($dir . "/" . $object);
					else unlink($dir . "/" . $object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}
}
