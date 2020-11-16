<?php
// print_array($courses);
?>
<div class="row">
    <div class="col-md-12">
        <!-- start: TABLE WITH IMAGES PANEL -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h4 class="panel-title">Striped <span class="text-bold">rows</span></h4>
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
            <table class="table table-striped table-hover" id="sample-table-2">
                <thead>
                    <tr>
                        <th class="center">Photo</th>
                        <th>Full Name</th>
                        <th class="hidden-xs">Source</th>
                        <th>Course Content</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($courses as $key => $value) {
                    ?>
                        <tr>
                            <td class="center"><img src="<?= $value['image_url_small'] ?>" alt="image" width="200" height="200" /></td>
                            <td><a href="<?= base_url('imagemanager/couser_subcontent?link=' . encryptValue($value['next_link']) . '&fullname=' . $value['fullname']) ?>">
                                    <h3 style="color:#689F59"><?= $value['fullname'] ?></h3>
                                </a></td>
                            <td><?= $value['source'] ?></td>
                            <td class="center">
                                <?php
                                if ($value['source'] == "moodle") {
                                ?>
                                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                                        <a href="<?= base_url('imagemanager/couser_subcontent?link=' . encryptValue($value['next_link']) . '&fullname=' . $value['fullname']) ?>" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Check"><i class="fa fa-share"></i></a>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                                        <a href="#" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Check"><i class="fa fa-share"></i></a>
                                    </div>
                                <?php
                                }
                                ?>

                                <div class="visible-xs visible-sm hidden-md hidden-lg">
                                    <div class="btn-group">
                                        <a class="btn btn-green dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                            <i class="fa fa-cog"></i> <span class="caret"></span>
                                        </a>
                                        <ul role="menu" class="dropdown-menu pull-right dropdown-dark">
                                            <li>
                                                <a role="menuitem" tabindex="-1" href="<?= base_url('imagemanager/couser_subcontent?link=' . encryptValue($value['next_link']) . '&fullname=' . $value['fullname']) ?>">
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
                </tbody>
            </table>
        </div>
        <!-- end: TABLE WITH IMAGES PANEL -->
    </div>
</div>