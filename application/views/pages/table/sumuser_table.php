<table class="table table-striped table-hover" id="sumuser_report_table">
    <thead>
        <tr>
            <th class="center">FULL NAME</th>
            <th class="center">USERNAME</th>
            <th class="center">BOOKS VIEWED</th>
            <th class="center">CHAPTERS VIEWED</th>
            <th class="center">LAST ACTIVITY DATE</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($survey_reportdata as $keyvalue_in_sub => $value_in_sub) {
        ?>
            <tr>
                <td class="center"><?= $value_in_sub['fullnames'] ?></td>
                <td class="center"><?= $value_in_sub['username'] ?></td>
                <td class="center"><?= $value_in_sub['books_veiwed']  ?></td>
                <td class="center"><?= $value_in_sub['chapters']  ?></td>
                <td class="center"><?= $value_in_sub['lastactivitydate']  ?></td>
            </tr>
        <?php
        }
        ?>
    <tbody>
</table>