<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	protected $page_header = 'Daftar Restok Barang';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Dashboard_model'=>'dashboard'));
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
		$data['panel_heading'] = 'Item List';
		$data['page']         = '';

		$this->template->backend('dashboard_v', $data);
	}

	public function get_dashboard()
	{
		if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $list = $this->dashboard->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->ItemID;
         
         $no++;
         $row = array();
         $row[] = $no;
         $row[] = $field->NamaItem;
         $row[] = $field->Deskripsi;
         $row[] = $field->Stok;
         $row[] = "Rp " . number_format($field->HargaBeli);
         $row[] = "Rp " . number_format($field->HargaJual);
         $row[] = $field->Terjual;
         $row[] = $field->ReOrder;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->dashboard->count_rows(),
         "recordsFiltered" => $this->dashboard->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
	}
}
