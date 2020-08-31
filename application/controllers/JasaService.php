<?php

defined('BASEPATH') or exit('No direct script access allowed');

class JasaService extends CI_Controller
{

	protected $page_header = 'Jasa Service Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model('JasaService_model', 'jasaservice');
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
		$data['panel_heading'] = 'Jasa Service List';
		$data['page']         = '';

		$this->template->backend('jasaservice_v', $data);
	}

	public function get_jasaservice()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}  

		$list = $this->jasaservice->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
			$id = $field->JasaServiceID;

			$url_view   = 'view_data(' . $id . ');';
			$url_update = 'update_data(' . $id . ');';
			$url_delete = 'delete_data(' . $id . ');';

			$no++;
			$row = array();
			$row[] = ajax_button($url_view, $url_update, $url_delete);
			$row[] = $no;
			$row[] = $field->NamaService;
			$row[] = "Rp " . number_format($field->BiayaService);

			$data[] = $row;
		}

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->jasaservice->count_rows(),
			"recordsFiltered" => $this->jasaservice->count_filtered(),
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

		$id = $this->input->post('JasaServiceID');

		$query = $this->jasaservice->where('JasaServiceID', $id)->get();
		if ($query) {
			$data = array(
				'JasaServiceID' => $query->JasaServiceID,
				'NamaService' => $query->NamaService,
				'BiayaService' => $query->BiayaService
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
		if ($this->input->post('JasaServiceID')) {
			$id      = $this->input->post('JasaServiceID');
			$query   = $this->jasaservice->where('JasaServiceID', $id)->get();
			if ($query) {
				$row = array(
					'JasaServiceID'    => $query->JasaServiceID,
					'NamaService'   => $query->NamaService,
					'BiayaService'   => $query->BiayaService
				);
			}
			$row = (object) $row;
		}

		$data = array(
			'hidden' => form_hidden('JasaServiceID', !empty($row->JasaServiceID) ? $row->JasaServiceID : ''),
			'NamaService' => form_input(array('name' => 'NamaService', 'id' => 'NamaService', 'class' => 'form-control', 'value' => !empty($row->NamaService) ? $row->NamaService : '')),
			'BiayaService' => form_input(array('name' => 'BiayaService', 'id' => 'BiayaService', 'class' => 'form-control', 'value' => !empty($row->BiayaService) ? $row->BiayaService : ''))
		);

		echo json_encode($data);
	}


	public function save_jasaservice()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}  

		$rules = array(
			'insert' => array(
				array('field' => 'NamaService', 'label' => 'NamaService', 'rules' => 'trim|required|max_length[100]'),
				array('field' => 'BiayaService', 'label' => 'BiayaService', 'rules' => 'trim|required|max_length[100]')
			),
			'update' => array(
				array('field' => 'JasaServiceID', 'label' => 'JasaServiceID', 'rules' => 'required|max_length[5]'),
				array('field' => 'NamaService', 'label' => 'NamaService', 'rules' => 'trim|required|max_length[100]'),
				array('field' => 'BiayaService', 'label' => 'BiayaService', 'rules' => 'trim|required|max_length[100]')
			)
		);

		$row = array(
			'NamaService' => $this->input->post('NamaService'),
			'BiayaService' => $this->input->post('BiayaService')
		);

		$code = 0;

		if ($this->input->post('JasaServiceID') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->jasaservice->insert($row);

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

				$id = $this->input->post('JasaServiceID');

				$this->jasaservice->where('JasaServiceID', $id)->update($row);

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

		$id = $this->input->post('JasaServiceID');

		$this->jasaservice->where('JasaServiceID', $id)->delete();

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
