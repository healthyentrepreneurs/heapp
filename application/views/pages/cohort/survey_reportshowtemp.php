<style>
    h4,
    p {
        display: inline
    }
</style>
<div class="col-md-12">
    <div class="panel-body">
        <div class="table-responsive" id="mysurveytable_nm">
            <p>
                <b>Survey Report In Range <h4> <?= converttodate($startdate, 'F jS, Y') ?> TO <?= converttodate($enddate, 'F jS, Y') ?></b></h4>
            </p>
            <?php echo $this->load->view($table_survey_url, '', TRUE); ?>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#mysurveytable_n').DataTable({
            // "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
    });

    // function gotoreport(id) {
    //     window.location.replace("http://192.168.43.88/heapp/welcome/admin/6?id=" + id);
    // }

    function removepigi() {
        if ($.fn.dataTable.isDataTable('#mysurveytable_n')) {
            table = $('#mysurveytable_n').DataTable();
            table.destroy();
            // console.log("njovou");
        }
        printJS({
            printable: 'mysurveytable_nm',
            type: 'html',
            header: 'Survey In PDF <?php echo $taskname ?>',
            maxWidth: '1000',
            honorMarginPadding: false,
            targetStyles: ['*']
        })
    }
</script>