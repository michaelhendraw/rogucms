<?php
    $row = 0;
    $option = 5; // A, B, C, D, E
?>
<h3 class="page-title">Tambah Latihan UN</h3>
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
                            <label class="control-label col-md-3">Status
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <select id="status" name="status" class="form-control">
                                  <option value="">--- Pilih Status ---</option>
                                  <?php
                                    foreach($quiz_statuses as $key => $value) {
                                      echo '<option value="'.$key.'">'.$value.'</option>';
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                            <div class="table-container col-md-12">
                                <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%"> No </th>
                                            <th width="45%"> Pertanyaan & Kunci dan Jawaban</th>
                                            <th width="45%"> Materi & Pembahasan </th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="Tr<?php echo $row; ?>" role="row" class="filter">
                                            <td>
                                                <input id="No<?php echo $row; ?>" name="detail[<?php echo $row; ?>][no]" data-row="<?php echo $row; ?>" value="<?php echo ($row+1); ?>" type="text" class="form-control form-filter input-sm text-center" readonly>
                                            </td>
                                            <td>
                                                <textarea id="QuizDetailQuestion<?php echo $row; ?>" name="detail[<?php echo $row; ?>][question]" class="form-control QuizDetailQuestion" rows="5"></textarea>
                                                <div class="mt-radio-list">
                                                    <?php
                                                        for($o=0; $o<$option; $o++){
                                                            echo '<label class="mt-radio mt-radio-outline">
                                                                    <input id="QuizDetailCorrectAnswer'.$row.'" name="detail['.$row.'][correct_answer]" value="'.$o.'" class="form-control QuizDetailCorrectAnswer" type="radio">
                                                                    <textarea id="QuizDetailAnswer'.$row.'_'.$o.'" name="detail['.$row.'][answer]['.$o.']" class="form-control QuizDetailAnswer" rows="1"></textarea>
                                                                    <span></span>
                                                                </label>';
                                                        }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-md-12">
                                                    <select id="QuizDetailMaterialID<?php echo $row; ?>" name="detail[<?php echo $row; ?>][material_id]" class="form-control QuizDetailMaterialID">
                                                        <?php echo getMaterialBySubject($subject_id,0); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <br/><textarea id="QuizDetailSolution<?php echo $row; ?>" name="detail[<?php echo $row; ?>][solution]" class="form-control QuizDetailSolution" rows="5"></textarea>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="margin-bottom-5">
                                                    <button id="Minus<?php echo $row; ?>" class="btn btn-sm red btn-outline margin-bottom" onclick="delRow(<?php echo $row; ?>);"><i class="fa fa-close"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="detailFooter">
                                            <td colspan="4">
                                              <input id="row" type="hidden" value="<?php echo $row; ?>">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm green filter-cancel" onclick="addRow();"><i class="fa fa-plus"></i> Tambah Soal</button>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                            <div class="form-group">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button id="submit" type="submit" class="btn green">Simpan</button>
                                </div>
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
                description: {
                  required: true,
                },
                subject_id: {
                  required: true,
                },
                status: {
                  required: true,
                },
            },
            messages: {
                name: {
                  required: 'Nama harus diisi',
                },
                description: {
                  required: 'Deskripsi harus diisi',
                },
                subject_id: {
                  required: 'Mapel harus dipilih',
                },
                status: {
                  required: 'Status harus dipilih',
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
                post_data.append('subject_id',$('#subject_id').val());
                post_data.append('status',$('#status').val());
                post_data.append('<?php echo $this->security->get_csrf_token_name(); ?>','<?php echo $this->security->get_csrf_hash(); ?>');

                var rows = Number($('#row').val());
                for(i=0; i<=rows; i++){
                    if($('#QuizDetailQuestion'+i).val()!=undefined){
                        post_data.append('detail['+i+'][question]',$('#QuizDetailQuestion'+i).val());
                        post_data.append('detail['+i+'][correct_answer]',$("input[name='detail["+i+"][correct_answer]']:checked").val());
                        post_data.append('detail['+i+'][material_id]',$('#QuizDetailMaterialID'+i).val());
                        post_data.append('detail['+i+'][solution]',$('#QuizDetailSolution'+i).val());
                        for(j=0; j< <?php echo $option; ?>; j++){
                            post_data.append('detail['+i+'][answer]['+j+']',$('#QuizDetailAnswer'+i+'_'+j).val());
                        }
                    }
                }

                $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url()."subject/".$subject_id."/quiz/insert"; ?>',
                  data: post_data,
                  processData: false,
                  contentType: false,
                  success: function(data){
                    if(data != 0) {
                      swal({
                        title: '<h4>Latihan UN Gagal Ditambah!</h4>',
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
                        title: '<h4>Latihan UN Berhasil Ditambah!</h4>',
                        text: 'Selamat, latihan UN berhasil ditambah.',
                        imageUrl: "<?php echo base_url(); ?>assets/apps/img/success.png",
                        html: true,
                        confirmButtonColor:'#2ECB71'
                      },
                      function(){
                        window.location.href = '<?php echo base_url()."subject/".$subject_id."/quiz"; ?>';
                      });
                    }
                  }
                });
                return false;
            }
        });
        ruleDetail();
    });
    
    function check_name(name) {
        if(name!=''){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url()."subject/".$subject_id."/quiz/check_name"; ?>',
                data: {
                  '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                  'name':name
                },
                success: function(data) {
                  if(data != 0){
                    $('#name').val('');
                    swal({
                      title: "<h4>Latihan UN Gagal Ditambah!</h4>",
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

    function addRow(){
        var row = Number($('#row').val())+1;
        var html='<tr id="Tr'+row+'" role="row" class="filter"><td><input id="No'+row+'" name="detail['+row+'][no]" data-row="'+row+'" value="'+(row+1)+'" type="text" class="form-control form-filter input-sm text-center" readonly></td><td><textarea id="QuizDetailQuestion'+row+'" name="detail['+row+'][question]" class="form-control QuizDetailQuestion" rows="5"></textarea><div class="mt-radio-list">';
        for(o=0; o<<?php echo $option; ?>; o++){
            html+='<label class="mt-radio mt-radio-outline"><input id="QuizDetailCorrectAnswer'+row+'" name="detail['+row+'][correct_answer]" value="'+o+'" class="form-control QuizDetailCorrectAnswer" type="radio"><textarea id="QuizDetailAnswer'+row+'_'+o+'" name="detail['+row+'][answer]['+o+']" class="form-control QuizDetailAnswer" rows="1"></textarea><span></span></label>';
        }
        html+='</div></td><td><div class="col-md-12"><select id="QuizDetailMaterialID'+row+'" name="detail['+row+'][material_id]" class="form-control QuizDetailMaterialID"><?php echo getMaterialBySubject($subject_id,0); ?></select></div><div class="col-md-12"><br/><textarea id="QuizDetailSolution'+row+'" name="detail['+row+'][solution]" class="form-control QuizDetailSolution" rows="5"></textarea></div></td><td><div class="margin-bottom-5"><button id="Minus'+row+'" class="btn btn-sm red btn-outline margin-bottom" onclick="delRow('+row+');"><i class="fa fa-close"></i></button></div></td></tr>';

        $('#detailFooter').before(html);
        $('#row').val(row);
        ruleDetail();
    }

    function delRow(row){
        if($('tr[id^=Tr]').length > 1){
            $('#Tr'+row).detach();
        }else{
            alert('Detail tidak boleh kosong');
        }
    }

    function ruleDetail() {
        $('.QuizDetailQuestion').each(function(){
            $(this).rules('add',{
                required: true,
                messages:{
                    required:'Pertanyaan harus diisi',
                }
            });
        });
        $('.QuizDetailCorrectAnswer').each(function(){
            $(this).rules('add',{
                required: true,
                messages:{
                    required:'Kunci jawaban harus dipilih',
                }
            });
        });
        $('.QuizDetailAnswer').each(function(){
            $(this).rules('add',{
                required: true,
                messages:{
                    required:'Jawaban harus diisi',
                }
            });
        });
        $('.QuizDetailMaterialID').each(function(){
            $(this).rules('add',{
                required: true,
                messages:{
                    required:'Materi harus dipilih',
                }
            });
        });
        $('.QuizDetailSolution').each(function(){
            $(this).rules('add',{
                required: true,
                messages:{
                    required:'Pembahasan harus diisi',
                }
            });
        });
    }
</script>