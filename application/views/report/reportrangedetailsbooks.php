<div class="panel-body" id="client_one">
    <div class="row">
        <form id="formdetailschap" method="post" class="form-horizontal" role="form">
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
                    Course
                </label>
                <div class="col-sm-7">
                    <select id="client_iddet" name="client_iddet" class="form-control lengthselect select2" placeholder="Select Course">
                        <option value="non">--Select Optional--</option>
                        <?php
                        foreach ($course_content as $value) {
                        ?>
                            <option value="<?= $value['course_id']; ?>"><?= $value['course_shortname'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Book
                </label>
                <div class="col-sm-7">
                    <select id="book_id" name="book_id" class="form-control lengthselect select2" placeholder="Select Book">
                        <option value="non">--Select Optional--</option>
                        <?php
                        foreach ($books_content as $value) {
                        ?>
                            <option value="<?= $value['book_id']; ?>"><?= $value['book_name'] ?></option>
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
                            <li><a href="#" id="exportpdfdet" onclick="removedetailschap()">
                                    Save as PDF </a></li>
                            <li>
                                <a href="<?= base_url('excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'chapter' . 'write.xls'); ?>" download>Export to Excel</a>
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
    var getmereportclientcostdet = "<?php echo base_url('report/books_reportdetails'); ?>";
    $(document).ready(function() {
        $('.select2').select2();
        $('#dateragestarttimedet')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formdetailschap').formValidation('revalidateField', 'dateragestarttimedet');
            });
        $('#daterageendtimedet')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formdetailschap').formValidation('revalidateField', 'daterageendtimedet');
            });
        //        Main.init();
        //        $('#tatamamaid').DataTable();
        $('#formdetailschap')
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
                    }
                    // ,
                    // client_iddet: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: 'Survey is required'
                    //         }
                    //     }
                    // }
                }
            }).on('success.form.fv', function(e) {
                e.preventDefault();
                var projectid = document.getElementById("client_iddet");
                var projectidvalue = projectid.options[projectid.selectedIndex].value;
                var projectidtext = projectid.options[projectid.selectedIndex].text;
                // Start Books
                var bookid = document.getElementById("book_id");
                var bookvalue = bookid.options[bookid.selectedIndex].value;
                var booktext = bookid.options[bookid.selectedIndex].text;
                //End Books
                var dateragestarttimedetn = $('#dateragestarttimedetn').val();
                var daterageendtimedetn = $('#daterageendtimedetn').val();
                $.ajax({
                    method: "POST",
                    url: getmereportclientcostdet,
                    dataType: "JSON",
                    data: {
                        'courseid': projectidvalue,
                        'coursename': projectidtext,
                        'bookid': bookvalue,
                        'booktext': booktext,
                        'startdate': dateragestarttimedetn,
                        'enddate': daterageendtimedetn
                    }
                }).done(function(response) {
                    // console.log(response);
                    // console.log(response.path);
                    if (response.status === 1) {
                        $.notify(response.report, "success");
                        $("#contentcostbyclientdet").html(response.data);
                    } else {
                        $.notify(response.report, "error");
                        $("#contentcostbyclientdet").html('');
                    }
                });
            });
    });
</script>