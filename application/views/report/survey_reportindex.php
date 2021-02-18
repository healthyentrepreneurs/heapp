<div class="tabbable no-margin no-padding partition-white">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active">
            <a data-toggle="tab" href="#surveyone">
                Survey Report
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#surveytwo">
                Survey Report Detailed
            </a>
        </li>
    </ul>
    <div class="tab-content partition-white">
        <div id="surveyone" class="tab-pane padding-bottom-5 active">
            <?php echo $this->load->view('report/reportrange', '', TRUE); ?>
        </div>
        <div id="surveytwo" class="tab-pane padding-bottom-5">
            <?php echo $this->load->view('report/reportrangedetails', '', TRUE); ?>
        </div>

    </div>
</div>