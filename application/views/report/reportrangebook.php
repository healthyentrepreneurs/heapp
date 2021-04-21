<script src="<?= base_url() ?>/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/assets/validate/formValidation.min.js"></script>
<script src="<?= base_url() ?>/assets/validate/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/print.css" type="text/css" media="print" />
<script src="<?= base_url() ?>/assets/validate/moment.min.js"></script>
<script src="<?= base_url() ?>/assets/validate/print.min.js"></script>
<script src="<?= base_url() ?>/assets/js/notify.min.js"></script>
<div class="panel-body" id="client_one">
    <?php
    // print_array($surveydatas);
    ?>
    <div class="row">
        <form id="timeperclient" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Start Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="dateragestarttime">
                        <input type="text" name="dateragestarttime" id="dateragestarttimen" class="form-control">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <label class="col-sm-1 control-label">
                    End Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="daterageendtime">
                        <input type="text" class="form-control" name="daterageendtime" id="daterageendtimen">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1">
                    <button class="btn btn-blue btn-sm" type="submit">
                        Load By Date&nbsp;<i class="fa fa-arrow-circle-right"></i>
                    </button>
                </div>
                <div class="col-sm-2">
                    <div class="btn-group pull-right">
                        <button data-toggle="dropdown" class="btn btn-green dropdown-toggle btn-sm">
                            Export <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-light pull-right">
                            <li><a href="#" id="exportpdf" onclick="removepigi()">
                                    Save as PDF </a></li>
                            <li>
                                <a href="<?= base_url('excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'write.xls'); ?>" download>Export to Excel</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
        <!--END-->
    </div>
    <br>
    <div id="contentcostbyclient"></div>
</div>
<script>
    var report_perbooks = "<?php echo base_url('report/report_perbooks'); ?>";
    $(document).ready(function() {
        $('#dateragestarttime')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#timeperclient').formValidation('revalidateField', 'dateragestarttime');
            });
        $('#daterageendtime')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#timeperclient').formValidation('revalidateField', 'daterageendtime');
            });
        //        Main.init();
        //        $('#tatamamaid').DataTable();
        $('#timeperclient')
            .formValidation({
                framework: 'bootstrap',
                icon: {},
                fields: {
                    dateragestarttime: {
                        validators: {
                            notEmpty: {
                                message: 'Range Start is required'
                            },
                            date: {
                                format: 'DD-MM-YYYY',
                                message: 'The date is not a valid'
                            }
                        }
                    },
                    daterageendtime: {
                        validators: {
                            notEmpty: {
                                message: 'Range End is required'
                            },
                            date: {
                                format: 'DD-MM-YYYY',
                                message: 'The date is not a valid'
                            }
                        }
                    }
                }
            }).on('success.form.fv', function(e) {
                e.preventDefault();
                var dateragestarttimen = $('#dateragestarttimen').val();
                var daterageendtimen = $('#daterageendtimen').val();
                $.ajax({
                    method: "POST",
                    url: report_perbooks,
                    dataType: "JSON",
                    data: {
                        'startdate': dateragestarttimen,
                        'enddate': daterageendtimen
                    }
                }).done(function(response) {
                    console.log(response);
                    // console.log(response.path);
                    // if (response.status === 1) {
                    //     $.notify(response.report, "success");
                    //     $("#contentcostbyclient").html(response.data);
                    // } else {
                    //     $.notify(response.report, "error");
                    //     $("#contentcostbyclient").html('');
                    // }
                });
            });
    });
</script>