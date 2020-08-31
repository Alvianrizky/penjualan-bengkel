<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kendaraan extends CI_Controller {

   protected $page_header = 'Kendaraan Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model('Kendaraan_model', 'kendaraan');
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
      $data['panel_heading'] = 'Kendaraan List';
      $data['page']         = '';

      $this->template->backend('kendaraan_v', $data);
   }

   public function get_kendaraan()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $list = $this->kendaraan->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->KendaraanID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->NoPolisi;
         $row[] = $field->NoRangka;
         $row[] = $field->NoMesin;
         $row[] = $field->TipeMotor;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->kendaraan->count_rows(),
         "recordsFiltered" => $this->kendaraan->count_filtered(),
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
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
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

      $data = array('hidden'=> form_hidden('KendaraanID', !empty($row->KendaraanID) ? $row->KendaraanID : ''),
        'NoPolisi' => form_input(array('name'=>'NoPolisi', 'id'=>'NoPolisi', 'class'=>'form-control', 'value'=>!empty($row->NoPolisi) ? $row->NoPolisi : '')),
        'NoRangka' => form_input(array('name'=>'NoRangka', 'id'=>'NoRangka', 'class'=>'form-control', 'value'=>!empty($row->NoRangka) ? $row->NoRangka : '')),
        'NoMesin' => form_input(array('name'=>'NoMesin', 'id'=>'NoMesin', 'class'=>'form-control', 'value'=>!empty($row->NoMesin) ? $row->NoMesin : '')),
        'TipeMotor' => form_input(array('name'=>'TipeMotor', 'id'=>'TipeMotor', 'class'=>'form-control', 'value'=>!empty($row->TipeMotor) ? $row->TipeMotor : ''))
     );

      echo json_encode($data);
   }


   public function save_kendaraan()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }elseif (!$this->ion_auth->is_admin()) {
         redirect('auth/login', 'refresh');
      }

      $rules = array(
         'insert' => array(                     
            array('field' => 'NoPolisi', 'label' => 'No Polisi', 'rules' => 'trim|required|max_length[10]'),                      
            array('field' => 'NoRangka', 'label' => 'No Rangka', 'rules' => 'max_length[50]'),
            array('field' => 'NoMesin', 'label' => 'No Mesin', 'rules' => 'max_length[20]'),
            array('field' => 'TipeMotor', 'label' => 'Tipe Motor', 'rules' => 'max_length[100]')
         ),
         'update' => array(
            array('field' => 'KendaraanID', 'label' => 'Kendaraan ID', 'rules' => 'required|max_length[11]'),
            array('field' => 'NoPolisi', 'label' => 'No Polisi', 'rules' => 'trim|required|max_length[10]'),                      
            array('field' => 'NoRangka', 'label' => 'No Rangka', 'rules' => 'max_length[50]'),
            array('field' => 'NoMesin', 'label' => 'No Mesin', 'rules' => 'max_length[20]'),
            array('field' => 'TipeMotor', 'label' => 'Tipe Motor', 'rules' => 'max_length[100]')
         )                  
      );

      $row = array('NoPolisi' => $this->input->post('NoPolisi'),
         'NoMesin' => $this->input->post('NoMesin'),
         'NoRangka'      => $this->input->post('NoRangka'),
         'TipeMotor'      => $this->input->post('TipeMotor'));

      $code = 0;

      if($this->input->post('KendaraanID') == null){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {

            $this->kendaraan->insert($row);

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

            $id = $this->input->post('KendaraanID');

            $this->kendaraan->where('KendaraanID', $id)->update($row);
            
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

      $id = $this->input->post('KendaraanID');

      $this->kendaraan->where('KendaraanID', $id)->delete();

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
