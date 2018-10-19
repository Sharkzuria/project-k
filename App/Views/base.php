<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    {% set baseurl = "/" %}
    <title>{% block title %}{% endblock %}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="images/favicon.ico" type="image/ico" />
    <!-- Bootstrap -->
    <link href="{{baseurl}}vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{baseurl}}vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{baseurl}}vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{baseurl}}vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="{{baseurl}}vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{baseurl}}vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{baseurl}}vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{baseurl}}build/css/custom.min.css" rel="stylesheet">
</head>
<body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Gentelella Alela!</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="{{baseurl}}images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>John Doe</h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />
    {% include "sidebar.php"  %}
    {% include "topnav.php"  %}
    {% block body %}
    	<!-- page data would be rendered -->
    {% endblock %}
    <!-- footer content -->
        <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="{{baseurl}}vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="{{baseurl}}vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="{{baseurl}}vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="{{baseurl}}vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="{{baseurl}}vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="{{baseurl}}vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{baseurl}}vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="{{baseurl}}vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="{{baseurl}}vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="{{baseurl}}vendors/Flot/jquery.flot.js"></script>
    <script src="{{baseurl}}vendors/Flot/jquery.flot.pie.js"></script>
    <script src="{{baseurl}}vendors/Flot/jquery.flot.time.js"></script>
    <script src="{{baseurl}}vendors/Flot/jquery.flot.stack.js"></script>
    <script src="{{baseurl}}vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="{{baseurl}}vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="{{baseurl}}vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="{{baseurl}}vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="{{baseurl}}vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="{{baseurl}}vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="{{baseurl}}vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="{{baseurl}}vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{baseurl}}vendors/moment/min/moment.min.js"></script>
    <script src="{{baseurl}}vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{baseurl}}build/js/custom.min.js"></script>
</body>
</html>
