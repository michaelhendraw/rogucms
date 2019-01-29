<h3 class="page-title">Profil Saya</h3>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-body">
                <?php echo form_open_multipart("#", array('id' => 'form', 'class' => 'form-horizontal')); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Email
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="id" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>" />
                                <input id="email" name="email" type="text" class="form-control" onchange="check_email(this.value);" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Password
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="password" name="password" type="password" class="form-control" />
                                <span class="help-block">(Kosongkan bila password tidak diganti)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="name" name="name" type="text" class="form-control" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tempat Lahir
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="pob" name="pob" type="text" class="form-control" value="<?php echo isset($data['pob']) ? $data['pob'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tanggal Lahir
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="dob" name="dob" type="text" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="<?php echo isset($data['dob']) ? $data['dob'] : date('Y-m-d',strtotime('-20 years')); ?>" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jenis Kelamin
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <select id="gender" name="gender" class="form-control">
                                  <option value="">--- Pilih Jenis Kelamin ---</option>
                                  <?php
                                    foreach($genders as $key => $value) {
                                        echo '<option value="'.$key.'" '.(isset($data['gender']) && $data['gender']==$key ? 'selected' : '').'>'.$value.'</option>';
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button id="submit" type="submit" class="btn green">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker();

        $("#form").validate({
            rules: {
                email: {
                  required: true,
                },
                name: {
                  required: true,
                },
                pob: {
                  required: true,
                },
                dob: {
                  required: true,
                },
                gender: {
                  required: true,
                },
            },
            messages: {
                email: {
                  required: 'Email harus diisi',
                },
                name: {
                  required: 'Nama harus diisi',
                },
                pob: {
                  required: 'Tempat Lahir harus diisi',
                },
                dob: {
                  required: 'Tanggal Lahir harus dipilih',
                },
                gender: {
                  required: 'Jenis Kelamin harus dipilih',
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
            submitHandler: function () {
                $('#submit').hide();
                $.blockUI({message:"<?php loading(); ?>"});

                var post_data = new FormData();
                post_data.append('email',$('#email').val());
                post_data.append('password',$('#password').val());
                post_data.append('name',$('#name').val());
                post_data.append('pob',$('#pob').val());
                post_data.append('dob',$('#dob').val());
                post_data.append('gender',$('#gender').val());
                post_data.append('<?php echo $this->security->get_csrf_token_name(); ?>','<?php echo $this->security->get_csrf_hash(); ?>');

                $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url(); ?>user/update_profile',
                  data: post_data,
                  processData: false,
                  contentType: false,
                  success: function(data){
                    if(data != 0) {
                      swal({
                        title: '<h4>Pengguna Gagal Diedit!</h4>',
                        text: data,
                        imageUrl: "<?php echo base_url(); ?>assets/apps/img/error.png",
                        html: true,
                        confirmButtonColor:'#F14635'
                      });
                      $('#submit').show();
                      $.unblockUI();
                    }
                    else {
                      swal({
                        title: '<h4>Pengguna Berhasil Diedit!</h4>',
                        text: 'Selamat, pengguna berhasil diedit.',
                        imageUrl: "<?php echo base_url(); ?>assets/apps/img/success.png",
                        html: true,
                        confirmButtonColor:'#2ECB71'
                      },
                      function(){
                        window.location.href = "<?php echo base_url(); ?>user/profile";
                      });
                    }
                  }
                });
                return false;
            }
        });
    });

    function check_email(email) {
        if(email!=''){
            var id = $('#id').val();
            $.ajax({
                type:'POST',
                url:'<?php echo base_url(); ?>user/check_email',
                data: {
                  '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                  'email':email,
                  'id':id,
                },
                success: function(data) {
                  if(data != 0){
                    $('#email').val('');
                    swal({
                      title: "<h4>Pengguna Gagal Diedit!</h4>",
                      text: "Maaf, email sudah ada",
                      imageUrl: "<?php echo base_url(); ?>assets/apps/img/error.png",
                      html: true,
                      confirmButtonColor:"#F14635"
                    });
                  }
                }
            });
        }
    }
</script>