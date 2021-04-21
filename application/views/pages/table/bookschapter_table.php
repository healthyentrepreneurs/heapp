<table class="table table-striped table-hover" id="mybookchaptertable_n">
    <thead>
        <tr>
            <th></th>
            <th class="center">COURSE NAME</th>
            <th class="center">BOOK NAME</th>
            <th class="center">USER NAME</th>
            <th class="center">FULL NAMES/th>
            <th class="center">DATE SUBMITTED/th>
            <th class="center">TIME SUBMITTED</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($survey_reportdata as $keyvalue_in_sub => $value_in_sub) {
        ?>
            <tr>
                <td class="center"><img src="<?= base_url($value_in_sub['name_course_image'].'token='.$value_in_sub['token']) ?>" alt="image" width="100" height="100" /></td>
                <td class="center"><?= $value_in_sub['name_course'].' | '.$value_in_sub['course_shortname']   ?></td>
                <td class="center"><?= $value_in_sub['book_name'] ?></td>
                <td class="center"><?= $value_in_sub['user_id'].' | '.$value_in_sub['he_names']  ?></td>
                <td class="center"><?= date("F jS, Y", strtotime($value_in_sub['date_inserted'])) ?></td>
                <td class="center"><?= date("h:i:sa", strtotime($value_in_sub['date_inserted'])) ?></td>
                <td class="center">
                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                        <a href="<?= base_url('welcome/admin/11/' . $value_in_sub['id']) ?>" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="More Details" target="_blank"><i class="fa fa-share"></i></a>
                    </div>
                    <div class="visible-xs visible-sm hidden-md hidden-lg">
                        <div class="btn-group">
                            <a class="btn btn-green dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                <i class="fa fa-cog"></i> <span class="caret"></span>
                            </a>
                            <ul role="menu" class="dropdown-menu pull-right dropdown-dark">
                                <li>
                                    <a role="menuitem" tabindex="-1" href="">
                                        <i class="fa fa-share"></i> Book Details
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
        }
        ?>
    <tbody>
</table>