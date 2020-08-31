<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceTiket extends CI_Controller {

   protected $page_header = 'Service Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model(array('ServiceTiket_model' => 'servicetiket', 'SubService_model' => 'subservice', 'SubServiceTempo_model' => 'subservicetempo', 'Kendaraan_model' => 'kendaraan', 'kustomer_model' => 'kustomer', 'Item_model' => 'item', 'Mekanik_model' => 'mekanik', 'Service_model' => 'service', 'JasaService_model' => 'jasaservice'));
      $this->load->library(array('ion_auth', 'form_validation', 'template'));
      $this->load->helper('bootstrap_helper');
   }

   public function index()
   {  

      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'ServiceTiket List';
      $data['page']         = '';

      $this->template->kasir('subservice_v', $data);
   }

   public function get_subservice()
   {
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $list = $this->subservicetempo->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->SubServiceID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = $no;
         $row[] = $field->NamaItem;
         $row[] = "Rp ".number_format($field->HargaJual);
         $row[] = $field->Jumlah;
         $row[] = "Rp ".number_format($field->Total);
         $row[] = '<button type="button" name="id" class="btn btn-danger btn-sm" onClick="delete_data(' . $id . ');">Delete</button>';

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->subservicetempo->count_rows(),
         "recordsFiltered" => $this->subservicetempo->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }

   public function totalharga()
   {
      $data = array('Total' => 'Rp '.number_format($this->subservicetempo->Total()));
      echo json_encode($data);
   }

   public function view()
   {
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('KendaraanID');

      $query = $this->kendaraan->where('KendaraanID', $id)->get();
      if($query){
         $data = array('KendaraanID' => $query->KendaraanID,
            'NoPolisi' => $query->NoPolisi,
            'NoRangka' => $query->NoRangka,
            'NoMesin'  => $query->NoMesin,
            'TipeMotor'  => $query->TipeMotor
         );
      }

      echo json_encode($data);
   }

   public function form_data()
   {
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $opt_kustomer     = $this->kustomer->as_dropdown('Nama')->get_all();
      $opt_kendaraan     = $this->kendaraan->as_dropdown('NoPolisi')->get_all();

      $row = array();
      if($this->input->post('ServiceTiketID')){
         $id      = $this->input->post('ServiceTiketID');
         $query   = $this->servicetiket
         ->with_kustomer('fields:Nama')
         ->with_kendaraan('fields:NoPolisi')
         ->where('ServiceTiketID', $id)->get(); 
         if($query){
            $row = array(
               'ServiceTiketID'    => $query->ServiceTiketID,
               'KustomerID'   => $query->kustomer->Nama,
               'KendaraanID'   => $query->kendaraan->NoPolisi,
               'Keterangan'       => $query->Keterangan
            );
         }
         $row = (object) $row;
      }

      $data = array('hiddenservicetiket'=> form_hidden('aksi', 'servicetiket'),
       'KustomerID' => form_dropdown('KustomerID', $opt_kustomer, !empty($row->KustomerID) ? $row->KustomerID : '', 'class="form-control chosen-select"'),
       'KendaraanID' => form_dropdown('KendaraanID', $opt_kendaraan, !empty($row->KendaraanID) ? $row->KendaraanID : '', 'class="form-control chosen-select"'),
       'Keterangan' => form_textarea(array('name'=>'Keterangan', 'id'=>'Keterangan', 'class'=>'form-control', 'value'=>!empty($row->Keterangan) ? $row->Keterangan : '', 'rows' => 3)),
       'formkustomer' => '<button type="button" name="kustomer" class="btn btn-primary btn-sm" onClick="formkustomer();"><i class="glyphicon glyphicon-plus"></i> Tambah Kustomer</button>',
       'formkendaraan' => '<button type="button" name="kendaraan" class="btn btn-primary btn-sm" onClick="formkendaraan();"><i class="glyphicon glyphicon-plus"></i> Tambah Kendaraan</button>'
      );

      $query1 = $this->servicetiket->where('Status', 0)->get_all();

      if ($query1) {
         set_table(true);

         $No = array(
            'data' => 'No',
         );
         $Nama = array(
            'data' => 'Nama Kustomer'
         );
         $nopolisi = array(
            'data' => 'No Polisi',
         );

         $this->table->set_heading($No, $Nama, $nopolisi);

         $no = 1;

         foreach ($query1 as $row) {

            $noo = array(
               'data' => $no++,
            );

            $kustomer = $this->kustomer->where('KustomerID', $row->KustomerID)->get();
            $kendaraan = $this->kendaraan->where('KendaraanID', $row->KendaraanID)->get();

            $this->table->add_row($noo, $kustomer->Nama, $kendaraan->NoPolisi);
         }

         $servicetiket = $this->table->generate();

         $data['servicetiket'] = $servicetiket;

      } else {

         $data['servicetiket'] = '<h4 class="text-center">Data Service Tiket Belum Ada</h4>';
      }
      
      echo json_encode($data);
   }

   public function form_kustomer()
   {
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $row = array();
      if($this->input->post('KustomerID')){
         $id      = $this->input->post('KustomerID');
         $query   = $this->kustomer->where('KustomerID', $id)->get(); 
         if($query){
            $row = array(
               'KustomerID'    => $query->KustomerID,
               'Nama'   => $query->Nama,
               'NoHp'   => $query->NoHp,
               'Alamat'       => $query->Alamat
            );
         }
         $row = (object) $row;
      }

      $data = array('hiddenkustomer'=> form_hidden('aksi', 'kustomer'),
       'Nama' => form_input(array('name'=>'Nama', 'id'=>'Nama', 'class'=>'form-control', 'value'=>!empty($row->Nama) ? $row->Nama : '')),
       'NoHp' => form_input(array('name'=>'NoHp', 'id'=>'NoHp', 'class'=>'form-control', 'value'=>!empty($row->NoHp) ? $row->NoHp : '')),
       'Alamat' => form_textarea(array('name'=>'Alamat', 'id'=>'Alamat', 'class'=>'form-control', 'value'=>!empty($row->Alamat) ? $row->Alamat : '', 'rows' => 3))
      );

      echo json_encode($data);
   }

   public function form_kendaraan()
   {
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $row = array();
      if($this->input->post('KendaraanID')){
         $id      = $this->input->post('KendaraanID');
         $query   = $this->kendaraan->where('KendaraanID', $id)->get(); 
         if($query){
            $row = array(
               'KendaraanID'    => $query->KendaraanID,
               'NoPolisi'   => $query->NoPolisi,
               'NoRangka'   => $query->NoRangka,
               'NoMesin'       => $query->NoMesin,
               'TipeMotor'       => $query->TipeMotor
            );
         }
         $row = (object) $row;
      }

      $data = array('hiddenkendaraan'=> form_hidden('aksi', 'kendaraan'),
       'NoPolisi' => form_input(array('name'=>'NoPolisi', 'id'=>'NoPolisi', 'class'=>'form-control', 'value'=>!empty($row->NoPolisi) ? $row->NoPolisi : '')),
       'NoRangka' => form_input(array('name'=>'NoRangka', 'id'=>'NoRangka', 'class'=>'form-control', 'value'=>!empty($row->NoRangka) ? $row->NoRangka : '')),
       'NoMesin' => form_input(array('name'=>'NoMesin', 'id'=>'NoMesin', 'class'=>'form-control', 'value'=>!empty($row->NoMesin) ? $row->NoMesin : '')),
       'TipeMotor' => form_input(array('name'=>'TipeMotor', 'id'=>'TipeMotor', 'class'=>'form-control', 'value'=>!empty($row->TipeMotor) ? $row->TipeMotor : ''))
      );

      echo json_encode($data);
   }

   public function form_tambah()
   {
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $opt_item = $this->item->as_dropdown('NamaItem')->get_all();
      $opt_mekanik = $this->mekanik->as_dropdown('NamaMekanik')->get_all();
      // $opt_jasaservice = $this->jasaservice->as_dropdown('NamaService', 'BiayaService')->get_all();

      $query1 = $this->servicetiket->where('Status', 0)->get_all();
      $jasaservice = $this->jasaservice->as_object()->get_all();

      if($query1)
      {
         foreach($query1 as $value)
         {
            $query   = $this->kendaraan->where('KendaraanID', $value->KendaraanID)->get(); 
            $query2   = $this->kustomer->where('KustomerID', $value->KustomerID)->get(); 

            $data1 = $query2->Nama.' - '.$query->NoPolisi;

            $service[$value->ServiceTiketID] = $data1;
         }
      }

      if($jasaservice)
      {
         foreach($jasaservice as $value)
         {
            $data1 = $value->NamaService.' --- Rp '.number_format($value->BiayaService);
            // "Rp ".number_format($field->Total)
            $biaya[$value->JasaServiceID] = $data1;
         }
      }

      $data = array('ItemID' => form_dropdown('ItemID', !empty($opt_item) ? $opt_item : '', '', 'class="form-control chosen-select"'),
         'jumlahbeli' => form_input(array('name'=>'jumlahbeli', 'id'=>'datepicker', 'class'=>'form-control', 'value'=>'')),
         'ServiceTiketID' => form_dropdown('ServiceTiketID', !empty($service) ? $service : '', '', 'class="form-control"'),
         'MekanikID' => form_dropdown('MekanikID', !empty($opt_mekanik) ? $opt_mekanik : '', '', 'class="form-control"'),
         'JasaServiceID' => form_dropdown('JasaServiceID', !empty($biaya) ? $biaya : '', '', 'class="form-control" onchange="cek(this.value)"'),
         'Total' => 'Rp '.number_format($this->subservicetempo->total()),
         'Total1' => 'Rp '.number_format($this->subservicetempo->total())
      );

      echo json_encode($data);
   }

   public function form_service()
   {
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $opt_item = $this->item->as_dropdown('NamaItem')->get_all();

      $query1 = $this->servicetiket->as_object()->get_all();

      foreach($query1 as $value)
      {
         $query   = $this->kendaraan->where('KendaraanID', $value->KendaraanID)->get(); 
         $query2   = $this->kustomer->where('KustomerID', $value->KustomerID)->get(); 

         $data1 = $query2->Nama.' - '.$query->NoPolisi;

         $service[$value->ServiceTiketID] = $data1;
      }

      $row = array();
      if($this->input->post('ServiceTiketID')){
         $id      = $this->input->post('ServiceTiketID');
         $query   = $this->servicetiket
         ->with_kustomer('fields:Nama')
         ->with_kendaraan('fields:NoPolisi')
         ->where('ServiceTiketID', $id)->get(); 
         if($query){
            $row = array(
               'ServiceTiketID'    => $query->ServiceTiketID,
               'KustomerID'   => $query->kustomer->Nama,
               'KendaraanID'   => $query->kendaraan->NoPolisi,
               'Keterangan'       => $query->Keterangan
            );
         }
         $row = (object) $row;
      }

      $data = array('hiddenkendaraan'=> form_hidden('aksi', 'kendaraan'),
       'ServiceTiketID' => form_dropdown('ServiceTiketID', $service, !empty($row->ServiceTiketID) ? $row->ServiceTiketID : '', 'class="form-control chosen-select" onchange="cek(this.value)" '),
       'Item' => form_dropdown('Item', $opt_item, !empty($row->Item) ? $row->Item : '', 'class="form-control chosen-select"'),

      );

      echo json_encode($data);
   }

   public function cek()
   {
      $id1 = $this->input->post('JasaServiceID1');
      // print_r($id1);
      // exit();
      $query = $this->jasaservice->where('JasaServiceID', $id1)->get(); 

      $biaya = $this->subservicetempo->total() + $query->BiayaService;

      $data = array(
       'Total1' => 'Rp '.number_format($biaya)
      );

      echo json_encode($data);
   }

   public function save_tempo()
   {   
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      } 

      $rules1 = array(
         'cek' => array('field' => 'ItemID', 'label' => 'ItemID', 'rules' => 'trim|is_unique[subservice_t.ItemID]|max_length[30]')            
      );

      $id1      = $this->input->post('ItemID');
      $query   = $this->item->where('ItemID', $id1)->get();

      $harga = $query->HargaJual;
      $total = $this->input->post('jumlahbeli');

      $totalharga = $harga * $total;

      $row = array(
         'ItemID'        => $this->input->post('ItemID'),
         'Jumlah'      => $this->input->post('jumlahbeli'),
         'Total'      => $totalharga
      );

      $query1   = $this->subservicetempo->where('ItemID', $id1)->get();
      
      if(!empty($query1))
      {
         $sub = $query1->Total;
         $jumlah = $query1->Jumlah;
         $jum = $jumlah + $this->input->post('jumlahbeli');

         $tot = $harga * $jum;

         $row3 = array(
            'Jumlah'      => $jum,
            'Total'      => $tot
         );
      }

      $stok = $query->Stok;
      $terjual = $query->Terjual;

      $stokakhir = $stok - $total;
      $terjualakhir = $terjual + $total; 

      $row2 = array('Stok' => $stokakhir,
       'Terjual' => $terjualakhir);

      if($stok < $this->input->post('jumlahbeli'))
      {
         return "fail"; 
      }
      else{

         $this->form_validation->set_rules('ItemID', 'ItemID', 'trim|required|is_unique[subservice_t.ItemID]|max_length[12]');

         if ($this->form_validation->run() == true) {

            $this->subservicetempo->insert($row);
            $this->item->where('ItemID', $id1)->update($row2);
         }
         else{
            $id = $this->input->post('ItemID');

            $this->subservicetempo->where('ItemID', $id)->update($row3);
            $this->item->where('ItemID', $id1)->update($row2);
         }

      }

      $total = 'Rp '.number_format($this->subservicetempo->total());
      
      echo json_encode(array('Total' => $total));
   }

   public function save()
   {
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $rules = array(
         'servicetiket' => array(                     
            array('field' => 'KustomerID', 'label' => 'No Polisi', 'rules' => 'trim|required|max_length[11]'),                      
            array('field' => 'KendaraanID', 'label' => 'No Rangka', 'rules' => 'trim|required|max_length[11]'),
            array('field' => 'Keterangan', 'label' => 'Keterangan', 'rules' => 'required|max_length[300]')
         ),
         'kustomer' => array(                     
            array('field' => 'Nama', 'label' => 'Nama', 'rules' => 'trim|required|max_length[100]'),                      
            array('field' => 'Alamat', 'label' => 'Alamat', 'rules' => 'required|max_length[300]'),
            array('field' => 'NoHp', 'label' => 'No Hp', 'rules' => 'required|max_length[13]')
         ),
         'kendaraan' => array(                     
            array('field' => 'NoPolisi', 'label' => 'No Polisi', 'rules' => 'required|max_length[10]'),                      
            array('field' => 'NoRangka', 'label' => 'No Rangka', 'rules' => 'required|max_length[50]'),
            array('field' => 'NoMesin', 'label' => 'No Mesin', 'rules' => 'required|max_length[20]'),
            array('field' => 'TipeMotor', 'label' => 'Tipe Motor', 'rules' => 'required|max_length[100]')
         )                 
      );

      $servicetiket = array(
         'KustomerID'  => $this->input->post('KustomerID'),
         'KendaraanID' => $this->input->post('KendaraanID'),
         'Keterangan'  => $this->input->post('Keterangan'),
         'Status'  => 0
      );

      $kustomer = array(
         'Nama'   => $this->input->post('Nama'),
         'NoHp'   => $this->input->post('NoHp'),
         'Alamat' => $this->input->post('Alamat')
      );

      $kendaraan = array(
         'NoPolisi'  => $this->input->post('NoPolisi'),
         'NoMesin'   => $this->input->post('NoMesin'),
         'NoRangka'  => $this->input->post('NoRangka'),
         'TipeMotor' => $this->input->post('TipeMotor')
      );

      $code = 0;

      if($this->input->post('aksi') == 'servicetiket')
      {
         $this->form_validation->set_rules($rules['servicetiket']);

         if ($this->form_validation->run() == true) {

            $this->servicetiket->insert($servicetiket);

            $error =  $this->db->error();
            if($error['code'] <> 0){
               $code = 1;
               $title = 'Warning!';
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{
               $title = 'Insert!';
               $notifications = 'Success Insert Data';
            }
         }
         else{
            $code = 1;
            $title = 'Warning!';
            $notifications = validation_errors(); 
         }
      }
      if ($this->input->post('aksi') == 'kustomer') 
      {
         $this->form_validation->set_rules($rules['kustomer']);

         if ($this->form_validation->run() == true) {

            $this->kustomer->insert($kustomer);

            $error =  $this->db->error();
            if($error['code'] <> 0){
               $code = 1;
               $title = 'Warning!';
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{
               $title = 'Insert!';
               $notifications = 'Success Insert Data';
            }
         }
         else{
            $code = 1;
            $title = 'Warning!';
            $notifications = validation_errors('', ''); 
         }
      }
      if ($this->input->post('aksi') == 'kendaraan') 
      {
         $this->form_validation->set_rules($rules['kendaraan']);

         if ($this->form_validation->run() == true) {

            $this->kendaraan->insert($kendaraan);

            $error =  $this->db->error();
            if($error['code'] <> 0){
               $code = 1;
               $title = 'Warning!';
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{
               $title = 'Insert!';
               $notifications = 'Success Insert Data';
            }
         }
         else{
            $code = 1;
            $title = 'Warning!';
            $notifications = validation_errors('', ''); 
         }
      }

      $notif = ($code == 0) ? json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code, 'aksi' => $this->input->post('aksi'))) : json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code, 'aksi' => $this->input->post('aksi')));

      echo $notif;
   }

   public function save_transaksi()
   {
      if (!$this->ion_auth->logged_in()) {
         redirect('auth/login', 'refresh');
      } elseif (!$this->ion_auth->in_group('members')) {
         redirect('auth/login', 'refresh');
      }

      $field = $this->subservicetempo->as_object()->get_all();
      // $field1 = $this->service->as_object()->get_all();
      $jasa = $this->jasaservice->where('JasaServiceID', $this->input->post('JasaServiceID'))->get();

      $TotalHarga = $this->subservicetempo->total() + $jasa->BiayaService;

      $data1 = array(
         'ServiceTiketID' => $this->input->post('ServiceTiketID'),
         'MekanikID' => $this->input->post('MekanikID'),
         'JasaServiceID' => $this->input->post('JasaServiceID'),
         'TotalHarga' => $TotalHarga
      );

      // echo "<pre>";
      // print_r($data1);
      // exit();

      if($this->subservicetempo->total() != null && !empty($field) && !empty($data1))
      {
         $this->service->insert($data1);

         $id = $this->service->id();
         
         foreach($field as $row)
         {
            $data['SubServiceID'] = $row->SubServiceID;
            $data['ItemID'] = $row->ItemID;
            $data['ServiceID'] = $id;
            $data['Jumlah'] = $row->Jumlah;
            $data['Total'] = $row->Total;

            $row1[] = $data;
         }

         $this->subservice->setBatchImport($row1);
         $this->subservice->insertData('subservice');
         $this->db->empty_table('subservice_t');

         $row = array('Status' => 1);
         $this->servicetiket->where('ServiceTiketID', $this->input->post('ServiceTiketID'))->update($row);
         // $this->servicetiket->where('KendaraanID', $id)->delete();
      }

      // echo "<pre>";
      // print_r($row1);
      // exit();

      $servicetiket   = $this->servicetiket->where('ServiceTiketID', $this->input->post('ServiceTiketID'))->get();
      $kustomer   = $this->kustomer->where('KustomerID', $servicetiket->KustomerID)->get();
      $kendaraan   = $this->kendaraan->where('KendaraanID', $servicetiket->KendaraanID)->get();
      $mekanik   = $this->mekanik->where('MekanikID', $this->input->post('MekanikID'))->get();
      $service   = $this->service->where('ServiceID', $this->service->id())->get();

      $data2 = array(
         'subservice' => $this->subservice->where('ServiceID', $this->service->id())->get_all(),
         'id' => $this->service->id(),
         'kustomer' => $kustomer,
         'kendaraan' => $kendaraan,
         'mekanik' => $mekanik,
         'service' => $service,
         'jasa' => $jasa
      );

      // echo "<pre>";
      // print_r($this->subservice->where('ServiceID', $this->service->id())->get_all());
      // exit();

      $this->load->view('backend/contents/invoice_v', $data2);
      // $this->template->backend('invoice_v', $data2);

   }

   public function print()
   {
      $this->load->view('backend/contents/invoice_v');
   }

}
