<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<style>
    .select2-container .select2-selection--single {
        height: 34px !important;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }
</style> -->
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