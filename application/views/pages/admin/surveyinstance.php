<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.0/knockout-min.js"></script>
<script src="https://surveyjs.azureedge.net/1.8.12/survey.ko.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ace.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
<!-- Uncomment to enable Select2 <script src="https://unpkg.com/jquery"></script> <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->
<link href="https://surveyjs.azureedge.net/1.8.12/survey-creator.min.css" type="text/css" rel="stylesheet" />
<script src="https://surveyjs.azureedge.net/1.8.12/survey-creator.min.js"></script>
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
                <div id="surveyContainerInstance">
                    <div id="creatorElementInstance"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
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
    var localStorageNameinstance = "SaveLoadSurveyCreatorInstace";
    creator.saveSurveyFunc = function(saveNo, callback) {
        //save the survey JSON
        // console.log(creator.text);
        //You can store in your database JSON as text: creator.text  or as JSON: creator.JSON
        window
            .localStorage
            .setItem(localStorageNameinstance, creator.text);
        $.ajax({
            type: 'POST',
            url: url_updatesurvey,
            data: {
                surveyname: document.getElementById("survey_name").value,
                surveyid: document.getElementById("survey_id").value,
                surveyobj: creator.text,
            },
            success: function(result) {
                console.log(result);
                // $('#sonuc').html(result);
            },
            error: function() {
                alert('Some error found. Please try again!');
            }
        });
        //We assume that we can't get error on saving data in local storage
        //Tells creator that changing (saveNo) saved successfully.
        //Creator will update the status from Saving to saved
        callback(saveNo, true);
    }
    // var defaultJSON = {
    //     pages: [{
    //         name: 'page1',
    //         questions: [{
    //             type: 'text',
    //             name: "q1"
    //         }]
    //     }]
    // };
    var defaultJSON = <?php echo $surveydataone['surveyjson']; ?>;
    // console.log(defaultJSON);
    creator.text = window
        .localStorage
        .getItem(localStorageNameinstance) || JSON.stringify(defaultJSON);
</script>