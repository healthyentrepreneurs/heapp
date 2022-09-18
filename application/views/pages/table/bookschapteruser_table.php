<table class="table table-striped table-hover" id="detailedviewsuserbox_report_table">
    <thead>
        <tr>
            <th class="center">BOOK NAME</th>
            <th class="center"></th>
            <th class="center">CHAPTER NAME</th>
            <th class="center">CHAPTERS COUNT</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($bookchapter_reportdata as $keyvalue_in_sub => $value_in_sub) {
            $serverurlchap = base_url('report/getchaptersbybookid/' . $value_in_sub['book_id']);
            $server_output = curl_request($serverurlchap, array(), "get", array('App-Key: 123456'));
            $array_of_output = json_decode($server_output, true);
        ?>
            <tr>
                <td class="center">BOOK NAME&ensp;&ensp;<b><?= $value_in_sub['book_name'] ?></b></td>
                <td class="center"></td>
                <td class="center"></td>
                <td class="center">BOOKS COUNT&ensp;&ensp;<b><?= $value_in_sub['bookcount'] ?></b></td>
            </tr>
            <?php
            foreach ($array_of_output as $key_in => $value_in) {
            ?>
                <tr>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"><?= $value_in['chaptername'] ?></td>
                    <td class="center"><?= $value_in['chaptercount'] ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td class="center"></td>
                <td class="center"></td>
                <td class="center"></td>
                <td class="center"></td>
            </tr>
        <?php
        }
        ?>
    <tbody>
</table>