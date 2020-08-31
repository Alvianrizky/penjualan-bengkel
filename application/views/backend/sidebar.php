<!-- Sidebar user panel -->
<div class="user-panel">
	<div class="pull-left image">
		<img src="<?php echo base_url() . 'assets/'; ?>dist/img/sofia.png" class="img-circle" alt="User Image">
	</div>
	<div class="pull-left info">
		<p><?php echo $this->session->userdata('first_name'); ?></p>
		<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
	</div>
</div>

<!-- search form
<form action="#" method="get" class="sidebar-form">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
        </span>
    </div>
</form>
 /.search form -->

<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
	<li class="header">MENU UTAMA</li>
	<li>
		<a href="<?php echo site_url('dashboard'); ?>">
			<i class="fa fa-dashboard"></i> <span>Dashboard</span>
		</a>
	</li>
	<li>
		<a href="<?php echo site_url('subpembelian'); ?>">
			<i class="fa fa-users"></i> <span>Aplikasi Pembelian</span>
		</a>
	</li>
	<li class="treeview">
		<a href="#">
			<i class="fa fa-users"></i>
			<span>Data Master </span>
			<span class="label label-primary pull-right">5</span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo site_url('kustomer'); ?>"><i class="fa fa-circle-o"></i> Data Kustomer</a></li>
			<li><a href="<?php echo site_url('mekanik'); ?>"><i class="fa fa-circle-o"></i> Data Mekanik</a></li>
			<li><a href="<?php echo site_url('kendaraan'); ?>"><i class="fa fa-circle-o"></i> Data Kendaraan</a></li>
			<li><a href="<?php echo site_url('item'); ?>"><i class="fa fa-circle-o"></i> Data Item</a></li>
			<li><a href="<?php echo site_url('jasaservice'); ?>"><i class="fa fa-circle-o"></i> Data Jasa Service</a></li>
		</ul>
	</li>
	<li class="treeview">
		<a href="#">
			<i class="fa fa-users"></i>
			<span>Data Transaksi </span>
			<span class="label label-primary pull-right">5</span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo site_url('pembelian'); ?>"><i class="fa fa-circle-o"></i> Data Pembelian</a></li>
			<li><a href="<?php echo site_url('penjualan'); ?>"><i class="fa fa-circle-o"></i> Data Penjualan</a></li>
		</ul>
	</li>
	
	<li class="treeview">
		<a href="#">
			<i class="fa fa-users"></i>
			<span>Users Management </span>
			<span class="label label-primary pull-right">2</span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo site_url('users'); ?>"><i class="fa fa-circle-o"></i> Users</a></li>
			<li><a href="<?php echo site_url('groups'); ?>"><i class="fa fa-circle-o"></i> Groups</a></li>
		</ul>
	</li>
	<li class="header"></li>
	<li>
		<a href="<?php echo site_url('auth/logout'); ?>">
			<i class="fa fa-power-off"></i> <span>Logout</span>
		</a>
	</li>

</ul>
