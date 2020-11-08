<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.0/knockout-min.js"></script>
    <script src="https://surveyjs.azureedge.net/1.8.12/survey.ko.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ace.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
    <!-- Uncomment to enable Select2 <script src="https://unpkg.com/jquery"></script> <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->
    <link href="https://surveyjs.azureedge.net/1.8.12/survey-creator.min.css" type="text/css" rel="stylesheet" />
    <script src="https://surveyjs.azureedge.net/1.8.12/survey-creator.min.js"></script>
<div id="surveyContainer">
    <div id="creatorElement"></div>
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
    // icon-noncommercial
    // $('.svd_commercial_product .svd-main-color').hide();
</script>