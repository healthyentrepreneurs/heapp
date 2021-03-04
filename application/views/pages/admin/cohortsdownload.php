<div class="tabbable no-margin no-padding partition-white">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active">
            <a data-toggle="tab" href="#downloadcohort">
                Download By Cohort
            </a>
        </li>
    </ul>
    <div class="tab-content partition-white">
        <div id="downloadcohort" class="tab-pane padding-bottom-5 active">
            <?php echo $this->load->view('pages/cohort/cohortviewdownload', '', TRUE); ?>
        </div>
    </div>
</div>