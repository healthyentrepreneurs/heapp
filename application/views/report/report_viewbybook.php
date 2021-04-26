<div class="panel-body" id="client_one">
    <?php
    print_array($all_courses);
    ?>
    <div class="row">
        <form id="formviewbybook" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Start Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="dateragestarttimebook">
                        <input type="text" name="dateragestarttimebook" id="dateragestarttimebookn" class="form-control">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <label class="col-sm-1 control-label">
                    End Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="daterageendtimebook">
                        <input type="text" class="form-control" name="daterageendtimebook" id="daterageendtimebookn">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Course
                </label>
                <div class="col-sm-7">
                    <select id="availaba_course" name="availaba_course" class="form-control" placeholder="Select Course">
                        <?php
                        foreach ($all_courses as $value) {
                        ?>
                            <option value="<?= $value['id']; ?>"><?= $value['fullname'] ?></option>
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
                    <select id="bookby_id" name="bookby_id" class="form-control" placeholder="Select Book">
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
                            <li><a href="#" id="exportpdfdet" onclick="removeviewbybooktab()">
                                    Save as PDF </a></li>
                            <li>
                                <a href="<?= base_url('excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'viewbybook' . 'write.xls'); ?>" download>Export to Excel</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
        <!--END-->
    </div>
    <br>
    <div id="contentviewbybook"></div>
</div>
<script>
    var getmereportviewbybook = "<?php echo base_url('report/reportby_booksid'); ?>";
    var getbooks = "<?php echo base_url('report/getbooksin_course'); ?>";
    $(document).ready(function() {
        // $('.select2').select2();
        $('#dateragestarttimebook')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formviewbybook').formValidation('revalidateField', 'dateragestarttimebook');
            });
        $('#daterageendtimebook')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formviewbybook').formValidation('revalidateField', 'daterageendtimebook');
            });
        $("#availaba_course").change(function() {
            var course_id = $(this).val();
            $.ajax({
                url: getbooks,
                type: 'post',
                data: {
                    courseid: course_id
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    var len = response.length;
                    $("#bookby_id").empty();
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['book_id'];
                        var name = response[i]['bookname'];
                        $("#bookby_id").append("<option value='" + id + "'>" + name + "</option>");
                    }
                }
            });
        });
        $('#formviewbybook')
            .formValidation({
                framework: 'bootstrap',
                icon: {},
                fields: {
                    dateragestarttimebook: {
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
                    daterageendtimebook: {
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
                    availaba_course: {
                        validators: {
                            notEmpty: {
                                message: 'Course is required'
                            }
                        }
                    },
                    bookby_id: {
                        validators: {
                            notEmpty: {
                                message: 'Book is required'
                            }
                        }
                    }
                }
            }).on('success.form.fv', function(e) {
                e.preventDefault();
                var projectid = document.getElementById("availaba_course");
                var projectidvalue = projectid.options[projectid.selectedIndex].value;
                var projectidtext = projectid.options[projectid.selectedIndex].text;
                // Start Books
                var bookid = document.getElementById("bookby_id");
                var bookvalue = bookid.options[bookid.selectedIndex].value;
                var booktext = bookid.options[bookid.selectedIndex].text;
                //End Books
                var dateragestarttimebookn = $('#dateragestarttimebookn').val();
                var daterageendtimebookn = $('#daterageendtimebookn').val();
                $.ajax({
                    method: "POST",
                    url: getmereportviewbybook,
                    dataType: "JSON",
                    data: {
                        'courseid': projectidvalue,
                        'coursename': projectidtext,
                        'bookid': bookvalue,
                        'booktext': booktext,
                        'startdate': dateragestarttimebookn,
                        'enddate': daterageendtimebookn
                    }
                }).done(function(response) {
                    // console.log(response);
                    // console.log(response.path);
                    if (response.status === 1) {
                        $.notify(response.report, "success");
                        $("#contentviewbybook").html(response.data);
                    } else {
                        $.notify(response.report, "error");
                        $("#contentviewbybook").html('');
                    }
                });
            });
    });
</script>