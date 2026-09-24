 <!-- Left side column. contains the sidebar -->
 <?php
    $chatUnread = 0;
    if (session()->get('level') == 2) {
        $chatModel = new \App\Models\M_chat();
        $chatUnread = $chatModel->count_unread_admin(session()->get('sesi_user'));
    }
    ?>
 <aside class="main-sidebar">
     <!-- sidebar: style can be found in sidebar.less -->
     <section class="sidebar">
         <!-- Sidebar user panel -->
         <div class="user-panel">
             <div class="pull-left image">
                 <img src="<?= base_url('fotouser/' . session()->get('foto_user')) ?>" class="img-circle" alt="User Image">
             </div>
             <div class="pull-left info">
                 <p><?= session()->get('nama_lengkap') ?></p>
                 <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
             </div>
         </div>

         <!-- sidebar menu: : style can be found in sidebar.less -->
         <ul class="sidebar-menu" data-widget="tree">
             <li class="header">MAIN NAVIGATION</li>

             <?php if (session()->get('level') == 0) { ?>
                 <li>
                     <a href="<?= base_url('home_superadmin/index') ?>">
                         <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                     </a>
                 </li>
                 <li class="header">KELOLA PLATFORM</li>
                 <li>
                     <a href="<?= base_url('superadmin_kelola_data/toko') ?>">
                         <i class="fa fa-building"></i> <span>Data Toko</span>
                     </a>
                 </li>
                 <li class="treeview">
                     <a href="#">
                         <i class="fa fa-gears"></i>
                         <span>Kebijakan Platform</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         <li><a href="<?= base_url('superadmin_kebijakan_platform/faq') ?>"><i class="fa fa-circle-o"></i>FAQ (Bantuan)</a></li>
                         <li><a href="<?= base_url('superadmin_kebijakan_platform/syarat') ?>"><i class="fa fa-circle-o"></i>Syarat &amp; Ketentuan</a></li>
                     </ul>
                 </li>
             <?php } ?>

             <?php if (session()->get('level') == 1) { ?>
                 <li>
                     <a href="<?= base_url('home_pemilik/index') ?>">
                         <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                     </a>
                 </li>
             <?php } ?>

             <?php if (session()->get('level') == 2) { ?>
                 <li>
                     <a href="<?= base_url('home_admin/index') ?>">
                         <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                     </a>
                 </li>
                 <li class="header">CHAT NAVIGATION</li>
                 <li>
                     <a href="<?= base_url('chat_admin_pembeli/index') ?>">
                         <i class="fa fa-comments"></i> <span>Chat Pembeli</span>
                         <?php if ($chatUnread > 0): ?>
                             <small class="label pull-right bg-red"><?= $chatUnread ?></small>
                         <?php endif; ?>
                     </a>
                 </li>
             <?php } ?>

             <?php if (session()->get('level') == 1) { ?>
                 <li class="header">MASTER DATA NAVIGATION</li>
                 <li class="treeview">
                     <a href="#">
                         <i class="fa fa-folder"></i>
                         <span>Data Master</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
<ul class="treeview-menu">
                          <li><a href="<?= base_url('pemilik_kelola_data/jenis') ?>"><i class="fa fa-circle-o"></i>Data Jenis Produk</a></li>
                          <?php if (getUserPreference('tampilkan_varian')) : ?>
                              <li><a href="<?= base_url('pemilik_kelola_data/varian') ?>"><i class="fa fa-circle-o"></i>Data Varian Produk</a></li>
                          <?php endif; ?>
                          <li><a href="<?= base_url('pemilik_kelola_data/satuan_produk') ?>"><i class="fa fa-circle-o"></i>Data Satuan Produk</a></li>
                          <li><a href="<?= base_url('pemilik_kelola_data/produk') ?>"><i class="fa fa-circle-o"></i>Data Produk</a></li>
                          <li><a href="<?= base_url('pemilik_kelola_data/bank') ?>"><i class="fa fa-circle-o"></i>Data Bank</a></li>
                      </ul>
                  </li>

                  <li class="header">PROMO NAVIGATION</li>
                  <li class="treeview">
                      <a href="#">
                          <i class="fa fa-bullhorn"></i>
                          <span>Banner Promo</span>
                          <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                          </span>
                      </a>
                      <ul class="treeview-menu">
                          <li><a href="<?= base_url('pemilik_kelola_data/banner') ?>"><i class="fa fa-circle-o"></i>Data Banner Promo</a></li>
                          <li><a href="<?= base_url('pemilik_kelola_data/data_dihapus_banner') ?>"><i class="fa fa-circle-o"></i>Banner Dihapus</a></li>
                      </ul>
                  </li>


                 <li class="header">SETTING NAVIGATION</li>
                 <li class="treeview">
                     <a href="#">
                         <i class="fa fa-folder"></i>
                         <span>Data Setting Gudang</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         <li><a href="<?= base_url('pemilik_kelola_website/index') ?>"><i class="fa fa-circle-o"></i>Setting Gudang</a></li>
                         <li><a href="<?= base_url('pemilik_kelola_website/backup_db') ?>"><i class="fa fa-circle-o"></i>Backup DB</a></li>
                     </ul>
                 </li>


                 <li class="header">USERS NAVIGATION</li>
                 <li class="treeview">
                     <a href="#">
                         <i class="fa fa-folder"></i>
                         <span>Data User Toko</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         <li><a href="<?= base_url('pemilik_kelola_user/user') ?>"><i class="fa fa-circle-o"></i>Data User Toko</a></li>
                         <li><a href="<?= base_url('pemilik_kelola_user/kurir') ?>"><i class="fa fa-circle-o"></i>Data Kurir Toko</a></li>
                         <li><a href="<?= base_url('pemilik_kelola_user/pelanggan') ?>"><i class="fa fa-circle-o"></i>Data Pelanggan Toko</a></li>
                     </ul>
                 </li>

                 <li class="header">KEBIJAKAN NAVIGATION</li>
                 <li class="treeview">
                     <a href="#">
                         <i class="fa fa-folder"></i>
                         <span>Kebijakan Toko</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         <li><a href="<?= base_url('kebijakan_toko/faq') ?>"><i class="fa fa-circle-o"></i>Data FAQ (Bantuan)</a></li>
                         <li><a href="<?= base_url('kebijakan_toko/syarat') ?>"><i class="fa fa-circle-o"></i>Syarat &amp; Ketentuan</a></li>
                     </ul>
                 </li>
             <?php } ?>

             <?php if (session()->get('level') == 2) { ?>
                 <li class="header">PRODUCT NAVIGATION</li>
                 <li>
                     <a href="<?= base_url('admin_kelola_data/view_produk') ?>">
                         <i class="fa fa-folder"></i> <span>Daftar Produk</span>
                     </a>
                 </li>
             <?php } ?>

             <?php if (session()->get('level') == 2) { ?>
                 <li class="header">STOCK NAVIGATION</li>
                 <li class="treeview">
                     <a href="#">
                         <i class="fa fa-folder"></i>
                         <span>Data Stok Produk</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         <li><a href="<?= base_url('admin_kelola_data/stok') ?>"><i class="fa fa-circle-o"></i>Stok Produk</a></li>
                         <li><a href="<?= base_url('admin_kelola_data/v_tot_stok') ?>">
                                 <i class="fa fa-circle-o"></i><span>Total Stok Produk</span>
                             </a>
                         </li>
                     </ul>
                 </li>
             <?php } ?>

             <?php if (session()->get('level') == 2) { ?>
                 <li class="header">REPORT NAVIGATION</li>
                 <li class="treeview">
                     <a href="#">
                         <i class="fa fa-folder"></i>
                         <span>Laporan Stok Produk</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         <li><a href="<?= base_url('admin_laporan_mingguan/laporan_stok') ?>"><i class="fa fa-circle-o"></i>Laporan Stok Mingguan</a></li>
                         <li><a href="<?= base_url('admin_laporan_bulanan/laporan_stok') ?>"><i class="fa fa-circle-o"></i>Laporan Stok Bulanan</a></li>
                     </ul>
                 </li>
             <?php } ?>

             <?php if (session()->get('level') == 2) { ?>
                 <li class="header">PRODUCT NAVIGATION</li>
                 <li>
                     <a href="<?= base_url('admin_kelola_data/beli') ?>">
                         <i class="fa fa-folder"></i> <span>Pembelian Produk</span>
                     </a>
                 </li>
             <?php } ?>

             <?php if (session()->get('level') == 2) { ?>
                 <li class="header">PRODUCT NAVIGATION</li>
                 <li>
                     <a href="<?= base_url('admin_kelola_data/bayar') ?>">
                         <i class="fa fa-folder"></i> <span>Pembayaran Pelanggan</span>
                     </a>
                 </li>
             <?php } ?>

             <?php if (session()->get('level') == 2) { ?>
                 <li class="header">KEBIJAKAN NAVIGATION</li>
                 <li class="treeview">
                     <a href="#">
                         <i class="fa fa-folder"></i>
                         <span>Kebijakan Toko</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         <li><a href="<?= base_url('kebijakan_toko/faq') ?>"><i class="fa fa-circle-o"></i>Data FAQ (Bantuan)</a></li>
                         <li><a href="<?= base_url('kebijakan_toko/syarat') ?>"><i class="fa fa-circle-o"></i>Syarat &amp; Ketentuan</a></li>
                     </ul>
                 </li>
             <?php } ?>

         </ul>
     </section>
     <!-- /.sidebar -->
 </aside>

 <!-- =============================================== -->