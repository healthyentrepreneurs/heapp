<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.0/knockout-min.js"></script>
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/survey.ko.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ace.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
<!-- Uncomment to enable Select2 <script src="https://unpkg.com/jquery"></script> <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->
<link href="<?= base_url() ?>assets/surveyjs/1.9.48/survey-creator.min.css" type="text/css" rel="stylesheet" />
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/survey-creator.min.js"></script>
<link href="<?= base_url() ?>assets/surveyjs/1.9.48/survey.analytics.datatables.min.css" type="text/css" rel="stylesheet" />
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/survey.analytics.datatables.min.js"></script>
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/survey.core.min.js"></script>
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/surveyjs-widgets.min.js"></script>
<div class="row">
    <!-- WALAH CRAP -->
    <div class="col-lg-12 col-md-12">
        <div class="panel panel-white">
            <div class="panel-heading border-light">
                <h4 class="panel-title">Edit <span class="text-bold">Survey</span></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="form-field-1">
                        Survey Name
                    </label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="Survey Name" name="survey_name" id="survey_name" value="<?= $surveydataone['name'] ?>" class="form-control">
                    </div>
                    <input type="hidden" id="survey_id" name="survey_id" value="<?= $id; ?>">
                </div>
                <br><br><br>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="form-field-1">
                        Survey Description
                    </label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="Survey Description" name="surveydesc" id="surveydesc" value="<?= $surveydataone['surveydesc'] ?>" class="form-control">
                    </div>
                </div>
                <div id="surveyContainerInstance">
                    <div id="creatorElementInstance"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var url_updatesurvey = "<?php echo base_url("survey/updatesurvey"); ?>";
        // Show Designer, Test Survey, JSON Editor and additionally Logic tabs
        var options = {
            showLogicTab: true
        };
        //create the SurveyJS Creator and render it in div with id equals to "creatorElement"
        var creator = new SurveyCreator.SurveyCreator("creatorElementInstance", options);
        //Show toolbox in the right container. It is shown on the left by default
        creator.showToolbox = "right";
        //Show property grid in the right container, combined with toolbox
        creator.showPropertyGrid = "right";
        //Make toolbox active by default
        creator.rightContainerActiveItem("toolbox");
        document.getElementsByClassName('svd_commercial_product')[0].style.visibility = 'hidden';
        document.getElementsByClassName('svd-svg-icon')[0].style.visibility = 'hidden';
        // creator.saveSurveyFunc = function(saveNo, callback) {
        //     $.ajax({
        //         type: 'POST',
        //         url: url_updatesurvey,
        //         data: {
        //             surveyname: document.getElementById("survey_name").value,
        //             surveyid: document.getElementById("survey_id").value,
        //             surveydesc:document.getElementById("surveydesc").value,
        //             surveyobj: creator.text,
        //         },
        //         success: function(result) {
        //             // console.log(result);
        //             var notyf = new Notyf();
        //             // notyf.success('Your changes have been successfully saved!');
        //             notyf.success('Changed Disabled Because They Affect existing Data!');
        //         },
        //         error: function() {
        //             alert('Some error found. Please try again!');
        //         }
        //     });
        //     callback(saveNo, true);
        // }
        var defaultJSON = <?php echo $surveydataone['surveyjson']; ?>;
        // console.log(defaultJSON);
        creator.text = JSON.stringify(defaultJSON);
    });
</script>
<!-- https://github.com/caroso1222/notyf -->