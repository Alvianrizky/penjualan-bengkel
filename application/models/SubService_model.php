<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class SubService_model extends MY_Model
{
	protected $column_order = array(null, 'SubServiceID','ServiceID','ItemID','Jumlah', 'Total'); 
    protected $column_search = array('SubServiceID','ServiceID','ItemID','Jumlah', 'Total');
    protected $order = array('SubServiceID' => 'asc');

	public function __construct()
	{
        $this->table = 'subservice';
        $this->primary_key = 'SubServiceID';

        $this->timestamps = TRUE;

        // $this->has_many['products'] = array('Products_model', 'SupplierID', 'SupplierID');

		parent::__construct();
	
	}

	private function _get_datatables_query()
    {
        
        $this->db->from($this->table);

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

    public function setBatchImport($batchImport)
    {
        $this->_batchImport = $batchImport;
    }

    public function insertData($mytable)
    {
        $data = $this->_batchImport;
        $this->db->insert_batch($mytable, $data);
    }

    public function total()
    {
        $this->db->select_sum('Total');
        $query = $this->db->get($this->table);
        if($query->num_rows()>0)
        {
            return $query->row()->Total;
        }
        else
        {
            return 0;
        }
    }
}
/* End of file '/Suppliers_model.php' */
/* Location: ./application/models/Suppliers_model.php */
