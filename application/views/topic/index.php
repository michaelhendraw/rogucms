<h3 class="page-title">Topik</h3>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="clearfix">
                                <a class="btn btn-success" href="<?php echo base_url().'subject'; ?>">
                                    <i class="fa fa-chevron-circle-left"></i> Kembali ke Mapel
                                </a>
                                <a class="btn btn-danger" href="<?php echo base_url().'subject/'.$subject_id.'/topic/add'; ?>">
                                    <i class="icon-plus"></i> Tambah Topik
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-success" onclick="reload_table();">
                                    <i class="icon-refresh"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <table id="table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Mapel</th>
                            <th>Urutan</th>
                            <th style="width:200px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Mapel</th>
                            <th>Urutan</th>
                            <th style="width:200px;">Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var table;

    $(document).ready(function(e) {
        //datatables
        table = $('#table').DataTable({ 
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": '<?php echo site_url("subject/$subject_id/topic"); ?>',
                "type": "POST"
            },
        
            //Set column definition initialisation properties.
            "columnDefs": [{ 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },],

        });

        //datepicker
        // $('.datepicker').datepicker({
        //     autoclose: true,
        //     format: "yyyy-mm-dd",
        //     todayHighlight: true,
        //     orientation: "top auto",
        //     todayBtn: true,
        //     todayHighlight: true,  
        // });

        //set input/textarea/select event when change value, remove class error and remove text help block 
        $("input").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
        // $("textarea").change(function(){
        //     $(this).parent().parent().removeClass('has-error');
        //     $(this).next().empty();
        // });
        $("select").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
    });

    function reload_table() {
        table.ajax.reload(null,false); //reload datatable ajax 
    }
</script>
