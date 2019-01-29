<body class="login">
    <div class="user-login-5">
        <div class="row bs-reset">
            <div class="col-md-6 bs-reset">
                <div class="login-bg" style="background-image:url('<?php echo base_url(); ?>assets/apps/img/home.png');background-position:left;"></div>
            </div>
            <div class="col-md-6 login-container bs-reset">
                <div class="login-content">
                    <h1><?php echo ADMIN; ?></h1>
                    <?php echo form_open('login/index',array('id'=>'login','class'=>'login-form')); ?>
                        <div class="row">
                            <div class="col-xs-6">
                                <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Email" id="user_email" name="user_email" required/>
                            </div>
                            <div class="col-xs-6">
                                <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" id="user_password" name="user_password" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button class="btn green" type="submit">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="login-footer">
                    <div class="row bs-reset">
                        <div class="col-xs-5 bs-reset">
                        </div>
                        <div class="col-xs-7 bs-reset">
                            <div class="login-copyright text-right">
                                <p><?php echo APP; ?> &copy; 2019</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    	$(document).ready(function(){
    	    $("#login").validate({
    	    	rules: {
    	        	user_email: {
    	          		required: true,
    	        	},
    	        	user_password: {
    	          		required: true,
    	        	},
    	      	},
    	      	messages: {
    	        	user_email: {
    	          		required: 'Email harus diisi',
    	        	},
    	        	user_password: {
    	          		required: 'Password harus diisi',
    	        	},
    	      	},
    	      	errorElement: 'span',
                errorPlacement: function (error,element) {
                	error.addClass('help-block help-block-error');
                    error.insertAfter(element);
                    element.parent().addClass('has-error');
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).parent().addClass('has-error');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parent().removeClass('has-error');
                },
    	    });
      	});
    </script>