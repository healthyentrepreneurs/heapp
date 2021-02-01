<div class="tabbable no-margin no-padding partition-white">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active">
            <a data-toggle="tab" href="#attandanceone">
                Assign Survey To Cohort
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#attandancetwo">
                More Cohort Acts
            </a>
        </li>
    </ul>
    <div class="tab-content partition-white">
        <div id="attandanceone" class="tab-pane padding-bottom-5 active">
            <?php echo $this->load->view('pages/cohort/assign_cohort', '', TRUE); ?>
        </div>
        <div id="attandancetwo" class="tab-pane padding-bottom-5">
            <?php echo $this->load->view('pages/cohort/list_cohort', '', TRUE); ?>
        </div>

    </div>
</div>