<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

   protected $page_header = 'Item Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model('Item_model', 'item');
      $this->load->library(array('ion_auth', 'form_validation', 'template','pdf'));
      $this->load->helper('bootstrap_helper');
   }

   public function index()
   {  

      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Item List';
      $data['page']         = '';

      $this->template->backend('item_v', $data);
   }

   public function get_item()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $list = $this->item->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->ItemID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
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
         "recordsTotal" => $this->item->count_rows(),
         "recordsFiltered" => $this->item->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }

   public function view()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('ItemID');

      $query = $this->item->where('ItemID', $id)->get();
      if($query){
         $data = array('ItemID' => $query->ItemID,
            'NamaItem' => $query->NamaItem,
            'Deskripsi' => $query->Deskripsi,
            'Stok'  => $query->Stok,
            'HargaBeli'  => $query->HargaBeli,
            'HargaJual'  => $query->HargaJual,
            'ReOrder'  => $query->ReOrder
         );
      }

      echo json_encode($data);
   }

   public function form_data()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $row = array();
      if($this->input->post('ItemID')){
         $id      = $this->input->post('ItemID');
         $query   = $this->item->where('ItemID', $id)->get(); 
         if($query){
            $row = array(
               'ItemID'    => $query->ItemID,
               'NamaItem'   => $query->NamaItem,
               'Deskripsi'   => $query->Deskripsi,
               'Stok'       => $query->Stok,
               'HargaBeli'       => $query->HargaBeli,
               'HargaJual'       => $query->HargaJual,
               'ReOrder'       => $query->ReOrder
            );
         }
         $row = (object) $row;
      }

      $data = array('hidden'=> form_hidden('ItemID', !empty($row->ItemID) ? $row->ItemID : ''),
        'NamaItem' => form_input(array('name'=>'NamaItem', 'id'=>'NamaItem', 'class'=>'form-control', 'value'=>!empty($row->NamaItem) ? $row->NamaItem : '')),
        'Deskripsi' => form_input(array('name'=>'Deskripsi', 'id'=>'Deskripsi', 'class'=>'form-control', 'value'=>!empty($row->Deskripsi) ? $row->Deskripsi : '')),
        'HargaBeli' => form_input(array('name'=>'HargaBeli', 'id'=>'HargaBeli', 'class'=>'form-control', 'value'=>!empty($row->HargaBeli) ? $row->HargaBeli : '')),
        'HargaJual' => form_input(array('name'=>'HargaJual', 'id'=>'HargaJual', 'class'=>'form-control', 'value'=>!empty($row->HargaJual) ? $row->HargaJual : '')),
        'ReOrder' => form_input(array('name'=>'ReOrder', 'id'=>'ReOrder', 'class'=>'form-control', 'value'=>!empty($row->ReOrder) ? $row->ReOrder : ''))
     );

      echo json_encode($data);
   }


   public function save_item()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $rules = array(
         'insert' => array(                     
            array('field' => 'NamaItem', 'label' => 'NamaItem', 'rules' => 'trim|required|max_length[100]'),                      
            array('field' => 'Deskripsi', 'label' => 'Deskripsi', 'rules' => 'max_length[300]'),
            array('field' => 'HargaBeli', 'label' => 'HargaBeli', 'rules' => 'max_length[100]'),
            array('field' => 'HargaJual', 'label' => 'HargaJual', 'rules' => 'max_length[100]'),
            array('field' => 'ReOrder', 'label' => 'ReOrder', 'rules' => 'max_length[6]')
         ),
         'update' => array(
            array('field' => 'ItemID', 'label' => 'ItemID', 'rules' => 'required|max_length[11]'),
            array('field' => 'NamaItem', 'label' => 'NamaItem', 'rules' => 'trim|required|max_length[100]'),                      
            array('field' => 'Deskripsi', 'label' => 'Deskripsi', 'rules' => 'max_length[300]'),
            array('field' => 'HargaBeli', 'label' => 'HargaBeli', 'rules' => 'max_length[100]'),
            array('field' => 'HargaJual', 'label' => 'HargaJual', 'rules' => 'max_length[100]'),
            array('field' => 'ReOrder', 'label' => 'ReOrder', 'rules' => 'max_length[6]')
         )                  
      );

      $row = array('NamaItem' => $this->input->post('NamaItem'),
         'Deskripsi' => $this->input->post('Deskripsi'),
         'HargaBeli'      => $this->input->post('HargaBeli'),
         'HargaJual'      => $this->input->post('HargaJual'),
         'ReOrder'      => $this->input->post('ReOrder'));

      $code = 0;

      if($this->input->post('ItemID') == null){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {

            $this->item->insert($row);

            $error =  $this->db->error();
            if($error['code'] <> 0){
               $code = 1;
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{
               $notifications = 'Success Insert Data';
            }
         }
         else{
            $code = 1;
            $notifications = validation_errors('<p>', '</p>'); 
         }

      }

      else{

         $this->form_validation->set_rules($rules['update']);

         if ($this->form_validation->run() == true) {

            $id = $this->input->post('ItemID');

            $this->item->where('ItemID', $id)->update($row);
            
            $error =  $this->db->error();
            if($error['code'] <> 0){               
               $code = 1;               
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{               
               $notifications = 'Success Update Data';
            }
         }
         else{
            $code = 1;
            $notifications = validation_errors('<p>', '</p>'); 
         }
      }

      $notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);
      
      echo json_encode(array('message' => $notifications, 'code' => $code));
   }

   public function delete()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $code = 0;

      $id = $this->input->post('ItemID');

      $this->item->where('ItemID', $id)->delete();

      $error =  $this->db->error();
      if($error['code'] <> 0){
         $code = 1;
         $notifications = $error['code'].' : '.$error['message'];
      }
      else{
         $notifications = 'Success Delete Data';
      }

      $notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);
      
      echo json_encode(array('message' => $notifications, 'code' => $code));
   }

}
