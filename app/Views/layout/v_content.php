   <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
       <!-- Content Header (Page header) -->
       <section class="content-header">
           <h1>
               <?= $title ?>
           </h1>
           <ol class="breadcrumb">
               <li><a href="#"><i class="fa fa-dashboard"></i><?= $title2 ?></a></li>
               <li class="active"><?= $title ?></li>
           </ol>
       </section>

       <!-- Main content -->
       <section class="content">
           <?php
            if ($isi) {
                echo view($isi);
            }
            ?>
       </section>
       <!-- /.content -->
   </div>
   <!-- /.content-wrapper -->

   <footer class="main-footer">
       <strong>Copyright &copy; <?php echo date("Y"); ?></strong> All rights
       reserved.
   </footer>

   <!-- Control Sidebar -->
   <aside class="control-sidebar control-sidebar-dark">
       <!-- Create the tabs -->
       <ul class="nav nav-tabs nav-justified control-sidebar-tabs">

           <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
       </ul>
       <!-- Tab panes -->
       <div class="tab-content">
           <!-- Home tab content -->
           <div class="tab-pane" id="control-sidebar-home-tab">
               <!-- /.control-sidebar-menu -->

           </div>
           <!-- /.tab-pane -->
           <!-- Stats tab content -->

   </aside>
   <!-- /.control-sidebar -->
   <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
   <div class="control-sidebar-bg"></div>
   </div>
   <!-- ./wrapper -->

   <!-- jQuery 3 -->
   <script src="<?= base_url() ?>/template_admin/bower_components/jquery/dist/jquery.min.js"></script>
   <!-- Bootstrap 3.3.7 -->
   <script src="<?= base_url() ?>/template_admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
   <!-- SlimScroll -->
   <script src="<?= base_url() ?>/template_admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
   <!-- FastClick -->
   <script src="<?= base_url() ?>/template_admin/bower_components/fastclick/lib/fastclick.js"></script>
   <!-- AdminLTE App -->
   <script src="<?= base_url() ?>/template_admin/dist/js/adminlte.min.js"></script>
   <!-- DataTables -->
   <script src="<?= base_url() ?>/template_admin/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
   <script src="<?= base_url() ?>/template_admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
   <!-- AdminLTE for demo purposes -->
   <script src="<?= base_url() ?>/template_admin/dist/js/demo.js"></script>

   <script>
       $(document).ready(function() {
           $('.sidebar-menu').tree()
       })
   </script>

   <!-- Script slide up alert window otomatis -->
   <script>
       window.setTimeout(function() {
           $(".alert").fadeTo(500, 0).slideUp(500, function() {
               $(this).remove();
           });
       }, 3000);
   </script>

   <!-- AdminLTE data tables -->
   <script>
       $(document).ready(function() {
           var table = $('#example1').DataTable({
               "stateSave": true,
               "lengthMenu": [
                   [10, 25, 50, -1],
                   [10, 25, 50, "All"]
               ],
               "pageLength": 10,
               "paging": true,
               "lengthChange": true,
               "searching": true,
               "ordering": true,
               "info": true,
               "autoWidth": false
           });

           // Optional: Tombol edit arahkan langsung ke halaman edit
           $('#example1 tbody').on('click', '.btn-edit', function() {
               var row = $(this).closest('tr');
               var idPoli = row.find('td:eq(0)').text();
               window.location.href = "<?= base_url('') ?>" + idPoli;
           });
       });
   </script>
   
   <!-- Script tampil gambar -->
   <script>
       function bacaGambar(input) {
           if (input.files && input.files[0]) {
               var reader = new FileReader();
               reader.onload = function(e) {
                   $('#gambar_load').attr('src', e.target.result);
               }
               reader.readAsDataURL(input.files[0]);
           }
       }
       $('#preview_gambar').change(function() {
           bacaGambar(this);
       });
   </script>

   <!-- Script hide password -->
   <script>
       function myFunction() {
           var x = document.getElementById("ShowPass");
           if (x.type === "password") {
               x.type = "text";

           } else {
               x.type = "password";
           }
       }
   </script>

   <!-- Script slide up alert window otomatis -->
   <script>
       window.setTimeout(function() {
           $(".alert").fadeTo(500, 0).slideUp(500, function() {
               $(this).remove();
           });
       }, 3000);
   </script>

   <!-- SweetAlert2 -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
   <script>
       function logoutConfirm() {
           Swal.fire({
               title: 'Keluar Sistem?',
               html: '<strong><?= session()->get('nama_lengkap') ?></strong>, Anda yakin ingin keluar?',
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Ya, Keluar!',
               cancelButtonText: 'Batal'
           }).then((result) => {
               if (result.isConfirmed) {
                   window.location.href = "<?= base_url('auth/logout_user') ?>";
               }
           });
       }
   </script>

    <!-- Auto Logout Script -->
    <?php if (session()->get('user_logged_in')): ?>
    <script>
    (function() {
        var timeoutMinutes = 15;
        var warningMinutes = 1;
        var logoutUrl = "<?= base_url('auth/logout_user') ?>";
        var timeout, warningTimeout, countdownInterval;
        var countdown = warningMinutes * 60;

        function resetTimer() {
            clearTimeout(timeout);
            clearTimeout(warningTimeout);
            clearInterval(countdownInterval);
            countdown = warningMinutes * 60;
            timeout = setTimeout(function() {
                showWarning();
            }, timeoutMinutes * 60 * 1000);
        }

        function showWarning() {
            countdown = warningMinutes * 60;
            Swal.fire({
                title: 'Sesi Akan Berakhir!',
                html: 'Anda akan logout otomatis dalam <strong id="countdown-timer">1:00</strong> menit.<br>Klik tombol di bawah untuk tetap login.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tetap Login',
                cancelButtonText: 'Logout',
                allowOutsideClick: false,
                allowEscapeKey: false,
                timer: warningMinutes * 60 * 1000,
                timerProgressBar: true,
                willOpen: function() {
                    var timerEl = document.getElementById('countdown-timer');
                    countdownInterval = setInterval(function() {
                        countdown--;
                        var mins = Math.floor(countdown / 60);
                        var secs = countdown % 60;
                        timerEl.textContent = mins + ':' + (secs < 10 ? '0' : '') + secs;
                    }, 1000);
                },
                preConfirm: function() {
                    clearInterval(countdownInterval);
                }
            }).then(function(result) {
                if (result.dismiss === Swal.DismissReason.timer || result.isDismissed) {
                    window.location.href = logoutUrl;
                } else if (result.isConfirmed) {
                    resetTimer();
                }
            });
        }

        ['mousemove', 'keypress', 'click', 'scroll', 'touchstart'].forEach(function(evt) {
            document.addEventListener(evt, resetTimer, { passive: true });
        });

        resetTimer();
    })();
    </script>
    <?php endif; ?>

    <?= view('layout_toko/v_csrf_script') ?>

    </body>

    </html>