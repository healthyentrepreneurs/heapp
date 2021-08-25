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
                    <?php
                    if (array_key_exists('text', $value) && $value['type'] != "file") {
                    ?>
                        <td><?= $value['text'] ?></td>
                    <?php
                    } else if (array_key_exists('text', $value) && $value['type'] == "file") {
                    ?>
                        <td>
                            <?php
                            $object_image=$value['text'];
                            if(is_array($object_image)){
?>
 <img src="<?= base_url('uploadsurvey/' . $object_image[0]['name']) ?>" alt="image" width="100" height="100" />
<?php
                            }else{
                                ?>
                                <img src="<?= base_url('uploadsurvey/' . $value['text']) ?>" alt="image" width="100" height="100" />
                                <?php
                            }
                            ?>
                        </td>
                    <?php
                    } else {
                    ?>
                        <td></td>
                    <?php
                    }
                    ?>

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
    <a href="javascript:window.close();">Back From Here</a>
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