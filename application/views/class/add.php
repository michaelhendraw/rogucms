<?php
    $rowStudent = 0;
    $rowSubject = 0;
?>
<h3 class="page-title">Tambah Kelas</h3>
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
                                <input id="name" name="name" type="text" class="form-control" />
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
                                    foreach($statuses as $key => $value) {
                                      echo '<option value="'.$key.'">'.$value.'</option>';
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                            <div class="table-container col-md-6">
                                <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="90%"> Siswa </th>
                                            <th width="10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="TrStudent<?php echo $rowStudent; ?>" role="row" class="filter">
                                            <td>
                                                <select id="ClassStudentStudentID<?php echo $rowStudent; ?>" name="student[<?php echo $rowStudent; ?>][student_id]" class="form-control ClassStudentStudentID">
                                                    <?php echo getStudent(0); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="margin-bottom-5">
                                                    <button id="MinusStudent<?php echo $rowStudent; ?>" class="btn btn-sm red btn-outline margin-bottom" onclick="delRowStudent(<?php echo $rowStudent; ?>);"><i class="fa fa-close"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="studentFooter">
                                            <td colspan="3">
                                              <input id="row_student" type="hidden" value="<?php echo $rowStudent; ?>">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm green filter-cancel" onclick="addRowStudent();"><i class="fa fa-plus"></i> Tambah Siswa</button>
                            </div>
                            <div class="table-container col-md-6">
                                <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="45%"> Mapel </th>
                                            <th width="45%"> Guru </th>
                                            <th width="10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="TrSubject<?php echo $rowSubject; ?>" role="row" class="filter">
                                            <td>
                                                <select id="ClassSubjectSubjectID<?php echo $rowSubject; ?>" name="subject[<?php echo $rowSubject; ?>][subject_id]" class="form-control ClassSubjectSubjectID">
                                                    <?php echo getSubject(0); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select id="ClassSubjectUserID<?php echo $rowSubject; ?>" name="subject[<?php echo $rowSubject; ?>][user_id]" class="form-control ClassSubjectUserID">
                                                    <?php echo getTeacher(0); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="margin-bottom-5">
                                                    <button id="MinusSubject<?php echo $rowSubject; ?>" class="btn btn-sm red btn-outline margin-bottom" onclick="delRowSubject(<?php echo $rowSubject; ?>);"><i class="fa fa-close"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="subjectFooter">
                                            <td colspan="3">
                                              <input id="row_subject" type="hidden" value="<?php echo $rowSubject; ?>">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm green filter-cancel" onclick="addRowSubject();"><i class="fa fa-plus"></i> Tambah Mapel</button>
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
                status: {
                  required: true,
                },
            },
            messages: {
                name: {
                  required: 'Nama harus diisi',
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
                post_data.append('status',$('#status').val());
                post_data.append('<?php echo $this->security->get_csrf_token_name(); ?>','<?php echo $this->security->get_csrf_hash(); ?>');

                var rowsSubject = Number($('#row_subject').val());
                for(i=0; i<=rowsSubject; i++){
                    if($('#ClassSubjectSubjectID'+i).val()!=undefined){
                        post_data.append('subject['+i+'][subject_id]',$('#ClassSubjectSubjectID'+i).val());
                        post_data.append('subject['+i+'][user_id]',$('#ClassSubjectUserID'+i).val());
                    }
                }

                var rowsStudent = Number($('#row_student').val());
                for(i=0; i<=rowsStudent; i++){
                    if($('#ClassStudentStudentID'+i).val()!=undefined){
                        post_data.append('student['+i+'][student_id]',$('#ClassStudentStudentID'+i).val());
                    }
                }

                $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url(); ?>classes/insert',
                  data: post_data,
                  processData: false,
                  contentType: false,
                  success: function(data){
                    if(data != 0) {
                      swal({
                        title: '<h4>Kelas Gagal Ditambah!</h4>',
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
                        title: '<h4>Kelas Berhasil Ditambah!</h4>',
                        text: 'Selamat, kelas berhasil ditambah.',
                        imageUrl: "<?php echo base_url(); ?>assets/apps/img/success.png",
                        html: true,
                        confirmButtonColor:'#2ECB71'
                      },
                      function(){
                        window.location.href = "<?php echo base_url(); ?>classes";
                      });
                    }
                  }
                });
                return false;
            }
        });
        ruleStudent();
        ruleSubject();
    });
    
    function check_name(name) {
        if(name!=''){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url(); ?>classes/check_name',
                data: {
                  '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                  'name':name
                },
                success: function(data) {
                  if(data != 0){
                    $('#name').val('');
                    swal({
                      title: "<h4>Kelas Gagal Ditambah!</h4>",
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
    
    function addRowSubject(){
        var row = Number($('#row_subject').val())+1;
        var html='<tr id="TrSubject'+row+'" role="row" class="filter"><td><select id="ClassSubjectSubjectID'+row+'" name="subject['+row+'][subject_id]" class="form-control ClassSubjectSubjectID"><?php echo getSubject(0); ?></select></td><td><select id="ClassSubjectUserID'+row+'" name="subject['+row+'][user_id]" class="form-control ClassSubjectUserID"><?php echo getTeacher(0); ?></select></td><td><div class="margin-bottom-5"><button id="MinusSubject'+row+'" class="btn btn-sm red btn-outline margin-bottom" onclick="delRowSubject('+row+');"><i class="fa fa-close"></i></button></div></td>';

        $('#subjectFooter').before(html);
        $('#row_subject').val(row);
        ruleSubject();
    }

    function delRowSubject(row){
        if($('tr[id^=TrSubject]').length > 1){
            $('#TrSubject'+row).detach();
        }else{
            alert('Mapel tidak boleh kosong');
        }
    }

    function ruleSubject() {
        $('.ClassSubjectSubjectID').each(function(){
            $(this).rules('add',{
                required: true,
                messages:{
                    required:'Mapel harus dipilih',
                }
            });
        });

        $('.ClassSubjectUserID').each(function(){
            $(this).rules('add',{
                required: true,
                messages:{
                    required:'Guru harus dipilih',
                }
            });
        });
    }

    function addRowStudent(){
        var row = Number($('#row_student').val())+1;
        var html='<tr id="TrStudent'+row+'" role="row" class="filter"><td><select id="ClassStudentStudentID'+row+'" name="student['+row+'][student_id]" class="form-control ClassStudentStudentID"><?php echo getStudent(0); ?></select></td><td><div class="margin-bottom-5"><button id="MinusStudent'+row+'" class="btn btn-sm red btn-outline margin-bottom" onclick="delRowStudent('+row+');"><i class="fa fa-close"></i></button></div></td></tr>';

        $('#studentFooter').before(html);
        $('#row_student').val(row);
        ruleStudent();
    }

    function delRowStudent(row){
        if($('tr[id^=TrStudent]').length > 1){
            $('#TrStudent'+row).detach();
        }else{
            alert('Siswa tidak boleh kosong');
        }
    }

    function ruleStudent() {
        $('.ClassStudentStudentID').each(function(){
            $(this).rules('add',{
                required: true,
                messages:{
                    required:'Siswa harus dipilih',
                }
            });
        });
    }
</script>