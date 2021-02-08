<?php
$titles_name = array();
$balance = $key_bign['howbig'];
if (!empty($survey_reportdata)) {
    $study_case = $survey_reportdata[$key_bign['key']];
    $a = $study_case['data_submission'];
    $titles_name_inter = array();
    foreach ($a as $keyn => $value_n) {
        array_push($titles_name_inter, $value_n['title']);
    }
    $extra_three = array(
        'Username',
        'Full Name',
        'Submitted Date'
    );
    $titles_name = array_merge($extra_three, $titles_name_inter);
    // print_array($titles_name);
}
?>
<table class="table table-striped table-hover" id="mysurveytable_n">
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
            $time_data_array = explode(" ", $time_data);
            $username = $value_in_sub['username'];
            $fullname = $value_in_sub['fullname'];
            $tr_data_submission = $value_in_sub['data_submission'];
            $check_what = count($tr_data_submission);
        ?>
            <tr>
                <td class="center"><?= $username ?></td>
                <td class="center"><?= $fullname ?></td>
                <td class="center"><?= $time_data ?></td>
                <?php
                foreach ($tr_data_submission as $key => $value) {
                ?>
                    <td class="center">
                        <?php
                        if (array_key_exists("value_score", $value)) {
                            echo $value['value_score'];
                        } else {
                            echo " ";
                        }
                        ?>
                    </td>
                    <?php
                }
                if ($check_what < $balance) {
                    $additional_td = $balance - $check_what;
                    for ($i = 0; $i < $additional_td; $i++) {
                    ?>
                        <td class="center"></td>
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