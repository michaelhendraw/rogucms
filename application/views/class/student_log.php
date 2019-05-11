<?php
    $start = $progress = strtotime($date.'-01');
    $end = strtotime(date("Y-m-t", $start));

    $datesShow = array();
    $dates = array();
    while($progress <= $end){
        $datesShow[] = date('d M Y', $progress);
        foreach($types as $type => $type_name){
            $today = date('Y-m-d', $progress);
            $dates[$type][$today] = 0;
        }
        $progress = strtotime("+1 day", $progress);
    }
    $dateShow = '"'.implode('","', $datesShow).'"';

    foreach($dates as $type => $date_count){
        foreach($date_count as $d => $c){
            if(!empty($logs)){
                foreach($logs as $l){
                    if($l['date']==$d && $l['type']==$type){
                        $dates[$type][$d]=$l['count'];
                    }
                }
            }
        }
    }

    $show = array();
    $countAll = array();
    foreach($types as $type => $type_name){
        $start = $progress = strtotime($date.'-01');
        $end = strtotime(date("Y-m-t", $start));
        $val = array();
        $countAll[$type] = 0;
        while($progress <= $end){
            $today = date('Y-m-d', $progress);
            $val[]=$dates[$type][$today];
            $progress = strtotime("+1 day", $progress);
            $countAll[$type]+=$dates[$type][$today];
        }
        $show[$type_name] = implode(',',$val);
    }
?>
<h3 class="page-title">Log Aktivitas Siswa</h3>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-body">
                <?php echo form_open_multipart("classes/$class_id/student/log/$student_id", array('id' => 'form', 'class' => 'form-horizontal')); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">NISN</label>
                            <div class="col-md-4">
                                <span class="form-control"><?php echo isset($student['nisn']) ? $student['nisn'] : '-'; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama</label>
                            <div class="col-md-4">
                                <span class="form-control"><?php echo isset($student['name']) ? $student['name'] : '-'; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kelas</label>
                            <div class="col-md-4">
                                <span class="form-control"><?php echo isset($student['class_id']) ? getColumnBy('name','class','id='.$student['class_id']) : '-'; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Bulan
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-4">
                                <input id="date_" name="date" type="text" class="form-control datepicker" data-date-format="yyyy-mm" value="<?php echo isset($date) ? $date : date('Y-m'); ?>" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                            <div class="form-group">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button id="submit" type="submit" class="btn green">Tampilkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="portlet-body">
                    <div id="highchart_student_logs" style="height:500px;"></div>
                    <div class="form-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="text-center">
                                    <?php
                                        foreach($countAll as $t => $c){
                                            echo $types[$t] . ' sebanyak ' . $c . ' kali<br/>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker();

        $("#form").validate({
            rules: {
                date: {
                  required: true,
                },
            },
            messages: {
                date: {
                  required: 'Bulan harus dipilih',
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

        $("#highchart_student_logs").highcharts({
            chart:{
                style:{
                    fontFamily:"Open Sans"
                }
            },
            title:{
                text:"Log Aktivitas Siswa",
                x:-20
            },
            xAxis:{
                categories:[
                    <?php echo $dateShow; ?>
                ]
            },
            yAxis:{
                tickInterval: 1,
                title:{
                    text:"Jumlah"
                },
                plotLines:[
                    {
                        value:0,
                        width:1,
                        color:"#808080"
                    }
                ]
            },
            tooltip:{
                valueSuffix:" kali"
            },
            legend:{
                layout:"vertical",
                align:"right",
                verticalAlign:"middle",
                borderWidth:0
            },
            series:[
                <?php
                    foreach($show as $n => $d) {
                        echo "{name:'$n',data:[$d]},";
                    }
                ?>
            ]
        })
    });
</script>