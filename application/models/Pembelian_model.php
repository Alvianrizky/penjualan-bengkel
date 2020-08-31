<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_model extends MY_Model
{
	protected $column_order = array(null, 'PembelianID','TotalHarga', 'created_at'); 
    protected $column_search = array('PembelianID','TotalHarga', 'created_at');
	protected $order = array('PembelianID' => 'desc');
        
	public function __construct()
	{
        $this->table       = 'pembelian';
        $this->primary_key = 'PembelianID';
        $this->fillable    = $this->column_search;
        $this->timestamps  = TRUE;

        // $this->has_many['subtransaksi'] = array('Subtransaksi_model', 'transaksiID', 'transaksiID');

		parent::__construct();
	}

    public function get_fields()
    {
        return $this->column_search;
	}
	
	public function filter($date)
	{
		$this->db->from($this->table);
		// $this->db->where('created_at >=', $date1);
		// $this->db->where('created_at <=', $date2);

		if ($date) {
			$this->db->where('created_at', $date);
		}
		
		$query = $this->db->get();

		return $query->result();
	}

    private function _get_datatables_query()
    {
		if ($this->input->post('transaksiID')) {
			$this->db->where('transaksiID', $this->input->post('transaksiID'));
		}

		if ($this->input->post('start_date') && $this->input->post('end_date')) {
			$this->db->where('DATE(created_at) >=', $this->input->post('start_date'));
			$this->db->where('DATE(created_at) <=', $this->input->post('end_date'));
		}

		$this->db->from($this->table);

        $i = 0;
    
        if(!empty($_POST['search']['value']))
        {
            foreach ($this->column_search as $item)
            {
                if(isset($_POST['search']['value']))
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
        $this->db->select_max('PembelianID');
        $this->db->from($this->table);

        $query = $this->db->get();

        return $query->row()->PembelianID;
    }

}
/* End of file '/Category_model.php' */
/* Location: ./application/models/Category_model.php */
