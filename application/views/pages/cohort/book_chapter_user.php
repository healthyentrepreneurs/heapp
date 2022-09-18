<style>
    h4,
    p {
        display: inline
    }
</style>
<div class="col-md-12">
    <div class="panel-body">
        <div class="table-responsive" id="sumbooktablepaneuserchap">
            <p>
                <b>Total Count For Views of Books and Chapters Per User</b></h4>
            </p>
            <?php echo $this->load->view($table_survey_url, '', TRUE); ?>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        // $('#detailedviewsuserbox_report_table').DataTable({
        //     "pageLength": 4,
        //     "paging": false,
        //     "ordering": false,
        //     "info": false
        // });
        // https://datatables.net/manual/tech-notes/3#retrieve
        $('#detailedviewsuserbox_report_table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ordering: false
            // paging: false
        });
    });

    function removesumbootabuserchapbook() {
        if ($.fn.dataTable.isDataTable('#detailedviewsuserbox_report_table')) {
            table = $('#detailedviewsuserbox_report_table').DataTable();
            table.destroy();
            // console.log("njovou");
        }
        printJS({
            printable: 'sumbooktablepaneuserchap',
            type: 'html',
            header: 'PDF <?php echo $taskname ?>',
            maxWidth: '1000',
            honorMarginPadding: false,
            targetStyles: ['*']
        });
        // else {
        //     table = $('#detailedviewsuserbox_report_table').DataTable({
        //         ordering: false
        //     });
        //     console.log("njovou xxx");
        // }
    }
</script>