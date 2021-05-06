<div class="panel-body" id="client_one">
    <?php
    // print_array($surveydatas);
    ?>
    <div class="row">
        <form id="timeperclientdet" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Start Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="dateragestarttimedet">
                        <input type="text" name="dateragestarttimedet" id="dateragestarttimedetn" class="form-control">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <label class="col-sm-1 control-label">
                    End Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="daterageendtimedet">
                        <input type="text" class="form-control" name="daterageendtimedet" id="daterageendtimedetn">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Survey
                </label>
                <div class="col-sm-7">
                    <select id="client_iddet" name="client_iddet" class="form-control search-select" placeholder="Select Client">
                        <option value="">--Select Survey--</option>
                        <?php
                        foreach ($surveydatas as $value) {
                        ?>
                            <option value="<?= $value['id']; ?>"><?= $value['name'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
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
                            <li><a href="#" id="exportpdfdet" onclick="removepigi()">
                                    Save as PDF </a></li>
                            <li>
                                <a href="<?= base_url('excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'detailswrite.xls'); ?>" download>Export to Excel</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
        <!--END-->
    </div>
    <br>
    <div id="contentcostbyclientdet"></div>
</div>
<script>
    var getmereportclientcostdet = "<?php echo base_url('report/report_surveydetails'); ?>";
    $(document).ready(function() {
        $('#dateragestarttimedet')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#timeperclientdet').formValidation('revalidateField', 'dateragestarttimedet');
            });
        $('#daterageendtimedet')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#timeperclientdet').formValidation('revalidateField', 'daterageendtimedet');
            });
        //        Main.init();
        //        $('#tatamamaid').DataTable();
        $('#timeperclientdet')
            .formValidation({
                framework: 'bootstrap',
                icon: {},
                fields: {
                    dateragestarttimedet: {
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
                    daterageendtimedet: {
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
                    client_iddet: {
                        validators: {
                            notEmpty: {
                                message: 'Survey is required'
                            }
                        }
                    }
                }
            }).on('success.form.fv', function(e) {
                e.preventDefault();
                var projectid = document.getElementById("client_iddet");
                var projectidvalue = projectid.options[projectid.selectedIndex].value;
                var projectidtext = projectid.options[projectid.selectedIndex].text;
                var dateragestarttimedetn = $('#dateragestarttimedetn').val();
                var daterageendtimedetn = $('#daterageendtimedetn').val();
                $.ajax({
                    method: "POST",
                    url: getmereportclientcostdet,
                    dataType: "JSON",
                    data: {
                        'selectclientid': projectidvalue,
                        'selectclientname': projectidtext,
                        'startdate': dateragestarttimedetn,
                        'enddate': daterageendtimedetn,
                        'id_showdetailed': $('#id_showdetailed').val()
                    }
                }).done(function(response) {
                    console.log(response);
                    // console.log(response.path);
                    // if (response.status === 1) {
                    //     $.notify(response.report, "success");
                    //     $("#contentcostbyclientdet").html(response.data);
                    // } else {
                    //     $.notify(response.report, "error");
                    //     $("#contentcostbyclientdet").html('');
                    // }
                });
            });
    });
</script>