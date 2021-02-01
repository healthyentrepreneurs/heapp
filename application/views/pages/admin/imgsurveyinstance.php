<?php
// print_array($surveydataone);
?>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <!-- WALAH CRAP N-->
        <div class="panel panel-white">
            <div class="panel-heading border-light">
                <h4 class="panel-title">Update Image <span class="text-bold">Survey</span></h4>
            </div>
            <div class="panel-body">
                <form id="imageUploadForm_n" method="post" class="form-horizontal" action="<?= base_url("survey/edit_survayimage") ?>" enctype="multipart/form-data" role="form">
                    <div class="form-group">
                        <br>
                        <label class="col-sm-1 control-label">
                            <b>Upload</b>
                        </label>
                        <div class="col-sm-11">
                            <img id="uploadPreview" src="<?= base_url('uploadscustome/') . $surveydataone['image'] ?>" onclick="Call_Uploader()" width="300" height="200" />
                            <br><br>
                            <!-- <div class="control-group error">
                                <h6 class="help-inline">
                                    <font size="1" color="red"><?php echo $this->session->flashdata('imageerror'); ?></font>
                                </h6>
                            </div> -->
                            <input id="user_profile_pic" class="hidden" type="file" name="user_profile_pic" onChange="PreviewImage();" placeholder="Profile Picture" />
                            <script type="text/javascript">
                                function PreviewImage() {
                                    var oFReader = new FileReader();
                                    oFReader.readAsDataURL(document.getElementById("user_profile_pic").files[0]);
                                    oFReader.onload = function(oFREvent) {
                                        document.getElementById("uploadPreview").src = oFREvent.target.result;
                                        //document.getElementById("uploadImage").disabled = true
                                    };
                                };

                                function Call_Uploader() {
                                    $('#user_profile_pic').click();
                                }
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="form-field-1">
                            <b>Survey Name</b>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="Survey Name" name="surveyname" id="surveyname" class="form-control" value="<?= $surveydataone['name'] ?>" readonly="true">
                            <input type="hidden" id="survey_id" name="survey_id" value="<?= $id; ?>">
                            <input type="hidden" id="image_old" name="image_old" value="<?= $surveydataone['image']; ?>">
                            <input type="hidden" id="image_url_small_old" name="image_url_small_old" value="<?= $surveydataone['image_url_small']; ?>">
                            <div class="control-group error">
                                <h6 class="help-inline">
                                    <font size="2" color="red"><span id="error_message_now"></span></font>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="form-field-1">
                            <b>Survey Description</b>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="Survey Description" name="surveydesc" id="surveydesc" class="form-control" value="<?= $surveydataone['surveydesc'] ?>" readonly="true">
                            <div class="control-group error">
                                <h6 class="help-inline">
                                    <font size="2" color="red"><span id="error_message_now"></span></font>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="control-group error">
                        <h6 class="help-inline">
                            <font size="3" color="green"><span id="sucess_message_now"></span></font>
                        </h6>
                    </div>
                    <div class="form-group">
                        <div class="panel-body">
                            <!-- <input type="submit" name="upload" value="Upload" /> -->
                            <p>
                                <button class="btn btn-blue btn-block" type="submit">
                                    Update Survey <i class="fa fa-arrow-circle-right"></i>
                                </button>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(e) {
        $('#imageUploadForm_n').on('submit', (function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status == 1) {
                        // $('#imageUploadForm_n')[0].reset();
                        $("#sucess_message_now").text(data.message);
                        window.setTimeout(function() {
                            // location.reload()
                            location.href = "<?php echo base_url('welcome/admin/2')?>";
                        }, 1000)
                        // creator.text = defaultJSON;
                        // console.log(data.status);
                    } else {
                        // document.getElementById("error_message_now").value = "Shit Ehhhh?";
                        $("#error_message_now").text(data.message);
                    }
                    // console.log(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }));

        $("#ImageBrowse").on("change", function() {
            $("#imageUploadForm").submit();
        });
    });
</script>