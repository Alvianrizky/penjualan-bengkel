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
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<button id="create" class="btn btn-primary btn-sm" title="Data Create" alt="Data Create"><i class="glyphicon glyphicon-plus"></i> Tambah Kendaraan</button>
								</div>
							</div>
						</div>

						<div class="table-responsive" id="table-responsive">
							<table id="table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th style="width: 100px!important;">Action</th>
										<th>No</th>
										<th>NoPolisi</th>
										<th>NoRangka</th>
										<th>NoMesin</th>
										<th>TipeMotor</th>
									</tr>
								</thead>
								<tbody>
								</tbody>

								<tfoot>
									<tr>
										<th>Action</th>
										<th>No</th>
										<th>NoPolisi</th>
										<th>NoRangka</th>
										<th>NoMesin</th>
										<th>TipeMotor</th>
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
									<label>NoPolisi</label>
									<p id="NoPolisi"></p>
								</div>
								<div class="form-group">
									<label>NoRangka</label>
									<p id="NoRangka"></p>
								</div>
								<div class="form-group">
									<label>NoMesin</label>
									<p id="NoMesin"></p>
								</div>
								<div class="form-group">
									<label>TipeMotor</label>
									<p id="TipeMotor"></p>
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
	var site_url = site_url() + 'kendaraan/';

	var table;
	$(document).ready(function() {

		table_data();

		table = $('#table').DataTable({

			"processing": true,
			"serverSide": true,
			"order": [],

			"ajax": {
				"url": site_url + 'get_kendaraan',
				"type": "POST"
			},

			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ],
		});

		$('#create').click(function() {
			$.post(site_url + 'form_data/', function(data) {
				form_data();
				$('.box-title').text('Tambah Kendaraan');

				data = JSON.parse(data);
				$('#hidden').html(data.hidden);
				$('#NoPolisi').html(data.NoPolisi);
				$('#NoMesin').html(data.NoMesin);
				$('#NoRangka').html(data.NoRangka);
				$('#TipeMotor').html(data.TipeMotor);
			});
		});

		$('#btn-cetak').click(function() {
			$.ajax({
				url: site_url + 'cetak/',
				type: "POST",
				data: new FormData($('#form-filter')[0]),
				dataType: "JSON",
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {
					if (data.code == 1) {
						$('#notifications').append(data.message);
					} else {
						$('#notifications').append(data.message);
						table_data();
						table.draw(false);
					}
				}
			});
		});

		$('#submit').click(function() {
			$.post(site_url + 'save_kendaraan/', $('#form-data').serialize(), function(data) {
					if (data.code == 1) {
						$('#notifications').append(data.message);
					} else {
						$('#notifications').append(data.message);
						table_data();
						table.draw(false);
					}
				}, 'json')
				.fail(function(jqXHR, textStatus, errorThrown) {
					alert('Error adding / update data');
				});
		});

	});

	function table_data() {
		$('#table-data').show();
		$('#form-data').hide();
		$('#form-view').hide();

		$('.box-title').text('Kendaraan List');
	}

	function form_data() {
		$('#hidden').empty();
		$('#NoPolisi').empty();
		$('#NoMesin').empty();
		$('#NoRangka').empty();
		$('#TipeMotor').empty();

		$('#table-data').hide();
		$('#form-data').show();
		$('#form-view').hide();
	}

	function form_view() {
		$('p#hidden').empty();
		$('p#NoPolisi').empty();
		$('p#NoMesin').empty();
		$('p#NoRangka').empty();
		$('p#TipeMotor').empty();

		$('#table-data').hide();
		$('#form-data').hide();
		$('#form-view').show();

		$('.box-title').text('View Kendaraan');
	}

	function view_data(id) {
		$.post(site_url + 'view/', {
				'KendaraanID': id
			}, function(data) {
				form_view();

				data = JSON.parse(data);
				$('p#hidden').html(data.hidden);
				$('p#NoPolisi').html(data.NoPolisi);
				$('p#NoMesin').html(data.NoMesin);
				$('p#NoRangka').html(data.NoRangka);
				$('p#TipeMotor').html(data.TipeMotor);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
			});
	}

	function update_data(id) {
		$.post(site_url + 'form_data/', {
				'KendaraanID': id
			}, function(data) {
				form_data();
				$('.box-title').text('Update Kendaraan');

				data = JSON.parse(data);
				$('#hidden').html(data.hidden);
				$('#NoPolisi').html(data.NoPolisi);
				$('#NoMesin').html(data.NoMesin)
				$('#NoRangka').html(data.NoRangka);
				$('#TipeMotor').html(data.TipeMotor);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
			});
	}

	function delete_data(id) {
		var agree = confirm("Are you sure you want to delete this item?");
		if (agree) {
			$.post(site_url + 'delete/', {
					'KendaraanID': id
				}, function(data) {
					$('#notifications').append(data.message);
					if (data.code == 0) table.draw(false);
					table_data();
				}, 'json')
				.fail(function(jqXHR, textStatus, errorThrown) {
					alert('Error adding / update data');
				});
		} else
			return false;
	}
</script>
