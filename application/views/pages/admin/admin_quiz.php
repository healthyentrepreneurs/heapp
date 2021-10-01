<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.0/knockout-min.js"></script>
<script src="https://surveyjs.azureedge.net/1.8.12/survey.ko.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ace.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
<!-- Uncomment to enable Select2 <script src="https://unpkg.com/jquery"></script> <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->
<link href="https://surveyjs.azureedge.net/1.8.12/survey-creator.min.css" type="text/css" rel="stylesheet" />
<script src="https://surveyjs.azureedge.net/1.8.12/survey-creator.min.js"></script>
<!-- Edit Images -->
<div class="row">
    <div class="col-lg-12 col-md-12">
        <!-- WALAH CRAP N-->
        <div class="panel panel-white">
            <div class="panel-heading border-light">
                <h4 class="panel-title">Add New <span class="text-bold">Survey</span></h4>
            </div>
            <div class="panel-body">
                <div id="surveyContainer">
                    <div id="creatorElement"></div>
                </div>
                <form id="imageUploadForm" method="post" class="form-horizontal" action="<?= base_url("survey/addsurvey") ?>" enctype="multipart/form-data" role="form">
                    <div class="form-group">
                        <br>
                        <label class="col-sm-1 control-label">
                            <b>Upload</b>
                        </label>
                        <div class="col-sm-11">
                            <img id="uploadPreview" src="<?= $icon_image ?>" onclick="Call_Uploader()" width="300" height="200" />
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
                    <div class="alert alert-warning">
                        <span class="label label-success">NOTE!</span>
                        <span> This Image Is The Face Of This Activity </span>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="form-field-1">
                            <b>Survey Name</b>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="Survey Name" name="surveyname" id="surveyname" class="form-control">
                            <input type="hidden" name="surveyjson" id="surveyjson">
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
                            <input type="text" placeholder="Survey Description" name="surveydesc" id="surveydesc" class="form-control">
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
                                    Final Survey Save <i class="fa fa-arrow-circle-right"></i>
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
    // Show Designer, Test Survey, JSON Editor and additionally Logic tabs
    var options = {
        showLogicTab: true
    };
    //create the SurveyJS Creator and render it in div with id equals to "creatorElement"
    var creator = new SurveyCreator.SurveyCreator("creatorElement", options);
    //Show toolbox in the right container. It is shown on the left by default
    creator.showToolbox = "right";
    //Show property grid in the right container, combined with toolbox
    creator.showPropertyGrid = "right";
    //Make toolbox active by default
    creator.rightContainerActiveItem("toolbox");
    document.getElementsByClassName('svd_commercial_product')[0].style.visibility = 'hidden';
    document.getElementsByClassName('svd-svg-icon')[0].style.visibility = 'hidden';
    var localStorageName = "SaveLoadSurveyCreatorExample";
    creator.saveSurveyFunc = function(saveNo, callback) {
        //save the survey JSON
        // console.log(creator.text);
        //You can store in your database JSON as text: creator.text  or as JSON: creator.JSON
        window
            .localStorage
            .setItem(localStorageName, creator.text);
        document.getElementById("surveyjson").value = creator.text;
        callback(saveNo, true);
    }
    var defaultJSON = {
        pages: [{
            name: 'page1',
            questions: [{
                type: 'text',
                name: "q1"
            }]
        }]
    };
    creator.text = window
        .localStorage
        .getItem(localStorageName) || JSON.stringify(defaultJSON);
    $(document).ready(function(e) {
        $('#imageUploadForm').on('submit', (function(e) {
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
                        $('#imageUploadForm')[0].reset();
                        foo();
                        $("#sucess_message_now").text(data.message);
                        // creator.text = defaultJSON;
                        // console.log(data.status);
                        // foo();
                    } else {
                        // document.getElementById("error_message_now").value = "Shit Ehhhh?";
                        $("#error_message_now").text(data.message);
                        console.log(data.message)
                    }
                    // console.log("success");

                },
                error: function(data) {
                    console.log("error");
                }
            });
        }));

        $("#ImageBrowse").on("change", function() {
            $("#imageUploadForm").submit();
        });
    });

    function foo() {
        // alert("hello");
        // onclick="document.getElementById('resetbuttonid').click()"
        document.getElementById("uploadPreview").value = 'https://picsum.photos/200/300';
        // document.getElementById("myImg").src = "https://picsum.photos/200/300";
        // document.getElementById("surveyjson").value = creator.text;
        creator.text = "";
        window
            .localStorage
            .setItem(localStorageName, creator.text);
        creator.text = JSON.stringify(defaultJSON);
        window.setTimeout(function() {
            location.reload()
        }, 1000)
    }
</script>