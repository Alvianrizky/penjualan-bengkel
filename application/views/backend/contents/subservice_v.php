<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<?php echo isset($page_header) ? $page_header : ''; ?>
		<small></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active"> <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?></li>
	</ol>
</section>


<!-- Main content -->
<section class="content">
	<div id="notifications"></div>

	<div class="row">
		<div class="col-md-12">
			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo isset($panel_heading) ? $panel_heading : ''; ?> </h3>
				</div>

				<div id="table-data">
					<div class="box-body">

						<div class="row">
							<div class="col-lg-8">
								<form role="form" method="POST" action="" id="form-tambah">
									<div class="box-body">
										<div class="row">
											<div class="col-lg-8">
												<div id="hidden"></div>
												<div id="js-config"></div>
												<div class="form-group">
													<label>Nama Item</label>
													<div id="ItemID"></div>
												</div>
												<div class="form-group">
													<label>Jumlah Beli</label>
													<div id="jumlahbeli"></div>
												</div>
											</div>
										</div>
										<button type="button" name="submit" id="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Tambah Data</button> &nbsp; &nbsp;
										<button type="button" name="submit" id="tampil" class="btn btn-primary">Bayar Pesanan</button> &nbsp; &nbsp;
									</div>
								</form>
							</div>
							<div class="col-lg-4">
								<div class="card text-right" id="total" style="font-size: 50px;">
									<div id="tot"></div>
								</div>
							</div>
						</div>
						<div class="table-responsive" id="table-responsive">
							<table id="table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama Item</th>
										<th>Harga</th>
										<th>Jumlah Beli</th>
										<th>Total Harga</th>
										<th style="width: 100px!important;">Action</th>
									</tr>
								</thead>
								<tbody>
								</tbody>

								<tfoot>
									<tr>
										<th>No</th>
										<th>Nama Item</th>
										<th>Harga</th>
										<th>Jumlah Beli</th>
										<th>Total Harga</th>
										<th>Action</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="box-footer">
						Klik <kbd>SHIFT</kbd> Untuk Membuat Service Tiket
						<i style="margin-right: 20px;"></i>
						klik <kbd>CTRL</kbd> Jika Sudah Selesai Service
					</div>
				</div>

				<form role="form" method="POST" action="" id="form-data" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-inline" style="margin-bottom: 25px;">
							<div class="row">
								<div class="col-lg-2">
									<div id="formkustomer"></div>
								</div>
								<div class="col-2">
									<div id="formkendaraan"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<div id="hiddenservicetiket"></div>
								<div class="form-group">
									<label>Nama Kustomer</label>
									<div id="KustomerID"></div>
								</div>
								
								<div class="form-group">
									<label>Nomer Kendaraan</label>
									<div id="KendaraanID"></div>
								</div>
								<div class="form-group">
									<label>Keterangan</label>
									<div id="Keterangan"></div>
								</div>
								<button type="button" id="saveservicetiket" name="submit" class="btn btn-primary">Submit</button>
							</div>
							<div class="col-lg-6">
								<div class="panel panel-info">
									<div class="panel-heading">
										<h4>Daftar Service</h4>
									</div>
									<div class="panel-body scroll" id="tabel" style="max-height: 220px; height: auto;">
										<div id="servicetiket"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						Klik <kbd>SHIFT</kbd> Untuk Membuat Service Tiket
						<i style="margin-right: 20px;"></i>
						klik <kbd>CTRL</kbd> Jika Sudah Selesai Service
					</div>
				</form>

				<form role="form" method="POST" action="" id="form-view">
					<div class="box-body">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Nama Mekanik</label>
									<p id="NamaMekanik"></p>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer"><button type="button" name="back" class="btn btn-primary pull-right" onClick="table_data();">Back Button</button></div>
				</form>

			</div><!-- /.box -->
		</div>
		<!--/.col (right) -->
	</div> <!-- /.row -->


</section><!-- /.content -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form role="form" method="POST" action="" id="form-kustomer" enctype="multipart/form-data">
					<div class="box-body">
						<div id="hiddenkustomer"></div>
						<div class="form-group">
							<label>Nama</label>
							<div id="Nama"></div>
						</div>
						<div class="form-group">
							<label>No Hp</label>
							<div id="NoHp"></div>
						</div>
						<div class="form-group">
							<label>Alamat</label>
							<div id="Alamat"></div>
						</div>
						<button type="button" id="savekustomer" name="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>

				<form role="form" method="POST" action="" id="form-kendaraan" enctype="multipart/form-data">
					<div class="box-body">
						<div id="hiddenkendaraan"></div>
						<div class="form-group">
							<label>NoPolisi</label>
							<div id="NoPolisi"></div>
						</div>
						<div class="form-group">
							<label>NoRangka</label>
							<div id="NoRangka"></div>
						</div>
						<div class="form-group">
							<label>NoMesin</label>
							<div id="NoMesin"></div>
						</div>
						<div class="form-group">
							<label>TipeMotor</label>
							<div id="TipeMotor"></div>
						</div>
						<button type="button" id="savekendaraan" name="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
			
		</div>
	</div>
</div>

<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<form role="form" method="POST" action="<?php echo site_url('servicetiket/save_transaksi	'); ?>" id="form-save" enctype="multipart/form-data" target="_blank">
							<div class="box-body">
								<div class="form-group">
									<label>Service Tiket</label>
									<div id="ServiceTiketID"></div>
								</div>
								<div class="form-group">
									<label>Nama Mekanik</label>
									<div id="MekanikID"></div>
								</div>
								<div class="form-group">
									<label>Nama Service</label>
									<div id="JasaServiceID"></div>
								</div>
								<button type="submit" id="savehasil" name="submit" class="btn btn-primary">Bayar</button>
							</div>
						</form>
					</div>
					<div class="col-md-6 text-right" style="font-size: 50px;">
						<p style="margin: 90px 0;"><div id="tot1"></div></p>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>


<script type="text/javascript">
	var site_url = site_url() + 'servicetiket/';

	var table;
	$(document).ready(function() {

		// $('.chosen-select').select2();
		 // $('.select2').select2();

		 table_data();

		 table = $('#table').DataTable({

		 	"processing": true,
		 	"serverSide": true,
		 	"order": [],

		 	"ajax": {
		 		"url": site_url + 'get_subservice',
		 		"type": "POST"
		 	},

		 	"columnDefs": [{
		 		"targets": [0],
		 		"orderable": false,
		 	}, ],
		 }); 

		 $('#saveservicetiket').click(function() {
		 	load('save/', $('#form-data')[0]);
		 	$('#tabel').load(refresh());
		 });

		 $('#savekustomer').click(function() {
		 	load('save/', $('#form-kustomer')[0]);
		 	form_data();
		 	$('#form-data').load(refresh());
		 });

		 $('#savekendaraan').click(function() {
		 	load('save/', $('#form-kendaraan')[0]);
		 	table_data();
			// $('#tot').html(data.total);
		});

		 // $('#savehasil').click(function() {
		 // 	// load('save_transaksi/', $('#form-save')[0]);
		 // 	$.ajax({
		 // 		url: site_url + 'save_transaksi/',
		 // 		type: "POST",
		 // 		data: new FormData($('#form-save')[0]),
		 // 		dataType: "JSON",
		 // 		contentType: false,
		 // 		cache: false,
		 // 		processData: false,
		 // 		success: function(data) {

		 // 		}
		 // 	});
		 // 	window.location = "servicetiket";
		 // 	form_data();
		 // 	$('#form-data').load(refresh());
		 // });

		 $('#form-data').ready(function() {
		 	refresh();
		 });

		 $('#tampil').click(function() {
		 	form_save();
		 });

		 $('#savehasil').click(function() {
		 	window.location = "servicetiket";
		 });

		 $('#submit').click(function() {
		 	$.ajax({
		 		url: site_url + 'save_tempo/',
		 		type: "POST",
		 		data: new FormData($('#form-tambah')[0]),
		 		dataType: "JSON",
		 		contentType: false,
		 		cache: false,
		 		processData: false,
		 		success: function(data) {
		 			if (data == "fail") {
		 				alert("Stok tidak mencukupi");
		 			}
		 			table.draw(false);
		 			$('#tot').html(data.Total);
		 			table_data();
		 		},
		 		error: function(jqXHR, textStatus, errorThrown) {
		 			Swal.fire('Warning!', 'Stok tidak mencukupi', 'error');
		 		}
		 	});
		 });

		 $('#submit').on('keyup', function() {
		 	$.ajax({
		 		url: site_url + 'totalharga/',
		 		type: "POST",
		 		dataType: "JSON",
		 		contentType: true,
		 		cache: false,
		 		processData: false,
		 		success: function(data) {
		 			$('#tot').html(data.Total);
		 			$('#tot1').html(data.Total1);
		 		},
		 	});
		 });

		});

	function table_data() {
		$('#table-data').show();
		$('#form-data').hide();
		$('#form-view').hide();
		$('#form-tambah').show();

		$('#myModal').modal('hide');
		$('.box-title').text('Mekanik List');
	}

	function form_data() {
		$('#table-data').hide();
		$('#form-data').show();
		$('#form-view').hide();

		$('#myModal').modal('hide');
	}

	function form_kustomer() {
		$('#form-kendaraan').hide();
		$('#form-save').hide();
		$('#form-kustomer').show();

		$('#myModal').modal('show');
		$('.modal-title').text('Tambah Kustomer');
	}

	function form_kendaraan() {
		$('#form-kustomer').hide();
		$('#form-save').hide();
		$('#form-kendaraan').show();

		$('#myModal').modal('show');
		$('.modal-title').text('Tambah Kendaraan');
	}

	function form_save() {
		$('#form-save').show();

		$('#Modal').modal('show');
		$('.modal-title').text('Konfirmasi Service');
	}

	function formkustomer() {
		$.ajax({
			url: site_url + 'form_kustomer/',
			cache: false,
			type: "POST",
			success: function(data) {
				form_kustomer();

				data = JSON.parse(data);
				$('#hiddenkustomer').html(data.hiddenkustomer);
				$('#hidden').html(data.hidden);
				$('#Nama').html(data.Nama);
				$('#NoHp').html(data.NoHp);
				$('#Alamat').html(data.Alamat);
			}
		});
	}

	function formkendaraan() {
		$.ajax({
			url: site_url + 'form_kendaraan/',
			cache: false,
			type: "POST",
			success: function(data) {
				form_kendaraan();

				data = JSON.parse(data);
				$('#hiddenkendaraan').html(data.hiddenkendaraan);
				$('#NoPolisi').html(data.NoPolisi);
				$('#NoMesin').html(data.NoMesin);
				$('#NoRangka').html(data.NoRangka);
				$('#TipeMotor').html(data.TipeMotor);
			}
		});
	}

	function error(data)
	{
		Swal.fire({
			icon: data.icon,
			title: data.title,
			text: data.message,
			showConfirmButton: false,
			showCloseButton: true
		});
	}

	function berhasil(data)
	{
		Swal.fire({
			icon: data.icon,
			title: data.title,
			text: data.message,
			showConfirmButton: false,
			timer: 1500
		});
	}

	function load(link, form)
	{
		$.ajax({
			url: site_url + link,
			type: "POST",
			data: new FormData(form),
			dataType: "JSON",
			contentType: false,
			cache: false,
			processData: false,
			success: function(data) {
				if (data.code == 1) {
					error(data);
				} else {
					berhasil(data);
					form.reset();
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				Swal.fire('Warning!', 'Error adding / update data', 'error');
			}
		});
	}

	function refresh()
	{
		$.ajax({
			url: site_url + 'form_data/',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data) {

				form_data();
				$('.box-title').text('Buat Service Tiket');

				$('#hiddenservicetiket').html(data.hiddenservicetiket);
				$('#js-config').html(data.jsConfig);
				$('#KustomerID').html(data.KustomerID);
				$('#KendaraanID').html(data.KendaraanID);
				$('#Keterangan').html(data.Keterangan);
				$('#formkustomer').html(data.formkustomer);
				$('#formkendaraan').html(data.formkendaraan);
				$('#servicetiket').html(data.servicetiket);

				$(".chosen-select").chosen();

				$('#form-data').reset();
				
			}
		});
	}

	function service()
	{
		$.ajax({
			url: site_url + 'form_tambah/',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data) {

				$('#form-data').hide();
				$('#table-data').show();

				$('.box-title').text('Service Management');

				$('#ItemID').html(data.ItemID);
				$('#jumlahbeli').html(data.jumlahbeli);
				$('#ServiceTiketID').html(data.ServiceTiketID);
				$('#MekanikID').html(data.MekanikID);
				$('#JasaServiceID').html(data.JasaServiceID);
				$('#tot').html(data.Total);
				$('#tot1').html(data.Total1);

				$(".chosen-select").chosen();

				$('#form-tambah').reset();
			}
		});
	}

	function cek(id)
	{
		$.ajax({
			url: site_url + 'cek/',
			data: {'JasaServiceID1': id},
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data) {
				$('#tot1').html(data.Total1);
			}
		});
	}

	function view_data(id) {
		$.post(site_url + 'view/', {
			'MekanikID': id
		}, function(data) {
			form_view();

			data = JSON.parse(data);
			$('p#hidden').html(data.hidden);
			$('p#NamaMekanik').html(data.NamaMekanik);
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alert('Error adding / update data');
		});
	}

	function myFunction(){
		/* tombol F11 */
		if(event.keyCode == 16) {
			event.preventDefault()
			$('#table-data').hide();
			$('#form-data').show();
			$('.box-title').text('Buat Service Tiket');
		}

		if(event.keyCode == 17) {
			event.preventDefault()
			$('#form-data').hide();
			$('#table-data').show();
			service();
			$('.box-title').text('Service Management');
		}
	}

	function delete_data(id) {
		Swal.fire({
			title: 'Apa anda yakin ?',
			text: "Data akan dihapus dari database !",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!',
			showLoaderOnConfirm: true,
			preConfirm: function() {
				return new Promise(function(resolve) {
					$.ajax({
						url: site_url + 'delete/',
						data: {
							'SubServiceID': id
						},
						type: "POST",
						dataType: 'json'
					})
					.done(function(data) {
						Swal.fire({
							icon: data.icon,
							title: 'Deleted!',
							text: data.message,
							showConfirmButton: false,
							timer: 1500
						});
						if (data.code == 0) {
							table.draw(false);
							$('#tot').html(data.total);
						}
						table_data();
					})
					.fail(function() {
						Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
					});
				});
			},
			allowOutsideClick: false
		});
	}
	

</script>
