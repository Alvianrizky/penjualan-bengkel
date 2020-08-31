<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SubPembelian extends CI_Controller {

   protected $page_header = 'Aplikasi Pembelian';

   public function __construct()
   {
      parent::__construct();


      $this->load->model(array('SubPembelianTempo_model'=>'subpembelian', 'Item_model' => 'item', 'Pembelian_model' => 'pembelian', 'SubPembelian_model' => 'subpembelian1'));
      $this->load->library(array('ion_auth', 'form_validation', 'template'));
      $this->load->helper('bootstrap_helper');
   }

	public function index()
	{  
      
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}

      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Aplikasi Kasir';
      $data['page']         = '';

      $this->template->backend('subpembelian_v', $data);
	}

   public function get_subpembelian()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}

      $list = $this->subpembelian->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->SubPembelianID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = $no;
         $row[] = $field->NamaItem;
         $row[] = "Rp ".number_format($field->HargaJual);
         $row[] = $field->Jumlah;
         $row[] = "Rp ".number_format($field->TotalHarga);
         $row[] = '<button type="button" name="id" class="btn btn-danger btn-sm" onClick="delete_data(' . $id . ');">Delete</button>';

			$data[] = $row;
		}
		
		
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->subpembelian->count_rows(),
         "recordsFiltered" => $this->subpembelian->count_filtered(),
			"data" => $data,
      );
      echo json_encode($output);
   }

   public function totalharga()
   {
      $data = array('Total' => 'Rp '.number_format($this->subpembelian->total()));
      echo json_encode($data);
   }

   public function form_tambah()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $opt_item = $this->item->as_dropdown('NamaItem')->get_all();

      $data = array('ItemID' => form_dropdown('ItemID', !empty($opt_item) ? $opt_item : '', '', 'class="form-control chosen-select"'),
         'jumlahbeli' => form_input(array('name'=>'jumlahbeli', 'id'=>'datepicker', 'class'=>'form-control', 'value'=>'')),
         'Total' => 'Rp '.number_format($this->subpembelian->total())
      );

      echo json_encode($data);
   }

   public function save_tempo()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}
  
      $rules1 = array(
         'cek' => array('field' => 'ItemID', 'label' => 'ItemID', 'rules' => 'trim|is_unique[subpembelian_t.ItemID]|max_length[12]')            
      );

      $id1      = $this->input->post('ItemID');
      $query   = $this->item->where('ItemID', $id1)->get();

      $harga = $query->HargaJual;
      $total = $this->input->post('jumlahbeli');

      $totalharga = $harga * $total;
         
      $row = array(
         'ItemID'        => $this->input->post('ItemID'),
         'Jumlah'      => $this->input->post('jumlahbeli'),
         'TotalHarga'      => $totalharga        
      );

      $query1   = $this->subpembelian->where('ItemID', $id1)->get();
      
      if(!empty($query1))
      {
         $sub = $query1->TotalHarga;
         $jumlah = $query1->Jumlah;
         $jum = $jumlah + $this->input->post('jumlahbeli');

         $tot = $harga * $jum;

         $row3 = array(
            'Jumlah'      => $jum,
            'TotalHarga'      => $tot
         );
      }

		$stok = $query->Stok;
		$terjual = $query->Terjual;

		$stokakhir = $stok + $total;

		$row2 = array('Stok' => $stokakhir); 

		$this->form_validation->set_rules('ItemID', 'ItemID', 'trim|required|is_unique[subpembelian_t.ItemID]|max_length[12]');

		if ($this->form_validation->run() == true) {

			$this->subpembelian->insert($row);

			
			$this->item->where('ItemID', $id1)->update($row2);
		}
		else{
			$id = $this->input->post('ItemID');

         $this->subpembelian->where('ItemID', $id)->update($row3);
         $this->item->where('ItemID', $id1)->update($row2);
		}

		// print_r($id1);
  //     	exit();

      

      $total = 'Rp '.number_format($this->subpembelian->total());
      
      echo json_encode(array('Total' => $total));
   }

   public function save_transaksi()
   {
      if(!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $field = $this->subpembelian->as_object()->get_all();

      $data1 = array('TotalHarga' => $this->subpembelian->total());

      if($this->subpembelian->total() != null && !empty($field))
      {
         $this->pembelian->insert($data1);

         $id = $this->pembelian->id();
         
         foreach($field as $row)
         {
            $data['SubPembelianID'] = $row->SubPembelianID;
            $data['ItemID'] = $row->ItemID;
            $data['PembelianID'] = $id;
            $data['Jumlah'] = $row->Jumlah;
            $data['TotalHarga'] = $row->TotalHarga;

            $row1[] = $data;
         }

         $this->subpembelian1->setBatchImport($row1);
         $this->subpembelian1->insertData('subpembelian');
         $this->db->empty_table('subpembelian_t');
      }

      // $servicetiket   = $this->servicetiket->where('ServiceTiketID', $this->input->post('ServiceTiketID'))->get();
      // $kustomer   = $this->kustomer->where('KustomerID', $servicetiket->KustomerID)->get();
      // $kendaraan   = $this->kendaraan->where('KendaraanID', $servicetiket->KendaraanID)->get();
      $pembelian   = $this->pembelian->where('PembelianID', $this->pembelian->id())->get();

      $data2 = array(
         'subpembelian' => $this->subpembelian1->where('PembelianID', $this->pembelian->id())->get_all(),
         'id' => $this->pembelian->id(),
         'pembelian' => $pembelian
      );

  
      $this->load->view('backend/contents/invoice_p', $data2);
   }

   public function delete()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}
      
      $code = 0;
      $total = 0;

      $id = $this->input->post('SubPembelianID');
      
      $query = $this->subpembelian->where('SubPembelianID', $id)->get();
      
      $query1   = $this->item->where('ItemID', $query->ItemID)->get();
      $stok = $query1->Stok;
      $total = $query->Jumlah;
      $terjual = $query1->Terjual;

		$stokakhir = $stok - $total;
		$terjualakhir = $terjual - $total;

		$row2 = array('Stok' => $stokakhir);
     
      $this->subpembelian->where('SubPembelianID', $id)->delete();
      $this->item->where('ItemID', $query->ItemID)->update($row2);

      $error =  $this->db->error();
      if($error['code'] <> 0){
         $code = 1;
         $notifications = $error['code'].' : '.$error['message'];
      }
      else{
         $notifications = 'Success Delete Data';
         $total = 'Rp '.number_format($this->subpembelian->total());
      }

      $notif = ($code == 0) ? json_encode(array('icon' => 'success', 'message' => $notifications, 'code' => $code, 'total' => $total)) : json_encode(array('icon' => 'error', 'message' => $notifications, 'code' => $code, 'total' => $total));
      
      echo $notif;
   }

}
