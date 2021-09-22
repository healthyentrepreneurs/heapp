<?php
// print_array($courses_sub);
?>
<style type="text/css">
    .error {
        color: red;
    }

    .success {
        color: green;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <!-- start: TABLE WITH IMAGES PANEL -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h4 class="panel-title">Activity Type : <?= $type ?> <span class="text-bold"><?= $name ?></span></h4>
                <div class="panel-tools">
                    <div class="dropdown">
                        <a data-toggle="dropdown" class="btn btn-xs dropdown-toggle btn-transparent-grey">
                            <i class="fa fa-cog"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-light pull-right" role="menu">
                            <li>
                                <a class="panel-collapse collapses" href="#"><i class="fa fa-angle-up"></i> <span>Collapse</span> </a>
                            </li>
                            <li>
                                <a class="panel-refresh" href="#"> <i class="fa fa-refresh"></i> <span>Refresh</span> </a>
                            </li>
                            <li>
                                <a class="panel-config" href="#panel-config" data-toggle="modal"> <i class="fa fa-wrench"></i> <span>Configurations</span></a>
                            </li>
                            <li>
                                <a class="panel-expand" href="#"> <i class="fa fa-expand"></i> <span>Fullscreen</span></a>
                            </li>
                        </ul>
                    </div>
                    <a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
                </div>
            </div>
            <div class="panel-body">
                <h4 class="success"><?php echo ($this->session->flashdata('validate_image_success')) ?></h4>
                <form id="upload_icon" method="post" class="form-horizontal" enctype="multipart/form-data" role="form" action="<?= base_url('imagemanager/upload_resize') ?>">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            <b>Click On Image To Upload</b>
                        </label>
                        <div class="col-sm-9">
                            <img id="uploadPreview" src="<?= $icon_image ?>" onclick="Call_Uploader()" width="300" height="200" style="background-color: #689F59;" />
                            <br><br>
                            <h6 class="error"><?php echo ($this->session->flashdata('validate_image')) ?></h6>
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
                            <input id="original" class="hidden" type="text" name="original" value="<?= $icon_image ?>" />
                            <input id="name" class="hidden" type="text" name="name" value="<?= $name ?>" />
                            <input id="type" class="hidden" type="text" name="type" value="<?= $type ?>" />
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <span class="label label-warning">NOTE!</span>
                        <span> The Changes Dont Affect The Moodle Core Icon Settings </span>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="panel-body">
                            <p>
                                <button class="btn btn-blue btn-block" type="submit">
                                    Update Icon <i class="fa fa-arrow-circle-right"></i>
                                </button>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end: TABLE WITH IMAGES PANEL -->
    </div>
</div>