<div class="panel-body" id="viewbychapter">
    <?php
    // print_array($all_courses);
    ?>
    <div class="row">
        <form id="formviewbychapter" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Start Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="dateragestarttimechapterview">
                        <input type="text" name="dateragestarttimechapterview" id="dateragestarttimechapterviewn" class="form-control">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <label class="col-sm-1 control-label">
                    End Date
                </label>
                <div class="col-sm-4 date">
                    <div class="input-group input-append date" id="daterageendtimechapterview">
                        <input type="text" class="form-control" name="daterageendtimechapterview" id="daterageendtimechapterviewn">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Course
                </label>
                <div class="col-sm-7">
                    <select id="availaba_coursechap" name="availaba_coursechap" class="form-control" placeholder="Select Course">
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
                    Book
                </label>
                <div class="col-sm-7">
                    <select id="bookby_id_chap" name="bookby_id_chap" class="form-control" placeholder="Select Book">
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">
                    Chapter
                </label>
                <div class="col-sm-7">
                    <select id="chapter_id_chap" name="chapter_id_chap" class="form-control" placeholder="Select Chapter">
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
                                <a href="<?= base_url('excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'viewbychapter' . 'write.xls'); ?>" download>Export to Excel</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
        <!--END-->
    </div>
    <br>
    <div id="contentviewbychapter"></div>
</div>
<script>
    var getmereportviewbychap = "<?php echo base_url('report/reportby_viewchapter'); ?>";
    var getbookschaps = "<?php echo base_url('report/getbooksin_course'); ?>";
    var getchapters = "<?php echo base_url('user/get_chapters_perbookcourse'); ?>";
    $(document).ready(function() {
        // $('.select2').select2();
        $('#dateragestarttimechapterview')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formviewbychapter').formValidation('revalidateField', 'dateragestarttimechapterview');
            });
        $('#daterageendtimechapterview')
            .datepicker({
                format: 'dd-mm-yyyy'
            })
            .on('changeDate', function(e) {
                // Revalidate the date field
                $('#formviewbychapter').formValidation('revalidateField', 'daterageendtimechapterview');
            });
        $("#availaba_coursechap").change(function() {
            var course_id = $(this).val();
            $.ajax({
                url: getbookschaps,
                type: 'post',
                data: {
                    courseid: course_id
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    var len = response.length;
                    $("#bookby_id_chap").empty();
                    $("#bookby_id_chap").append("<option value=''>" + "--Mandatory--" + "</option>");
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['book_id'];
                        var name = response[i]['bookname'];
                        $("#bookby_id_chap").append("<option value='" + id + "'>" + name + "</option>");
                    }
                }
            });
        });
        $("#bookby_id_chap").change(function() {
            var book_id = $(this).val();
            var projectid = document.getElementById("availaba_coursechap");
            var course_idem = projectid.options[projectid.selectedIndex].value;
            $.ajax({
                url: getchapters,
                type: 'post',
                data: {
                    courseid: course_idem,
                    book_id: book_id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    // var len = response.length;
                    // $("#chapter_id_chap").empty();
                    // $("#chapter_id_chap").append("<option value=''>" + "--Mandatory--" + "</option>");
                    // for (var i = 0; i < len; i++) {
                    //     var id = response[i]['chapter_id'];
                    //     var name = response[i]['title'];
                    //     $("#chapter_id_chap").append("<option value='" + id + "'>" + name + "</option>");
                    // }
                }
            });
        });
        $('#formviewbychapter')
            .formValidation({
                framework: 'bootstrap',
                icon: {},
                fields: {
                    dateragestarttimechapterview: {
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
                    daterageendtimechapterview: {
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
                    availaba_coursechap: {
                        validators: {
                            notEmpty: {
                                message: 'Course is required'
                            }
                        }
                    },
                    bookby_id_chap: {
                        validators: {
                            notEmpty: {
                                message: 'Book is required'
                            }
                        }
                    },
                    chapter_id_chap: {
                        validators: {
                            notEmpty: {
                                message: 'Chapter is required'
                            }
                        }
                    }
                }
            }).on('success.form.fv', function(e) {
                e.preventDefault();
                var projectid = document.getElementById("availaba_coursechap");
                var projectidvalue = projectid.options[projectid.selectedIndex].value;
                var projectidtext = projectid.options[projectid.selectedIndex].text;
                // Start Books
                var bookid = document.getElementById("bookby_id_chap");
                var bookvalue = bookid.options[bookid.selectedIndex].value;
                var booktext = bookid.options[bookid.selectedIndex].text;
                //End Books
                var dateragestarttimechapterviewn = $('#dateragestarttimechapterviewn').val();
                var daterageendtimechapterviewn = $('#daterageendtimechapterviewn').val();
                $.ajax({
                    method: "POST",
                    url: getmereportviewbychap,
                    dataType: "JSON",
                    data: {
                        'courseid': projectidvalue,
                        'coursename': projectidtext,
                        'bookid': bookvalue,
                        'booktext': booktext,
                        'startdate': dateragestarttimechapterviewn,
                        'enddate': daterageendtimechapterviewn
                    }
                }).done(function(response) {
                    // console.log(response);
                    // console.log(response.path);
                    if (response.status === 1) {
                        $.notify(response.report, "success");
                        $("#contentviewbychapter").html(response.data);
                    } else {
                        $.notify(response.report, "error");
                        $("#contentviewbychapter").html('');
                    }
                });
            });
    });
</script>