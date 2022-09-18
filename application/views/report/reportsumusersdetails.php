<script src="<?= base_url() ?>/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/assets/validate/formValidation.min.js"></script>
<script src="<?= base_url() ?>/assets/validate/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/print.css" type="text/css" media="print" />
<script src="<?= base_url() ?>/assets/validate/moment.min.js"></script>
<script src="<?= base_url() ?>/assets/validate/print.min.js"></script>
<script src="<?= base_url() ?>/assets/js/notify.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<style>
    .select2-container .select2-selection--single {
        height: 34px !important;
        /* width:180px; */
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }

    .lengthselect {
        width: 420px;
    }
</style>
<div class="panel-body" id="client_one">
    <!-- <div class="row"> -->
    <form id="formsumuserdetail" method="post" class="form-horizontal" role="form">
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
                User
            </label>
            <div class="col-sm-9">
                <select class="form-control lengthselect select2" placeholder="Select USER" name="user_id" id="user_id">
                    <option value="">-SELECT USER-</option>
                    <?php
                    foreach ($users as $value) {
                    ?>
                        <option value="<?= $value['id'] . '@' . $value['username'] ?>">
                            <?= $value['username'] . ' | ' . $value['firstname'] . ' ' . $value['lastname'] ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-1 control-label">
                Course
            </label>
            <div class="col-sm-7">
                <select id="availaba_course_user" name="availaba_course_user" class="form-control lengthselect select2" placeholder="Select Course">
                    <option value="">--Mandatory--</option>
                    <?php
                    foreach ($all_courses as $value) {
                    ?>
                        <option value="<?= $value['id']; ?>"><?= $value['fullname'] . ' | ' . $value['shortname'] ?></option>
                    <?php
                    }
                    ?>
                </select>
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
                        <li><a href="#" id="exportpdf" onclick="removesumbootabuserchapbook()">
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
    <!-- </div> -->
    <br>
    <div id="contentdetailsusers"></div>
</div>
<script>
    var sum_user_report_detail = "<?php echo base_url('report/sum_user_report_detail'); ?>";
    $(document).ready(function() {
        // $('.select2').select2();
        $('#dateragestarttimedetailsusers')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formsumuserdetail').formValidation('revalidateField', 'dateragestarttimedetailsusers');
            });
        $('#daterageendtimedetailsusers')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formsumuserdetail').formValidation('revalidateField', 'daterageendtimedetailsusers');
            });
        //        Main.init();
        //        $('#tatamamaid').DataTable();
        $('#formsumuserdetail')
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
                    },
                    user_id: {
                        validators: {
                            notEmpty: {
                                message: 'User is required'
                            }
                        }
                    },
                    availaba_course_user: {
                        validators: {
                            notEmpty: {
                                message: 'Course is required'
                            }
                        }
                    },
                }
            }).on('success.form.fv', function(e) {
                e.preventDefault();
                var dateragestarttimedetailsusersn = $('#dateragestarttimedetailsusersn').val();
                var daterageendtimedetailsusersn = $('#daterageendtimedetailsusersn').val();
                // User ID
                var userid = document.getElementById("user_id");
                var uservalue = userid.options[userid.selectedIndex].value;
                var usertext = userid.options[userid.selectedIndex].text;
                // User ID end
                //Start  course
                var courseid = document.getElementById("availaba_course_user");
                var courseidvalue = courseid.options[courseid.selectedIndex].value;
                var courseidtext = courseid.options[courseid.selectedIndex].text;
                //End course
                $.ajax({
                    method: "POST",
                    url: sum_user_report_detail,
                    dataType: "JSON",
                    data: {
                        'startdate': dateragestarttimedetailsusersn,
                        'enddate': daterageendtimedetailsusersn,
                        'userid': uservalue,
                        'courseid': courseidvalue
                    }
                }).done(function(response) {
                    // console.log(response);
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