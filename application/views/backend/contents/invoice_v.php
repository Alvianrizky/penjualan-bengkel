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
    <h3 class="text-center" style="margin-bottom: 20px;">Faktur Penjualan</h3>
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
    <div class="row" style="margin-bottom: 50px;">
        <div class="col-3">
            <table>
                <tr>
                    <td style="width: 80px!important;">No Polisi</td>
                    <td style="width: 20px!important;">:</td>
                    <td><?php echo $kendaraan->NoPolisi; ?></td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><?php echo $kustomer->Nama; ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?php echo $kustomer->Alamat; ?></td>
                </tr>
                <tr>
                    <td>No Hp</td>
                    <td>:</td>
                    <td><?php echo $kustomer->NoHp; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-3"></div>
        <div class="col-2"></div>
        <div class="col-4">
            <table>
                <tr>
                    <td style="width: 130px!important;">Nama Mekanik</td>
                    <td style="width: 20px!important;">:</td>
                    <td><?php echo $mekanik->NamaMekanik; ?></td>
                </tr>
                <tr>
                    <td>No Rangka</td>
                    <td>:</td>
                    <td><?php echo $kendaraan->NoRangka; ?></td>
                </tr>
                <tr>
                    <td>No Mesin</td>
                    <td>:</td>
                    <td><?php echo $kendaraan->NoMesin; ?></td>
                </tr>
                <tr>
                    <td>Tipe Motor</td>
                    <td>:</td>
                    <td><?php echo $kendaraan->TipeMotor; ?></td>
                </tr>
            </table>
        </div>
    </div> 

    <table class="table">
        <tr>
            <td>No</td>
            <td>Nama Item</td>
            <td>Harga Satuan</td>
            <td>Jumlah</td>
            <td>Total</td>
        </tr>
        <tr>
            <td>1</td>
            <td><?php echo $jasa->NamaService; ?></td>
            <td></td>
            <td></td>
            <td><?php echo "Rp ".number_format($jasa->BiayaService); ?></td>
        </tr>
        <?php

        $this->load->model(array('ServiceTiket_model' => 'servicetiket', 'SubService_model' => 'subservice', 'SubServiceTempo_model' => 'subservicetempo', 'Kendaraan_model' => 'kendaraan', 'kustomer_model' => 'kustomer', 'Item_model' => 'item', 'Mekanik_model' => 'mekanik', 'Service_model' => 'service'));

        if($subservice):
            foreach($subservice as $row):
                $no = 2;
                $query   = $this->item->where('ItemID', $row->ItemID)->get();

        ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $query->NamaItem; ?></td>
            <td><?php echo "Rp ".number_format($query->HargaJual); ?></td>
            <td><?php echo $row->Jumlah; ?></td>
            <td><?php echo "Rp ".number_format($row->Total); ?></td>
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
            <td><?php echo "Rp ".number_format($service->TotalHarga); ?></td>
        </tr>

    </table>
    
    <script>
        window.print();
    </script>
</body>
</html>