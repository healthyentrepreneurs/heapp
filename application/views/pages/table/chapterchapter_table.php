<table class="table table-striped table-hover" id="my_chaptercha_table">
    <thead>
        <tr>
            <th class="center">FULL NAMES</th>
            <th class="center">COURSE</th>
            <th class="center">BOOK NAME</th>
            <th class="center">CHAPTER NAME</th>
            <th class="center">DATE SUBMITTED</th>
            <th class="center">TIME SUBMITTED</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($survey_reportdata as $keyvalue_in_sub => $value_in_sub) {
        ?>
            <tr>
                <td class="center"><?= $value_in_sub['he_names']  ?></td>
                <td class="center"><?= '<b>' . $value_in_sub['course_shortname'] . '<b>'   ?></td>
                <td class="center"><?= $value_in_sub['book_name'] ?></td>
                <td class="center"><?= $value_in_sub['chaptername'] ?></td>
                <td class="center"><?= date("F jS, Y", strtotime($value_in_sub['date_inserted'])) ?></td>
                <td class="center"><?= date("h:i:sa", strtotime($value_in_sub['date_inserted'])) ?></td>
            </tr>
        <?php
        }
        ?>
    <tbody>
</table>