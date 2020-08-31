<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends MY_Model
{
	protected $column_order = array(null, 'ItemID','NamaItem','Deskripsi', 'Stok', 'HargaBeli', 'HargaJual', 'Terjual', 'ReOrder'); 
    protected $column_search = array('ItemID','NamaItem','Deskripsi', 'Stok', 'HargaBeli', 'HargaJual', 'Terjual', 'ReOrder');
    protected $order = array('ItemID' => 'asc');

	public function __construct()
	{
        $this->table       = 'item';
        $this->primary_key = 'ItemID';
        $this->fillable    = $this->column_order;
        $this->timestamps  = TRUE;

        // $this->has_one['categories'] = array('Categories_model', 'KategoriID', 'KategoriID');
        // $this->has_one['suppliers']  = array('Suppliers_model', 'SupplierID', 'SupplierID');
        // $this->has_many['tempo'] = array('Tempo_model', 'ProdukID', 'ProdukID');

		parent::__construct();
	}

    private function _get_datatables_query()
    {
        
        $this->db->select($this->column_search);

        $this->db->from($this->table);
  //       $this->db->join('categories', 'categories.KategoriID=products.KategoriID');
		// $this->db->join('suppliers', 'suppliers.SupplierID=products.SupplierID');
		$this->db->where('Stok <= ReOrder');

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

}
/* End of file '/Products_model.php' */
/* Location: ./application/models/Products_model.php */
