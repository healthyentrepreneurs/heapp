<?php
// print_array($courses_sub);
?>
<div class="row">
    <div class="col-md-12">
        <!-- start: TABLE WITH IMAGES PANEL -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h4 class="panel-title">Course <span class="text-bold"><?= $coursename ?></span></h4>
                <div class="panel-tools">
                    <div class="dropdown">
                        <a data-toggle="dropdown" class="btn btn-xs dropdown-toggle btn-transparent-grey">
                            <i class="fa fa-cog"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-light pull-right" role="menu">
                            <li>
                                <a class="panel-collapse collapses" href="#"><i class="fa fa-angle-up"></i> <span>Collapse</span> </a>
                            </li>
                            <li>
                                <a class="panel-refresh" href="#"> <i class="fa fa-refresh"></i> <span>Refresh</span> </a>
                            </li>
                            <li>
                                <a class="panel-config" href="#panel-config" data-toggle="modal"> <i class="fa fa-wrench"></i> <span>Configurations</span></a>
                            </li>
                            <li>
                                <a class="panel-expand" href="#"> <i class="fa fa-expand"></i> <span>Fullscreen</span></a>
                            </li>
                        </ul>
                    </div>
                    <a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="center"></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($courses_sub as $keyhead => $headers) {
                    ?>
                        <tr>
                            <td><b><?= $headers['name'] ?></b></td>
                            <td><b><?= $headers['summary'] ?></b></td>
                        </tr>
                        <tr>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="center">Activity</th>
                                        <th class="center">Name</th>
                                        <th class="center"></th>
                                        <th class="center">Edit Icon</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($headers['modules'] as $keyvalue_in_sub => $value_in_sub) {
                                    ?>
                                        <tr>
                                            <td class="center"><?= $value_in_sub['modname'] ?></td>
                                            <td class="center"><?= $value_in_sub['name'] ?></td>
                                            <td class="center"><img src="<?= $value_in_sub['modicon'] ?>" alt="image" width="50" height="50" style="background-color: #689F59;"/></td>
                                            <td class="center">
                                                <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                    <a href="<?= base_url('imagemanager/upload_image_sub?link=' . encryptValue($value_in_sub['modicon']) . '&name=' . $value_in_sub['name'] . '&type=' . $value_in_sub['modname']) ?>" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Edit"><i class="fa fa-share"></i></a>
                                                </div>
                                                <div class="visible-xs visible-sm hidden-md hidden-lg">
                                                    <div class="btn-group">
                                                        <a class="btn btn-green dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                                            <i class="fa fa-cog"></i> <span class="caret"></span>
                                                        </a>
                                                        <ul role="menu" class="dropdown-menu pull-right dropdown-dark">
                                                            <li>
                                                                <a role="menuitem" tabindex="-1" href="<?= base_url('imagemanager/upload_image_sub?link=' . encryptValue($value_in_sub['modicon'])) ?>">
                                                                    <i class="fa fa-share"></i> Check
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
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- end: TABLE WITH IMAGES PANEL -->
    </div>
</div>