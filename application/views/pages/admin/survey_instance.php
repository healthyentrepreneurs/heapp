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
                        // To be Revisted
                        $image_sci=$value['text'];
                        print_array($image_sci);
                        if(is_array($image_sci)){
                            $image_sci_second=array_shift($image_sci);
                            if(array_key_exists('name',$image_sci_second)){
                                $image_sci=$image_sci_second['name'];
                            }
                        }
                        // print_array($image_sci);
                    ?>
                        <td>
                            <img src="<?= base_url('uploadsurvey/' .$image_sci ) ?>" alt="image" width="100" height="100" />
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