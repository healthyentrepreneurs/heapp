<div class="tabbable no-margin no-padding partition-white">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active">
            <a data-toggle="tab" href="#surveyone">
                Books Report
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#surveytwo">
                Details In Chapters Report
            </a>
        </li>
    </ul>
    <div class="tab-content partition-white">
        <div id="surveyone" class="tab-pane padding-bottom-5 active">
            <?php echo $this->load->view('report/reportrangebook', '', TRUE); ?>
        </div>
        <div id="surveytwo" class="tab-pane padding-bottom-5">
            <?php echo $this->load->view('report/reportrangedetailsbooks', '', TRUE); ?>
        </div>
        <!-- Njovu -->
    </div>
</div>