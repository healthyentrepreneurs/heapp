<?php
$lead_take = $survey_reportdata[$survey_reportdata['key']];
$balance = $survey_reportdata['howbig'];
unset_post($lead_take, 'username');
unset_post($lead_take, 'fullname');
unset_post($lead_take, 'submitted_date');
unset_post($survey_reportdata, 'key');
unset_post($survey_reportdata, 'howbig');
$titles_namesk = array_column($lead_take, 'title');
$extra_three = array(
    'Username',
    'Full Name',
    'Submitted Date'
);
$titles_name = array_merge($extra_three, $titles_namesk);
// $survey_reportdata
?>
<table class="table table-striped table-hover" id="mysurveytable_jaja">
    <thead>
        <tr>
            <?php
            foreach ($titles_name as $valuenn) {
            ?>
                <th class="center"><?= $valuenn ?></th>
            <?php
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($survey_reportdata as $keyvalue_in_sub => $value_in_sub) {
            $time_data = $value_in_sub['submitted_date'];
            $username = $value_in_sub['username'];
            $fullname = $value_in_sub['fullname'];
            //Remove 
            unset_post($value_in_sub, 'username');
            unset_post($value_in_sub, 'fullname');
            unset_post($value_in_sub, 'submitted_date');
            $tr_data_submission = array_column($value_in_sub, 'text');
            $tr_data_title = array_column($value_in_sub, 'title');
            // print_array($value_in_sub);
            $check_what = count($tr_data_submission);
        ?>
            <tr>
                <td class="center"><?= $username ?></td>
                <td class="center"><?= $fullname ?></td>
                <td class="center"><?= $time_data ?></td>
                <?php
                foreach ($titles_namesk as $key => $valuepp) {
                    $getvalue = recursive_array_search($tr_data_title, $valuepp);
                    if (!empty($getvalue)) {
                ?>
                        <td class="center">
                            Value
                        </td>
                    <?php
                    } else {
                    ?>
                        <td class="center">
                            No
                        </td>
                <?php
                    }
                }
                ?>
            </tr>
        <?php
        }
        ?>
    <tbody>
</table>