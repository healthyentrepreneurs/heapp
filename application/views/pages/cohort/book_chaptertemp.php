<style>
    h4,
    p {
        display: inline
    }
</style>
<div class="col-md-12">
    <div class="panel-body">
        <div class="table-responsive" id="sumbooktablepane">
            <p>
                <b>Summary Books Unique Users and Chapters Per User</b></h4>
            </p>
            <?php echo $this->load->view($table_survey_url, '', TRUE); ?>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#summary_bool_report_table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            // "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
    });

    // function gotoreport(id) {
    //     window.location.replace("http://192.168.100.4/heapp/welcome/admin/6?id=" + id);
    // }

    function removesumbootab() {
        if ($.fn.dataTable.isDataTable('#summary_bool_report_table')) {
            table = $('#summary_bool_report_table').DataTable();
            table.destroy();
            // console.log("njovou");
        }
        printJS({
            printable: 'sumbooktablepane',
            type: 'html',
            header: 'PDF <?php echo $taskname ?>',
            maxWidth: '1000',
            honorMarginPadding: false,
            targetStyles: ['*']
        })
    }
</script>