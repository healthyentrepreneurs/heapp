<style>
    h4,
    p {
        display: inline
    }
</style>
<div class="col-md-12">
    <div class="panel-body">
        <div class="table-responsive" id="viewbychaptertablepane">
            <p>
                <b>User Logs By Chapter In Book Course</b></h4>
            </p>
            <?php echo $this->load->view($table_survey_url, '', TRUE); ?>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#viewbychapter_report_table').DataTable({
            // "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
    });

    // function gotoreport(id) {
    //     window.location.replace("http://192.168.100.4/heapp/welcome/admin/6?id=" + id);
    // }

    function removeviewbychapterentab() {
        if ($.fn.dataTable.isDataTable('#viewbychapter_report_table')) {
            table = $('#viewbychapter_report_table').DataTable();
            table.destroy();
            // console.log("njovou");
        }
        printJS({
            printable: 'viewbychaptertablepane',
            type: 'html',
            header: 'PDF <?php echo $taskname ?>',
            maxWidth: '1000',
            honorMarginPadding: false,
            targetStyles: ['*']
        })
    }
</script>