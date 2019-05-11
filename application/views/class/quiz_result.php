<?php
    $show = array();
    foreach($result as $r){
        $show[$r['quiz_detail_id']][$r['result']] = $r['count'];
    }

    for($i=1; $i<=$data['question_number']; $i++){
        if(!array_key_exists(0, $show[$i])){
            $show[$i][0]=0;
        }
        if(!array_key_exists(1, $show[$i])){
            $show[$i][1]=0;
        }
    }
?>

<h3 class="page-title">Hasil Evaluasi Latihan UN Siswa</h3>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-body">
                <?php echo form_open_multipart("#", array('id' => 'form', 'class' => 'form-horizontal')); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Latihan UN</label>
                            <div class="col-md-4">
                                <span class="form-control"><?php echo isset($data['quiz_id']) ? getColumnBy('name','quiz','id='.$data['quiz_id']) : '-'; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tanggal Dimulai</label>
                            <div class="col-md-4">
                                <span class="form-control"><?php echo isset($data['open_date']) ? date('d F Y H:i:s', strtotime($data['open_date'])) : '-'; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tanggal Berakhir</label>
                            <div class="col-md-4">
                                <span class="form-control"><?php echo isset($data['close_date']) ? date('d F Y H:i:s', strtotime($data['close_date'])) : '-'; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jumlah Soal</label>
                            <div class="col-md-4">
                                <span class="form-control"><?php echo isset($data['question_number']) ? $data['question_number']: '-'; ?></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <div id="gchart_quiz_results" style="height:500px;"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Soal ke-', 'Benar'],
            <?php
                foreach($show as $soal => $bs){
                    echo "['Soal ke-".$soal."',".$bs[1]."],";
                }
            ?>
        ]);

        var options = {
            chart: {
                title: 'Hasil Evaluasi Latihan UN Siswa',
            },
            hAxis: {
                viewWindow: {
                    min: 0,
                    max: 50,
                },
            }
        };

        var chart = new google.charts.Bar(document.getElementById('gchart_quiz_results'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>