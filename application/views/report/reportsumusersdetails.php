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
        <form id="formsumusers" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Start Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="dateragestarttimedetailsusers">
                        <input type="text" name="dateragestarttimedetailsusers" id="dateragestarttimedetailsusersn" class="form-control">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <label class="col-sm-1 control-label">
                    End Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="daterageendtimedetailsusers">
                        <input type="text" class="form-control" name="daterageendtimedetailsusers" id="daterageendtimedetailsusersn">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">
                </label>
                <div class="col-sm-7">

                </div>
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
                            <li><a href="#" id="exportpdf" onclick="removesumusertab()">
                                    Save as PDF </a></li>
                            <li>
                                <a href="<?= base_url('excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'sumusers' . 'write.xls'); ?>" download>Export to Excel</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
        <!--END-->
    </div>
    <br>
    <div id="contentdetailsusers"></div>
</div>
<script>
    var sum_user_report = "<?php echo base_url('report/sum_user_report'); ?>";
    $(document).ready(function() {
        $('#dateragestarttimedetailsusers')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formsumusers').formValidation('revalidateField', 'dateragestarttimedetailsusers');
            });
        $('#daterageendtimedetailsusers')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formsumusers').formValidation('revalidateField', 'daterageendtimedetailsusers');
            });
        //        Main.init();
        //        $('#tatamamaid').DataTable();
        $('#formsumusers')
            .formValidation({
                framework: 'bootstrap',
                icon: {},
                fields: {
                    dateragestarttimedetailsusers: {
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
                    daterageendtimedetailsusers: {
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
                var dateragestarttimedetailsusersn = $('#dateragestarttimedetailsusersn').val();
                var daterageendtimedetailsusersn = $('#daterageendtimedetailsusersn').val();
                $.ajax({
                    method: "POST",
                    url: sum_user_report,
                    dataType: "JSON",
                    data: {
                        'startdate': dateragestarttimedetailsusersn,
                        'enddate': daterageendtimedetailsusersn
                    }
                }).done(function(response) {
                    // console.log(response);
                    // console.log(response.path);
                    if (response.status === 1) {
                        $.notify(response.report, "success");
                        $("#contentdetailsusers").html(response.data);
                    } else {
                        $.notify(response.report, "error");
                        $("#contentdetailsusers").html('');
                    }
                });
            });
    });
</script>