<style>
    h4,
    p {
        display: inline
    }
</style>
<div class="col-md-12">
    <div class="panel-body">
        <div class="table-responsive" id="mychapchap_n">
            <p>
                <b>Chapters  Report In Range <h4> <?= converttodate($startdate, 'F jS, Y') ?> TO <?= converttodate($enddate, 'F jS, Y') ?></b></h4>
            </p>
            <?php echo $this->load->view($table_survey_url, '', TRUE); ?>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#mybookchaptertable_n').DataTable({
            "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
    });

    // function gotoreport(id) {
    //     window.location.replace("http://localhost/heapp/welcome/admin/6?id=" + id);
    // }

    function removepigichap() {
        var tble = document.getElementById('my_chaptercha_table');
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
        // $('#mysurveytable_n').DataTable({
        //     "paging": false,
        //     "ordering": false,
        //     "info": false
        // });
        printJS({
            printable: 'mychapchap_n',
            type: 'html',
            header: 'PDF <?php echo $taskname ?>',
            maxWidth: '1000',
            honorMarginPadding: false,
            targetStyles: ['*']
        })
    }
</script>