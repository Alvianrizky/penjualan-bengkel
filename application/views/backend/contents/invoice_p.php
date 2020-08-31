<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url().'assets/';?>css/bootstrap.min.css">
    <style>
        @page { 
            size: landscape;
        }
        @media print {
            #print{
                display: all;
            }
        }
        @media screen {
            #print{
                display: none;
            }
        }
    </style>
</head>
<body id="print">
    <h3 class="text-center" style="margin-bottom: 20px;">Faktur Pembelian</h3>
    <div class="row">
        <div class="col-3">
            <table>
                <tr>
                    <td style="width: 80px;">Tanggal</td>
                    <td style="width: 20px;">:</td>
                    <td><?php echo date('d/m/Y') ?></td>
                </tr>
            </table>
        </div>
        <div class="col-1"></div>
        <div class="col-4 text-center">
            <p>
                Ducati Motor<br>
                Jl. Imogiri Barat Km 5.5<br>
                Bangunharjo Sewon Bantul Yogyakarta<br>
                081328266785
            </p>
        </div>
        <div class="col-2"></div>
        <div class="col-2"></div>
    </div>

    <table class="table">
        <tr>
            <td>No</td>
            <td>Nama Item</td>
            <td>Harga Satuan</td>
            <td>Jumlah</td>
            <td>Total</td>
        </tr>
        <?php

        $this->load->model(array('Item_model' => 'item', 'Service_model' => 'service', 'Pembelian_model' => 'pembelian'));

        if($subpembelian):
            foreach($subpembelian as $row):
                $no = 1;
                $query   = $this->item->where('ItemID', $row->ItemID)->get();

        ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $query->NamaItem; ?></td>
            <td><?php echo "Rp ".number_format($query->HargaJual); ?></td>
            <td><?php echo $row->Jumlah; ?></td>
            <td><?php echo "Rp ".number_format($row->TotalHarga); ?></td>
        </tr>

        <?php

            endforeach;
        endif;

        ?>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Total Bayar :</td>
            <td><?php echo "Rp ".number_format($pembelian->TotalHarga); ?></td>
        </tr>

    </table>
    <script>
        window.print();
    </script>
</body>
</html>