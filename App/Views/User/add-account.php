{% extends "base.php" %}

{% block title %} {{ title }} {% endblock %}

{% block body %}
 <div class="right_col" role="main">
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Add Bank Account</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />
            <form  action="{{ baseurl }}user/add-bank-account" id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Bank Name <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="bank" required="">
                  	<option selected="" disabled="">Select Bank</option>
                  	<option value="Barclays">Barclays</option>
                  	<option value="Co-Operative">Co-Operative</option>
                  	<option value="UBA">UBA</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Account Number <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="last-name" name="acc_number" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				  <button class="btn btn-primary" type="reset">Reset</button>
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
</div>
{% endblock %}