 {% extends "base.php" %}

{% block title %} {{ title }} {% endblock %}

{% block body %}
 <!-- page content -->
        <div class="right_col" role="main">

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>All User Payments</h2>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>DOB</th>
                          <th>Progress</th>
                        </tr>
                      </thead>


                      <tbody>
                        {% for payment in payments %}

                            {% set pecrcent_paid = 0 %}
                            {% set bal = 0 %}
                            {% set cost = 1 %}
                            {% if payment['balance'] >  0 %}
                              {% set bal = payment['balance'] * -1 %}
                            {% endif %}
                            {% if payment['cost'] >  0 %}
                              {% set cost = payment['cost'] %}
                            {% endif %}

                            {% set percent_paid = (bal/cost) * 100 %}

                          <tr>
                            <td>{{ payment['fname'] }}</td>
                            <td>{{ payment['lname'] }}</td>
                            <td>{{ payment['email'] }}</td>
                            <td>{{ payment['phone'] }}</td>
                            <td>{{ payment['dob'] }}</td>
                            <td>
                            <div class="">
                              <div class="progress progress_sm" style="width: 100%;">
                                <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{ percent_paid }}"></div>
                              </div>{{ percent_paid }}%
                            </div>
                            </td>
                          </tr>
                        {% endfor %}
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
          </div>

        <!-- /page content -->
<script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
{% endblock %}