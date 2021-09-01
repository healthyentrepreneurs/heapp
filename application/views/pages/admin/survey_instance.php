<?php
// print_array($survey_instance);
?>
<style type="text/css">
    #img-zoomer-box {
  width: 500px;
  height: auto;
  position: relative;
  margin-top: 10px;
}

#img-1 {
  width: 100%;
  height: auto;
}

#img-zoomer-box:hover, #img-zoomer-box:active {
  cursor: zoom-in;
  display: block;
}

#img-zoomer-box:hover #img-2, #img-zoomer-box:active #img-2 {
  opacity: 1;
}
#img-2 {
  width: 340px;
  height: 340px;
  background: url('https://bit.ly/2mgDw0y') no-repeat #FFF;
  box-shadow: 0 5px 10px -2px rgba(0,0,0,0.3);
  pointer-events: none;
  position: absolute;
  opacity: 0;
  border: 4px solid whitesmoke;
  z-index: 99;
  border-radius: 100%;
  display: block;
  transition: opacity .2s;
}
</style>
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
                        if(is_array($image_sci)){
                            $image_sci_second=array_shift($image_sci);
                            if(array_key_exists('name',$image_sci_second)){
                                $image_sci=$image_sci_second['name'];
                            }
                        }
                    ?>
                        <td>
                        <div id="img-zoomer-box">
  <img src="<?= base_url('uploadsurvey/' .$image_sci ) ?>" alt="image" id="img-1" />
  <div id="img-2"></div>
</div>
                            <!-- <img src="<?= base_url('uploadsurvey/' .$image_sci ) ?>" alt="image" width="100" height="100" /> -->
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