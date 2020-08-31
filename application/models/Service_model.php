<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Service_model extends MY_Model
{
	protected $column_order = array(null, 'ServiceID','ServiceTiketID','MekanikID','TotalHarga', 'JasaServiceID', 'created_at'); 
    protected $column_search = array('ServiceID','ServiceTiketID.KustomerID','ServiceTiketID.KendaraanID','MekanikID.NamaMekanik', 'jasaservice.NamaService', 'TotalHarga');
    protected $order = array('service.created_at' => 'desc');

	public function __construct()
	{
        $this->table = 'service';
        $this->primary_key = 'ServiceID';   

        $this->timestamps = TRUE;

        // $this->has_many['products'] = array('Products_model', 'SupplierID', 'SupplierID');
        $this->has_one['servicetiket'] = array('ServiceTiket_model','ServiceTiketID','ServiceTiketID');
        $this->has_one['mekanik'] = array('Mekanik_model','MekanikID','MekanikID');
        $this->has_one['jasaservice'] = array('JasaService_model','JasaServiceID','JasaServiceID');

		parent::__construct();
	
	}

	private function _get_datatables_query()
    {
        
        $this->db->from($this->table);
        $this->db->join('servicetiket', 'servicetiket.ServiceTiketID=service.ServiceTiketID');
        $this->db->join('mekanik', 'mekanik.MekanikID=service.MekanikID');
        $this->db->join('jasaservice', 'jasaservice.JasaServiceID=service.JasaServiceID');
        if ($this->input->post('start_date') && $this->input->post('end_date')) {
            $this->db->where('DATE(service.created_at) >=', $this->input->post('start_date'));
            $this->db->where('DATE(service.created_at) <=', $this->input->post('end_date'));
        }

        $i = 0;
        
        if(!empty($_POST['search']['value']))
        {
            foreach ($this->column_search as $item)
            {
                if($_POST['search']['value'])
                {
                    
                    if($i===0)
                    {
                        $this->db->group_start(); 
                        $this->db->like($item, $_POST['search']['value']);
                    }
                    else
                    {
                        $this->db->or_like($item, $_POST['search']['value']);
                    }

                    if(count($this->column_search) - 1 == $i)
                        $this->db->group_end();
                }
                $i++;
            }
        }
        
        if(isset($_POST['order']))
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();

        $length = isset($_POST['length']) ? $_POST['length'] : 0;
        if($length != -1){
        	$start  = isset($_POST['start']) ? $_POST['start'] : 0;        	
        	$this->db->limit($length, $start);
        }

        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function id()
    {
        $this->db->select_max('ServiceID');
        $this->db->from($this->table);

        $query = $this->db->get();

        return $query->row()->ServiceID;
    }

}
/* End of file '/Suppliers_model.php' */
/* Location: ./application/models/Suppliers_model.php */
