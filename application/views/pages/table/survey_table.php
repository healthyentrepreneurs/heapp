<table class="table table-striped table-hover" id="mysurveytable_n">
    <thead>
        <tr>
            <th></th>
            <th class="center">SURVEY NAME</th>
            <th class="center">DATE CREATED</th>
            <th class="center">WHO SUBMITTED</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($survey_reportdata as $keyvalue_in_sub => $value_in_sub) {
        ?>
            <tr>
                <td class="center"><img src="<?= base_url('uploadscustome/' . $value_in_sub['image_url_small']) ?>" alt="image" width="100" height="100" /></td>
                <td class="center"><?= $value_in_sub['name'] ?></td>
                <td class="center"><?= date("F jS, Y", strtotime($value_in_sub['dateaddedsurvey'])) ?></td>
                <td class="center"><?= $value_in_sub['userid'] ?></td>

                <td class="center">
                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                        <a href="<?= base_url('welcome/admin/7/' . $value_in_sub['id'] . "/" . $value_in_sub['surveyid'] . '?userid=' . $value_in_sub['userid'] . '&name=' . $value_in_sub['name']) ?>" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Survey Details"><i class="fa fa-share"></i></a>
                    </div>
                    <div class="visible-xs visible-sm hidden-md hidden-lg">
                        <div class="btn-group">
                            <a class="btn btn-green dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                <i class="fa fa-cog"></i> <span class="caret"></span>
                            </a>
                            <ul role="menu" class="dropdown-menu pull-right dropdown-dark">
                                <li>
                                    <a role="menuitem" tabindex="-1" href="">
                                        <i class="fa fa-share"></i> Survey Details
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