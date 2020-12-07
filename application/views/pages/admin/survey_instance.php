<?php
// print_array($survey_instance);
?>
<div class="no-margin partition-white">
    <table class="table table-striped table-hover" id="instance_survey">
        <thead>
            <tr>
                <th>Question Title</th>
                <th></th>
                <th>Answer</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($survey_instance as $key => $value) {
            ?>
                <tr>
                    <td><?= $value['title'] ?></td>
                    <td><?= $value['description'] ?></td>
                    <td>
                        <?php
                        if ($value['type'] == "file") {
                        ?>
                            <div class="zoom-box">
                                <img src="<?= base_url('uploadsurvey/' . $value['value_score']) ?>" alt="image" width="100" height="100" />
                            </div>

                        <?php
                        } else {
                            echo $value['value_score'];
                        }
                        ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <!-- <div class="tab-content partition-white">
        <div id="attandanceone_surv" class="tab-pane padding-bottom-5 active">
        </div>
        <div id="attandancetwo_surv" class="tab-pane padding-bottom-5">
        </div>

    </div> -->
</div>
<script>
    $(document).ready(function() {
        $('#instance_survey').DataTable({
            "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
    });
</script>