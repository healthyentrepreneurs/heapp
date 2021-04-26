<table class="table table-striped table-hover" id="viewbychapter_report_table">
    <thead>
        <tr>
            <th class="center">COURSE</th>
            <th class="center">BOOK</th>
            <th class="center">CHAPTER</th>
            <th class="center">USERNAME</th>
            <th class="center">FULL NAME</th>
            <th class="center">DATE</th>
            <th class="center">TIME</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($survey_reportdata as $keyvalue_in_sub => $value_in_sub) {
        ?>
            <tr>
                <td class="center"><?= $value_in_sub['name_course'] ?></td>
                <td class="center"><?= $value_in_sub['book_name'] ?></td>
                <td class="center"><?= $value_in_sub['chaptername']  ?></td>
                <td class="center"><?= $value_in_sub['user_id']  ?></td>
                <td class="center"><?= $value_in_sub['he_names']  ?></td>
                <td class="center"><?= $value_in_sub['datelike']  ?></td>
                <td class="center"><?= $value_in_sub['hoursmins']  ?></td>
            </tr>
        <?php
        }
        ?>
    <tbody>
</table>