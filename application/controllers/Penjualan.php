<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {

	protected $page_header = 'Penjualan Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('ServiceTiket_model' => 'servicetiket', 'SubService_model' => 'subservice', 'Kendaraan_model' => 'kendaraan', 'kustomer_model' => 'kustomer', 'Item_model' => 'item', 'Mekanik_model' => 'mekanik', 'Service_model' => 'service'));
		$this->load->library(array('ion_auth', 'form_validation', 'template','pdf'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{  

		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		} 
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}


      // $id = $this->input->post('transaksiID');
      // $query1 = $this->subtransaksi->where('transaksiID', $id)->get_all();

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'service List';
		$data['page']         = '';
      // $data['sub'] = $query1;

		$this->template->backend('service_v', $data);
	}

	public function get_service()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}  

		$list = $this->service->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) { 
			$id = $field->ServiceID;

			$url_view   = 'view_data('.$id.');';
			$url_update = 'update_data('.$id.');';
			$url_delete = 'delete_data('.$id.');';

			$kustomer = $this->kustomer->where('KustomerID', $field->KustomerID)->get();
			$kendaraan = $this->kendaraan->where('KendaraanID', $field->KendaraanID)->get();

			$no++;
			$row = array();
			$row[] = '<button type="button" name="id" class="btn btn-primary btn-sm" onClick="view_data('.$id.');">Detail</button>';
			$row[] = $no;
			$row[] = $kustomer->Nama;
			$row[] = $kendaraan->NoPolisi;
			$row[] = $field->NamaMekanik;
			$row[] = $field->NamaService;
			$row[] = "Rp " . number_format($field->TotalHarga);

			$data[] = $row;
		}
		
		

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->service->count_rows(),
			"recordsFiltered" => $this->service->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function cetak()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin())
		{
			redirect('auth/login', 'refresh');
		} 


		$pdf = new FPDF('P', 'mm', 'A4'); //L = lanscape P= potrait
		// membuat halaman baru
		$pdf->AddPage();
		// setting jenis font yang akan digunakan
		$pdf->SetFont('Arial', 'B', 24);
		$ya = 44;
		// mencetak string 
		$pdf->Cell(15, 7, 'Sofia Cell', 0, 1);
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(15, 7, 'Jl. Mandungan No 57 Srimartani, Piyungan, Bantul', 0, 1);
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(15, 7, 'Telp :   +62 813 3818 0622', 0, 1);
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10, 7, '', 0, 1);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(15, 8, 'No', 1, 0, 'C');
		$pdf->Cell(60, 8, 'No Transaksi', 1, 0);
		$pdf->Cell(60, 8, 'Tanggal Transaksi', 1, 0);
		$pdf->Cell(50, 8, 'Total Bayar', 1, 1);
		$pdf->SetFont('Arial', '', 10);

		if(!empty($this->input->post('transaksiID')))
		{
			$penjualan = $this->penjualan->where('transaksiID', $this->input->post('transaksiID'))->get_all();
		}
		elseif (!empty($this->input->post('start_date')) && !empty($this->input->post('end_date')))
		{
			$where = array(
				'DATE(created_at) >=' => $this->input->post('start_date'),
				'DATE(created_at) <=' => $this->input->post('end_date')
			);

			$penjualan = $this->penjualan->where($where)->get_all();
		}
		else
		{
			$penjualan = $this->penjualan->as_object()->get_all();
		}

		$no = 1;
		foreach($penjualan as $row)
		{
			$pdf->Cell(15, 8, $no++, 1, 0, 'C');
			$pdf->Cell(60, 8, $row->transaksiID, 1, 0);
			$pdf->Cell(60, 8, $row->created_at, 1, 0);
			$pdf->Cell(50, 8, "Rp ".number_format($row->totalharga), 1, 1);
		}

		$kode = date('ymdhis');
		

		$pdf->Output("Transaksi". $kode.".pdf","I");

		redirect('tempo', 'refresh');
	}

	public function view()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		} 

		$id = $this->input->post('ServiceID');

		$query = $this->service
		->with_jasaservice('fields:NamaService, BiayaService')
		->where('ServiceID', $id)->get();
		$query1 = $this->subservice->where('ServiceID',$id)->get_all();



		if ($query || $query1) {
			set_table(true);

			$No = array(
				'data' => 'No',
				'width' => '100px',
				'style' => 'padding: 10px 0 ;'
			);
			$Nama = array(
				'data' => 'Nama Item',
				'width' => '150px',
				'style' => 'padding: 10px 0 ;'
			);
			$Harga = array(
				'data' => 'Harga Satuan',
				'width' => '150px',
				'style' => 'padding: 10px 0 ;'
			);
			$Qty = array(
				'data' => 'Jumlah',
				'width' => '100px',
				'style' => 'padding: 10px 0 ;'
			);
			$Total = array(
				'data' => 'Total Harga',
				'width' => '150px',
				'style' => 'padding: 10px 0 ;'
			);

			$this->table->set_heading($No, $Nama, $Harga, $Qty, $Total);

			$this->table->add_row('1', $query->jasaservice->NamaService, '', '', 'Rp ' . number_format($query->jasaservice->BiayaService));

			$no = 2;

			foreach ($query1 as $row) {
				$item = $row->ItemID;

				$query3 = $this->item->where('ItemID', $item)->get();

				$noo = array(
					'data' => $no++,
					'width' => '150px',
					'style' => 'padding: 10px 0 ;'
				);

				$this->table->add_row($noo, $query3->NamaItem, $query3->HargaJual, $row->Jumlah, 'Rp ' . number_format($row->Total));
			}

			$Grand = array(
				'data' => 'Grand Total',
				'width' => '100px',
				'style' => 'padding: 10px 0 ;'
			);

			$this->table->add_row('', '', '', $Grand, 'Rp ' . number_format($query->TotalHarga));

			$subservice = $this->table->generate();

			$data = array('ServiceID' => $query->ServiceID,
				'TotalHarga' => $query->TotalHarga,
				'created_at' => $query->created_at,
				'subservice' => $subservice
			);

		}
      //file_put_contents('transaksi.json', json_encode($query1, JSON_PRETTY_PRINT));
		echo json_encode($data);

      // $xhr = ;


	}

}
