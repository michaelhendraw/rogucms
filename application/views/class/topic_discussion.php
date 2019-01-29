<div class="table-toolbar">
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">Diskusi</h3>
            <div class="clearfix">
                <a class="btn btn-success" href="<?php echo base_url().'classes/'.$class_id.'/subject/'.$subject_id.'/topic/index'; ?>">
                    <i class="fa fa-chevron-circle-left"></i> Kembali ke Topik
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body" id="chats">
                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                <div class="scroller" style="overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                    <ul class="chats">
                        <?php
                            if($discussions!=0){
                                foreach($discussions as $discussion){
                                    if($discussion['user_id']!=0){
                                        echo '  <li class="in">';
                                        $name = getColumnBy('name','public.user','id='.$discussion['user_id']);
                                    } else {
                                        echo '  <li class="out">';
                                        $name = getColumnBy('name','public.student','id='.$discussion['student_id']);
                                    }
                                    echo '          <img class="avatar" alt="" src="'.base_url().'assets/apps/img//user_thumb.png">
                                                    <div class="message">
                                                        <span class="arrow"> </span>
                                                        <span style="font-weight:600;">'.$name.'</span>
                                                        <span class="datetime"> at '.date('d F Y H:i:s', strtotime($discussion['date'])).' </span>
                                                        <span class="body"> '.$discussion['description'].' </span>
                                                    </div>
                                                </li>';
                                }
                            }
                        ?>
                    </ul>
                </div>
                <div class="slimScrollBar" style="background: rgb(187, 187, 187); width: 7px; position: absolute; top: 149px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 376.023px;"></div>
                <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div>
            </div>
            <div class="chat-form">
                <?php echo form_open('#',array('id'=>'form','class'=>'form-horizontal')); ?>
                    <div class="input-cont">
                        <input class="form-control" type="text" placeholder="Masukkan atau komentari diskusi..." id="description" name="description">
                    </div>
                    <div class="btn-cont">
                        <span class="arrow"> </span>
                        <button type="submit" class="btn blue icn-only">
                            <i class="fa fa-send icon-white"></i>
                        </button>
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
                description: {
                  required: true,
                },
            },
            messages: {
                description: {
                  required: 'Harus diisi',
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
                post_data.append('description',$('#description').val());
                post_data.append('<?php echo $this->security->get_csrf_token_name(); ?>','<?php echo $this->security->get_csrf_hash(); ?>');

                $.ajax({
                  type: 'POST',
                  url: "<?php echo base_url().'classes/'.$class_id.'/subject/'.$subject_id.'/topic/'.$topic_id.'/discussion/add/'.$class_discussion_id; ?>",
                  data: post_data,
                  processData: false,
                  contentType: false,
                  success: function(data){
                    if(data != 0) {
                      swal({
                        title: '<h4>Diskusi Gagal Ditambah!</h4>',
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
                        title: '<h4>Diskusi Berhasil Ditambah!</h4>',
                        text: 'Selamat, diskusi berhasil ditambah.',
                        imageUrl: "<?php echo base_url(); ?>assets/apps/img/success.png",
                        html: true,
                        confirmButtonColor:'#2ECB71'
                      },
                      function(){
                        window.location.href = "<?php echo base_url().'classes/'.$class_id.'/subject/'.$subject_id.'/topic/'.$topic_id.'/discussion'; ?>";
                      });
                    }
                  }
                });
                return false;
            }
        });
    });
</script>