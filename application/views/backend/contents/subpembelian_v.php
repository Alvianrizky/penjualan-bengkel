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
				</div><!-- /.box-header -->

				<div id="table-data">
					<div class="box-body">
						<div class="row">
							<div class="col-lg-8">
								<div class="box-body">
									<div class="row">
										<div class="col-lg-8">
											<form role="form" method="POST" action="" id="form-tambah">
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
											</form>
											<form role="form" method="POST" action="<?php echo site_url('subpembelian/save_transaksi'); ?>" id="form-save" enctype="multipart/form-data" target="_blank">
												<button type="button" name="submit" id="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Tambah Data</button> &nbsp; &nbsp;
												<button type="submit" name="submit" id="savehasil" class="btn btn-primary">Bayar Pesanan</button> &nbsp; &nbsp;
											</form>
										</div>
									</div>
								</div>
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
				</div>

				<form role="form" method="POST" action="" id="form-data" enctype="multipart/form-data">
					<div class="box-body">
						<div class="row">
							<div class="col-lg-6">
								<div id="hidden"></div>
								<div class="form-group">
									<label>Nama Mekanik</label>
									<div id="NamaMekanik"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" name="submit" id="submit" class="btn btn-primary">Submit Data</button> &nbsp; &nbsp;
						<button type="reset" name="reset" class="btn btn-default">Reset Data</button>

						<button type="button" name="back" class="btn btn-primary pull-right" onClick="table_data();">Back Button</button>
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


<script type="text/javascript">
	var site_url = site_url() + 'subpembelian/';

	var table;
	$(document).ready(function() {

		table_data();

		table = $('#table').DataTable({

			"processing": true,
			"serverSide": true,
			"order": [],

			"ajax": {
				"url": site_url + 'get_subpembelian',
				"type": "POST"
			},

			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ],
		});

		$('#form-tambah').ready(function() {
			$.ajax({
				url: site_url + 'form_tambah/',
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data) {

					$('#form-data').hide();
					$('#table-data').show();

					$('.box-title').text('Aplikasi Pembelian ');

					$('#ItemID').html(data.ItemID);
					$('#jumlahbeli').html(data.jumlahbeli);
					$('#ServiceTiketID').html(data.ServiceTiketID);
					$('#MekanikID').html(data.MekanikID);
					$('#tot').html(data.Total);

					$(".chosen-select").chosen();

					$('#form-tambah').reset();
				}
			});
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
				},
			});
		});

		$('#savehasil').click(function() {
			window.location = "subpembelian";
		});

	});

	function table_data() {
		$('#table-data').show();
		$('#form-data').hide();
		$('#form-view').hide();

		$('.box-title').text('Aplikasi Pembelian');
	}

	function form_data() {
		$('#hidden').empty();
		$('#NamaMekanik').empty();

		$('#table-data').hide();
		$('#form-data').show();
		$('#form-view').hide();
	}

	function form_view() {
		$('p#hidden').empty();
		$('p#NamaMekanik').empty();

		$('#table-data').hide();
		$('#form-data').hide();
		$('#form-view').show();

		$('.box-title').text('View Mekanik');
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
							'SubPembelianID': id
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
