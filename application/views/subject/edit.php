<h3 class="page-title">Ubah Mapel</h3>
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
                            <label class="control-label col-md-3">Gambar
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4 text-center">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <?php
                                            if(isset($data['image'])) {
                                                echo '<img src="'.base_url().'uploads/subject/'.$data['image'].'?t='.time().'" alt="'.$data['name'].'">';
                                            } else {
                                                echo '<img src="'.base_url().'assets/apps/img/no-image.png" alt="Belum Ada Gambar" />';
                                            }
                                        ?>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                    <div>
                                        <span class="btn default btn-file">
                                        <span class="fileinput-new"> Pilih Gambar </span>
                                        <span class="fileinput-exists"> Ubah Gambar </span>
                                        <input id="image" name="image" type="file"> </span>
                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Hapus Gambar </a>
                                    </div>
                                </div>
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
                }
            },
            messages: {
                name: {
                  required: 'Nama harus diisi',
                }
            },
            errorElement: 'span',
            errorPlacement: function (error,element) {
                if(element.prop("type") === "file"){
                  element.addClass("input-error");
                  error.addClass("small-text label-error text-center");
                  error.insertAfter('#image_btn');
                  swal({
                    title: "<h4>Mapel Gagal Ditambah!</h4>",
                    text: "Maaf, image harus dipilih.",
                    imageUrl: "<?php echo base_url(); ?>assets/apps/img/error.png",
                    html: true,
                    confirmButtonColor:"#F14635"
                  });          
                }else{
                    error.addClass('help-block help-block-error');
                    error.insertAfter(element);
                    element.parent().addClass('has-error');
                }
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
                post_data.append('image',$('input[name=image]')[0].files[0]);
                post_data.append('<?php echo $this->security->get_csrf_token_name(); ?>','<?php echo $this->security->get_csrf_hash(); ?>');

                $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url(); ?>subject/update',
                  data: post_data,
                  processData: false,
                  contentType: false,
                  success: function(data){
                    if(data != 0) {
                      swal({
                        title: '<h4>Mapel Gagal Diedit!</h4>',
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
                        title: '<h4>Mapel Berhasil Diedit!</h4>',
                        text: 'Selamat, mapel berhasil diedit.',
                        imageUrl: "<?php echo base_url(); ?>assets/apps/img/success.png",
                        html: true,
                        confirmButtonColor:'#2ECB71'
                      },
                      function(){
                        window.location.href = "<?php echo base_url(); ?>subject";
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
                url:'<?php echo base_url(); ?>subject/check_name',
                data: {
                  '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                  'name':name,
                  'id':id,
                },
                success: function(data) {
                  if(data != 0){
                    $('#name').val('');
                    swal({
                      title: "<h4>Mapel Gagal Diedit!</h4>",
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