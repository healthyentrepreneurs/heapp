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
            "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
    });

    // function gotoreport(id) {
    //     window.location.replace("http://localhost/heapp/welcome/admin/6?id=" + id);
    // }

    function removeviewbybooktab() {
        var tble = document.getElementById('viewbybook_report_table');
        var row = tble.rows; // Getting the rows 
        for (var i = 0; i < row[0].cells.length; i++) {
            // Getting the text of columnName 
            var str = row[0].cells[i].innerHTML;

            // If 'Geek_id' matches with the columnName  
            if (str == "") {
                for (var j = 0; j < row.length; j++) {

                    // Deleting the ith cell of each row 
                    row[j].deleteCell(i);
                }
            }
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