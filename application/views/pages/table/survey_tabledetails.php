<table class="table table-striped table-hover" id="mysurveytable_jaja">
    <thead>
        <tr>
            <?php
            foreach ($titles as $valuenn) {
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
            $time_data = $value_in_sub['time_data'];
            $username = $value_in_sub['username'];
            $fullname = $value_in_sub['fullname'];
            //Remove 
            unset_post($value_in_sub, 'username');
            unset_post($value_in_sub, 'fullname');
            unset_post($value_in_sub, 'time_data');
            // print_array($value_in_sub);
        ?>
            <tr>
                <td class="center"><?= $username ?></td>
                <td class="center"><?= $fullname ?></td>
                <td class="center"><?= $time_data ?></td>
                <?php
                foreach ($value_in_sub as $key => $valuepp) {
                ?>
                    <td class="center"><?= $valuepp['text'] ?></td>
                <?php
                }
                ?>
            </tr>
        <?php
        }
        ?>
    <tbody>
</table>