<div class="tabbable no-margin no-padding partition-white">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active">
            <a data-toggle="tab" href="#attandanceone_surv">
                Survey Report
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#attandancetwo_surv">
                More Survey Report
            </a>
        </li>
    </ul>
    <div class="tab-content partition-white">
        <div id="attandanceone_surv" class="tab-pane padding-bottom-5 active">
            <?php echo $this->load->view('pages/cohort/survey_reportshow', '', TRUE); ?>
        </div>
        <div id="attandancetwo_surv" class="tab-pane padding-bottom-5">
            <?php echo $this->load->view('pages/cohort/list_survey_report', '', TRUE); ?>
        </div>

    </div>
</div>