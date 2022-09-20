<div class="wrapper mb-5">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    %5$s
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin/" class="brand-link">
      <img src="http://cops.jhugues.fr/wp-content/plugins/hj-cops/web/rsc/img/cops_logo.png" alt="COPS" class="brand-image" style="opacity: .8">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="display: block">
        <div class="image">
          <img src="/wp-content/plugins/hj-cops/web/rsc/img/masks/mask-%3$s.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <span class="d-block">%4$s</span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      %1$s
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 862px; background: transparent;">
    <!-- Content Header (Page header) -->
    %6$s

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        %2$s
      </div>
      <!--/. container-fluid -->

      <div class="modal fade" id="modal-confirm">
        <div class="modal-dialog">
          <div class="modal-content" style="margin-top: 125px;">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <p></p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
              <button type="button" class="btn btn-primary"><a href="" class="text-white">Confirmer</a></button>
            </div>
          </div>
        </div>
      </div>
      <!--/. modal -->

    </section>
    <!-- /.content -->



    <div class="toast-container position-absolute p-3" id="toastPlacement" style="top: 0;right: 0;z-index: 99999;"></div>
    <!-- /.toast-container -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer" style="height:56px;">
    <strong>To Protect And To Serve</strong>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.22.04.27
    </div>
    <script type="text/javascript">var ajaxurl = 'http://cops.jhugues.fr/wp-admin/admin-ajax.php';</script>
  </footer>
  <div id="sidebar-overlay"></div>
</div>
