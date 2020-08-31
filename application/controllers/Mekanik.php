<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mekanik extends CI_Controller
{

	protected $page_header = 'Mekanik Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model('Mekanik_model', 'mekanik');
		$this->load->library(array('ion_auth', 'form_validation', 'template'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}  

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Mekanik List';
		$data['page']         = '';

		$this->template->backend('mekanik_v', $data);
	}

	public function get_mekanik()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}  

		$list = $this->mekanik->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
			$id = $field->MekanikID;

			$url_view   = 'view_data(' . $id . ');';
			$url_update = 'update_data(' . $id . ');';
			$url_delete = 'delete_data(' . $id . ');';

			$no++;
			$row = array();
			$row[] = ajax_button($url_view, $url_update, $url_delete);
			$row[] = $no;
			$row[] = $field->NamaMekanik;

			$data[] = $row;
		}

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->mekanik->count_rows(),
			"recordsFiltered" => $this->mekanik->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function view()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}  

		$id = $this->input->post('MekanikID');

		$query = $this->mekanik->where('MekanikID', $id)->get();
		if ($query) {
			$data = array(
				'MekanikID' => $query->MekanikID,
				'NamaMekanik' => $query->NamaMekanik
			);
		}

		echo json_encode($data);
	}

	public function form_data()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}  

		$row = array();
		if ($this->input->post('MekanikID')) {
			$id      = $this->input->post('MekanikID');
			$query   = $this->mekanik->where('MekanikID', $id)->get();
			if ($query) {
				$row = array(
					'MekanikID'    => $query->MekanikID,
					'NamaMekanik'   => $query->NamaMekanik
				);
			}
			$row = (object) $row;
		}

		$data = array(
			'hidden' => form_hidden('MekanikID', !empty($row->MekanikID) ? $row->MekanikID : ''),
			'NamaMekanik' => form_input(array('name' => 'NamaMekanik', 'id' => 'NamaMekanik', 'class' => 'form-control', 'value' => !empty($row->NamaMekanik) ? $row->NamaMekanik : ''))
		);

		echo json_encode($data);
	}


	public function save_mekanik()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}  

		$rules = array(
			'insert' => array(
				array('field' => 'NamaMekanik', 'label' => 'Nama Mekanik', 'rules' => 'trim|required|max_length[100]')
			),
			'update' => array(
				array('field' => 'MekanikID', 'label' => 'Mekanik ID', 'rules' => 'required|max_length[5]'),
				array('field' => 'NamaMekanik', 'label' => 'Nama Mekanik', 'rules' => 'trim|required|max_length[100]')
			)
		);

		$row = array(
			'NamaMekanik' => $this->input->post('NamaMekanik')
		);

		$code = 0;

		if ($this->input->post('MekanikID') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->mekanik->insert($row);

				$error =  $this->db->error();
				if ($error['code'] <> 0) {
					$code = 1;
					$notifications = $error['code'] . ' : ' . $error['message'];
				} else {
					$notifications = 'Success Insert Data';
				}
			} else {
				$code = 1;
				$notifications = validation_errors('<p>', '</p>');
			}
		} else {

			$this->form_validation->set_rules($rules['update']);

			if ($this->form_validation->run() == true) {

				$id = $this->input->post('MekanikID');

				$this->mekanik->where('MekanikID', $id)->update($row);

				$error =  $this->db->error();
				if ($error['code'] <> 0) {
					$code = 1;
					$notifications = $error['code'] . ' : ' . $error['message'];
				} else {
					$notifications = 'Success Update Data';
				}
			} else {
				$code = 1;
				$notifications = validation_errors('<p>', '</p>');
			}
		}

		$notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);

		echo json_encode(array('message' => $notifications, 'code' => $code));
	}

	public function delete()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}  

		$code = 0;

		$id = $this->input->post('MekanikID');

		$this->mekanik->where('MekanikID', $id)->delete();

		$error =  $this->db->error();
		if ($error['code'] <> 0) {
			$code = 1;
			$notifications = $error['code'] . ' : ' . $error['message'];
		} else {
			$notifications = 'Success Delete Data';
		}

		$notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);

		echo json_encode(array('message' => $notifications, 'code' => $code));
	}
}
