<h3 class="page-title">Ubah Latihan UN Kelas</h3>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-body">
                <?php echo form_open_multipart("#", array('id' => 'form', 'class' => 'form-horizontal')); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Latihan UN
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="id" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>" />
                                <input id="class_subject_id" name="class_subject_id" type="hidden" value="<?php echo isset($data['class_subject_id']) ? $data['class_subject_id'] : ''; ?>" />
                                <select id="quiz_id" name="quiz_id" class="form-control">
                                    <option value="">--- Pilih Latihan UN ---</option>
                                    <?php
                                        foreach($quizes as $quiz) {
                                            echo '<option value="'.$quiz['id'].'" '.set_select('quiz_id',$quiz['id'],isset($data['quiz_id']) && $data['quiz_id']==$quiz['id'] ? TRUE : FALSE).'>'.$quiz['name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tanggal Dimulai
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="open_date" name="open_date" type="text" class="form-control datetimepicker" data-date-format="yyyy-mm-dd hh:ii:ss" value="<?php echo isset($data['open_date']) ? date('Y-m-d H:i:s',strtotime($data['open_date'])) : date('Y-m-d'); ?>" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tanggal Berakhir
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="close_date" name="close_date" type="text" class="form-control datetimepicker" data-date-format="yyyy-mm-dd hh:ii:ss" value="<?php echo isset($data['close_date']) ? date('Y-m-d H:i:s',strtotime($data['close_date'])) : date('Y-m-d',strtotime('+7 days')); ?>" readonly />
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
        $('.datetimepicker').datetimepicker();

        $("#form").validate({
            rules: {
                quiz_id: {
                  required: true,
                },
                open_date: {
                  required: true,
                },
                close_date: {
                  required: true,
                },
            },
            messages: {
                quiz_id: {
                  required: 'Latihan UN harus dipilih',
                },
                open_date: {
                  required: 'Tanggal Dimulai harus dipilih',
                },
                close_date: {
                  required: 'Tanggal Berakhir harus dipilih',
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
                post_data.append('class_subject_id',$('#class_subject_id').val());
                post_data.append('quiz_id',$('#quiz_id').val());
                post_data.append('open_date',$('#open_date').val());
                post_data.append('close_date',$('#close_date').val());
                post_data.append('<?php echo $this->security->get_csrf_token_name(); ?>','<?php echo $this->security->get_csrf_hash(); ?>');

                $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url()."classes/".$class_id."/subject/".$subject_id."/quiz/update"; ?>',
                  data: post_data,
                  processData: false,
                  contentType: false,
                  success: function(data){
                    if(data != 0) {
                      swal({
                        title: '<h4>Latihan UN Kelas Gagal Diedit!</h4>',
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
                        title: '<h4>Latihan UN Kelas Berhasil Diedit!</h4>',
                        text: 'Selamat, latihan UN kelas berhasil diedit.',
                        imageUrl: "<?php echo base_url(); ?>assets/apps/img/success.png",
                        html: true,
                        confirmButtonColor:'#2ECB71'
                      },
                      function(){
                        window.location.href = '<?php echo base_url()."classes/".$class_id."/subject/".$subject_id."/quiz"; ?>';
                      });
                    }
                  }
                });
                return false;
            }
        });
    });
</script>