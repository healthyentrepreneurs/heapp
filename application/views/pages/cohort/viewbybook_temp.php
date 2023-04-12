<style>
    h4,
    p {
        display: inline
    }
</style>
<div class="col-md-12">
    <div class="panel-body">
        <div class="table-responsive" id="viewbybooktablepane">
            <p>
                <b>Users By A Given Book Under A Given Course</b></h4>
            </p>
            <?php echo $this->load->view($table_survey_url, '', TRUE); ?>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#viewbybook_report_table').DataTable({
            // "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
    });

    // function gotoreport(id) {
    //     window.location.replace("http://192.168.100.4/heapp/welcome/admin/6?id=" + id);
    // }

    function removeviewbybooktab() {
        if ($.fn.dataTable.isDataTable('#viewbybook_report_table')) {
            table = $('#viewbybook_report_table').DataTable();
            table.destroy();
            // console.log("njovou");
        }
        printJS({
            printable: 'viewbybooktablepane',
            type: 'html',
            header: 'PDF <?php echo $taskname ?>',
            maxWidth: '1000',
            honorMarginPadding: false,
            targetStyles: ['*']
        })
    }
</script>