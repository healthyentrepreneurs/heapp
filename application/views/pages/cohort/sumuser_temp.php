<style>
    h4,
    p {
        display: inline
    }
</style>
<div class="col-md-12">
    <div class="panel-body">
        <div class="table-responsive" id="sumusertablepane">
            <p>
                <b>Summary Users Unique Books All Chapters Per User</b></h4>
            </p>
            <?php echo $this->load->view($table_survey_url, '', TRUE); ?>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#sumuser_report_table').DataTable({
            // "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
    });

    // function gotoreport(id) {
    //     window.location.replace("http://192.168.43.88/heapp/welcome/admin/6?id=" + id);
    // }

    function removesumusertab() {
        if ($.fn.dataTable.isDataTable('#sumuser_report_table')) {
            table = $('#sumuser_report_table').DataTable();
            table.destroy();
            // console.log("njovou");
        }
        printJS({
            printable: 'sumusertablepane',
            type: 'html',
            header: 'PDF <?php echo $taskname ?>',
            maxWidth: '1000',
            honorMarginPadding: false,
            targetStyles: ['*']
        })
    }
</script>