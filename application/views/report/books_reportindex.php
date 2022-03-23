<div class="tabbable no-margin no-padding partition-white">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active">
            <a data-toggle="tab" href="#surveyone">
                Summary Books Report
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#surveyfour">
                Summery User Report
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#surveynineth">
                Book/Chapters User Report
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#surveyfith">
                Book Views
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#surveysix">
                Chapter Views
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#surveytwo">
                By Course And/Or Books Report
            </a>
        </li>
    </ul>
    <div class="tab-content partition-white">
        <div id="surveyone" class="tab-pane padding-bottom-5 active">
            <?php echo $this->load->view('report/reportrangebook', '', TRUE); ?>
        </div>
        <div id="surveyfour" class="tab-pane padding-bottom-5">
            <?php echo $this->load->view('report/reportsumusers', '', TRUE); ?>
        </div>
        <div id="surveynineth" class="tab-pane padding-bottom-5">
            <?php echo $this->load->view('report/reportsumusersdetails', '', TRUE); ?>
        </div>
        <div id="surveyfith" class="tab-pane padding-bottom-5">
            <?php echo $this->load->view('report/report_viewbybook', '', TRUE); ?>
        </div>
        <div id="surveysix" class="tab-pane padding-bottom-5">
            <?php echo $this->load->view('report/report_viewbychapter', '', TRUE); ?>
        </div>
        <div id="surveytwo" class="tab-pane padding-bottom-5">
            <?php echo $this->load->view('report/reportrangedetailsbooks', '', TRUE); ?>
        </div>
    </div>
</div>