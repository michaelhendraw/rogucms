<h3 class="page-title">Tambah Materi</h3>
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
                                <input id="name" name="name" type="text" class="form-control" onchange="check_name(this.value);" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Deskripsi
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <textarea id="description" name="description" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Topik
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <select id="topic_id" name="topic_id" class="form-control">
                                  <?php
                                    foreach($topics as $topic) {
                                        if($topic['id'] == $topic_id){
                                            echo '<option value="'.$topic['id'].'">'.$topic['name'].'</option>';
                                        }
                                    }
                                  ?>
                                </select>
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
                                        if($subject['id'] == $subject_id){
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
                                <input id="sequence" name="sequence" type="number" class="form-control" />
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
        // $('.wysihtml5').wysihtml5({stylesheets:false});

        $("#form").validate({
            rules: {
                name: {
                  required: true,
                },
                description: {
                  required: true,
                },
                subject_id: {
                  required: true,
                },
                topic_id: {
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
                description: {
                  required: 'Nama harus diisi',
                },
                topic_id: {
                  required: 'Topik harus dipilih',
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
                post_data.append('name',$('#name').val());
                post_data.append('description',$('#description').val());
                post_data.append('topic_id',$('#topic_id').val());
                post_data.append('subject_id',$('#subject_id').val());
                post_data.append('sequence',$('#sequence').val());
                post_data.append('<?php echo $this->security->get_csrf_token_name(); ?>','<?php echo $this->security->get_csrf_hash(); ?>');

                $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url()."subject/".$subject_id."/topic/".$topic_id."/material/insert"; ?>',
                  data: post_data,
                  processData: false,
                  contentType: false,
                  success: function(data){
                    if(data != 0) {
                      swal({
                        title: '<h4>Materi Gagal Ditambah!</h4>',
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
                        title: '<h4>Materi Berhasil Ditambah!</h4>',
                        text: 'Selamat, materi berhasil ditambah.',
                        imageUrl: "<?php echo base_url(); ?>assets/apps/img/success.png",
                        html: true,
                        confirmButtonColor:'#2ECB71'
                      },
                      function(){
                        window.location.href = '<?php echo base_url()."subject/".$subject_id."/topic/".$topic_id."/material"; ?>';
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
            $.ajax({
                type:'POST',
                url:'<?php echo base_url()."subject/".$subject_id."/topic/".$topic_id."/material/check_name"; ?>',
                data: {
                  '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                  'name':name
                },
                success: function(data) {
                  if(data != 0){
                    $('#name').val('');
                    swal({
                      title: "<h4>Materi Gagal Ditambah!</h4>",
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