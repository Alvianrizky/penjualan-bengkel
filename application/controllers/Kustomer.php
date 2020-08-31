<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kustomer extends CI_Controller {

   protected $page_header = 'Kustomer Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model('kustomer_model', 'kustomer');
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
      $data['panel_heading'] = 'Kustomer List';
      $data['page']         = '';

      $this->template->backend('kustomer_v', $data);
   }

   public function get_kustomer()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $list = $this->kustomer->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->KustomerID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->Nama;
         $row[] = $field->NoHp;
         $row[] = $field->Alamat;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->kustomer->count_rows(),
         "recordsFiltered" => $this->kustomer->count_filtered(),
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

      $id = $this->input->post('KustomerID');

      $query = $this->kustomer->where('KustomerID', $id)->get();
      if($query){
         $data = array('KustomerID' => $query->KustomerID,
            'Nama' => $query->Nama,
            'NoHp' => $query->NoHp,
            'Alamat'      => $query->Alamat
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

      $data = array('hidden'=> form_hidden('KustomerID', !empty($row->KustomerID) ? $row->KustomerID : ''),
        'Nama' => form_input(array('name'=>'Nama', 'id'=>'Nama', 'class'=>'form-control', 'value'=>!empty($row->Nama) ? $row->Nama : '')),
        'NoHp' => form_input(array('name'=>'NoHp', 'id'=>'NoHp', 'class'=>'form-control', 'value'=>!empty($row->NoHp) ? $row->NoHp : '')),
        'Alamat' => form_input(array('name'=>'Alamat', 'id'=>'Alamat', 'class'=>'form-control', 'value'=>!empty($row->Alamat) ? $row->Alamat : ''))
     );

      echo json_encode($data);
   }


   public function save_kustomer()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $rules = array(
         'insert' => array(                     
            array('field' => 'Nama', 'label' => 'Nama', 'rules' => 'trim|required|max_length[100]'),                      
            array('field' => 'Alamat', 'label' => 'Alamat', 'rules' => 'max_length[300]'),
            array('field' => 'NoHp', 'label' => 'No Hp', 'rules' => 'max_length[13]')
         ),
         'update' => array(
            array('field' => 'KustomerID', 'label' => 'Kustomer ID', 'rules' => 'required|max_length[11]'),
            array('field' => 'Nama', 'label' => 'Nama', 'rules' => 'trim|required|max_length[100]'),                      
            array('field' => 'Alamat', 'label' => 'Alamat', 'rules' => 'max_length[300]'),
            array('field' => 'NoHp', 'label' => 'No Hp', 'rules' => 'max_length[13]')
         )                  
      );

      $row = array('Nama' => $this->input->post('Nama'),
         'NoHp' => $this->input->post('NoHp'),
         'Alamat'      => $this->input->post('Alamat'));

      $code = 0;

      if($this->input->post('KustomerID') == null){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {

            $this->kustomer->insert($row);

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

            $id = $this->input->post('KustomerID');

            $this->kustomer->where('KustomerID', $id)->update($row);
            
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

      $id = $this->input->post('KustomerID');

      $this->kustomer->where('KustomerID', $id)->delete();

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
