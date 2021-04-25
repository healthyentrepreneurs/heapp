<table class="table table-striped table-hover" id="summary_bool_report_table">
    <thead>
        <tr>
            <th class="center">BOOK NAME</th>
            <th class="center">COURSE</th>
            <th class="center">BOOKS VIEWED</th>
            <th class="center">CHAPTERS VIEWED</th>
            <th class="center">UNIQUE USERS VIEWED</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($survey_reportdata as $keyvalue_in_sub => $value_in_sub) {
        ?>
            <tr>
                <td class="center"><?= $value_in_sub['book'] ?></td>
                <td class="center"><?= $value_in_sub['course'] ?></td>
                <td class="center"><?= $value_in_sub['books_veiwed']  ?></td>
                <td class="center"><?= $value_in_sub['chapters']  ?></td>
                <td class="center"><?= $value_in_sub['unique_users']  ?></td>
            </tr>
        <?php
        }
        ?>
    <tbody>
</table>