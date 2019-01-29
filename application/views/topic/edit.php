<h3 class="page-title">Ubah Topik</h3>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-body">
                <?php echo form_open_multipart("#", array('id' => 'form', 'class' => 'form-horizontal')); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="id" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>" />
                                <input id="name" name="name" type="text" class="form-control" onchange="check_name(this.value);" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Mapel
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <select id="subject_id" name="subject_id" class="form-control">
                                  <?php
                                    foreach($subjects as $subject) {
                                        if($subject['id'] == $data['subject_id']){
                                            echo '<option value="'.$subject['id'].'">'.$subject['name'].'</option>';
                                        }
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Urutan
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="sequence" name="sequence" type="number" class="form-control" value="<?php echo isset($data['sequence']) ? $data['sequence'] : ''; ?>" />
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
        $("#form").validate({
            rules: {
                name: {
                  required: true,
                },
                subject_id: {
                  required: true,
                },
                sequence: {
                  required: true,
                },
            },
            messages: {
                name: {
                  required: 'Nama harus diisi',
                },
                subject_id: {
                  required: 'Mapel harus dipilih',
                },
                sequence: {
                  required: 'Urutan harus diisi',
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
                post_data.append('id',$('#id').val());
                post_data.append('name',$('#name').val());
                post_data.append('subject_id',$('#subject_id').val());
                post_data.append('sequence',$('#sequence').val());
                post_data.append('<?php echo $this->security->get_csrf_token_name(); ?>','<?php echo $this->security->get_csrf_hash(); ?>');

                $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url()."subject/".$subject_id."/topic/update/".$data['id']; ?>',
                  data: post_data,
                  processData: false,
                  contentType: false,
                  success: function(data){
                    if(data != 0) {
                      swal({
                        title: '<h4>Topik Gagal Diedit!</h4>',
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
                        title: '<h4>Topik Berhasil Diedit!</h4>',
                        text: 'Selamat, topik berhasil diedit.',
                        imageUrl: "<?php echo base_url(); ?>assets/apps/img/success.png",
                        html: true,
                        confirmButtonColor:'#2ECB71'
                      },
                      function(){
                        window.location.href = '<?php echo base_url()."subject/".$subject_id."/topic"; ?>';
                      });
                    }
                  }
                });
                return false;
            }
        });
    });
    
    function check_name(name) {
        if(name!=''){
            var id = $('#id').val();
            $.ajax({
                type:'POST',
                url:'<?php echo base_url()."subject/".$subject_id."/topic/check_name"; ?>',
                data: {
                  '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                  'name':name,
                  'id':id,
                },
                success: function(data) {
                  if(data != 0){
                    $('#name').val('');
                    swal({
                      title: "<h4>Topik Gagal Diedit!</h4>",
                      text: "Maaf, nama sudah ada",
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