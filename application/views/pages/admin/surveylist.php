<div class="row">
    <div class="col-md-12">
        <!-- WALAH CRAP -->
        <!-- start: TABLE WITH IMAGES PANEL -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h4 class="panel-title">Survey <span class="text-bold">LIST</span></h4>
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
                    <!-- <a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a> -->
                </div>
            </div>
            <table class="table table-striped table-hover" id="mysurveytable">
                <thead>
                    <tr>
                        <th class="center"></th>
                        <th class="center">SURVEY NAME</th>
                        <th class="center">DATE CREATED</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($surveydatas as $keyvalue_in_sub => $value_in_sub) {
                    ?>
                        <tr>
                            <td class="center"><img src="<?= base_url('uploadscustome/' . $value_in_sub['image']) ?>" alt="image" width="50" height="50" /></td>
                            <td class="center"><?= $value_in_sub['name'] ?></td>
                            <td class="center"><?= $value_in_sub['datecreated'] ?></td>
                            <td class="center">
                                <div class="visible-md visible-lg hidden-sm hidden-xs">
                                    <a href="<?= base_url('welcome/admin/3?id=' . $value_in_sub['id']) ?>" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="View/Edit"><i class="fa fa-share"></i></a>
                                    <a href="<?= base_url('welcome/admin/5?id=' . $value_in_sub['id']) ?>" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Update Image"><i class="fa fa-pencil fa-fw"></i></a>
                                    <!-- <a href="#" onclick="gotoreport(<?= $value_in_sub['id'] ?>);" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Reports"><i class="fa fa-file-excel-o"></i></a> -->
                                    <a href="#" onclick="heyhey(<?= $value_in_sub['id'] ?>);" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Delete"><i class="fa fa-minus"></i></a>
                                </div>
                                <div class="visible-xs visible-sm hidden-md hidden-lg">
                                    <div class="btn-group">
                                        <a class="btn btn-green dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                            <i class="fa fa-cog"></i> <span class="caret"></span>
                                        </a>
                                        <ul role="menu" class="dropdown-menu pull-right dropdown-dark">
                                            <li>
                                                <a role="menuitem" tabindex="-1" href="<?= base_url('welcome/admin/3?id=' . $value_in_sub['id']) ?>">
                                                    <i class="fa fa-share"></i> View/Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a role="menuitem" tabindex="-1" href="<?= base_url('welcome/admin/5?id=' . $value_in_sub['id']) ?>">
                                                    <i class="fa fa-pencil fa-fw"></i> Update Image
                                                </a>
                                            </li>
                                            <!-- <li>
                                                <a role="menuitem" tabindex="-1" href="#" onclick="gotoreport(<?= $value_in_sub['id'] ?>);">
                                                    <i class="fa fa-file-excel-o"></i> Reports
                                                </a>
                                            </li> -->
                                            <li>
                                                <a role="menuitem" tabindex="-1" href="#">
                                                    <i class="fa fa-minus"></i> Delete
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
        </div>
        <!-- end: TABLE WITH IMAGES PANEL -->
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#mysurveytable').DataTable({
            "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
    });

    function heyhey(id) {
        const notyf = new Notyf({
            duration: 1000,
            position: {
                x: 'right',
                y: 'top',
            },
            types: [{
                    type: 'warning',
                    background: 'orange',
                    icon: {
                        className: 'material-icons',
                        tagName: 'i',
                        text: 'warning'
                    }
                },
                {
                    type: 'error',
                    background: 'indianred',
                    duration: 2000,
                    dismissible: true
                }
            ]
        });
        notyf
            .error({
                message: 'Are you Sure You Want To Delete.',
                dismissible: true
            })
            .on('dismiss', ({
                target,
                event
            }) => foobar(id));

    }

    function gotoreport(id) {
        var url_reportsurvey = "<?php echo base_url("welcome/admin/6?id="); ?>" + id;
        window.location.replace(url_reportsurvey);
    }
    // function foobar(id){
    //     alert(id);
    // }
    function foobar(id) {
        var url_updatesurvey = "<?php echo base_url("survey/deletesurvey"); ?>";
        $.ajax({
            type: 'POST',
            url: url_updatesurvey,
            data: {
                surveyid: id
            },
            success: function(result) {
                console.log(result);
                var notyf = new Notyf();
                notyf.success('Your changes have been successfully saved!');
                window.setTimeout(function() {
                    location.reload()
                }, 1000)

            },
            error: function() {
                alert('Some error found. Please try again!');
            }
        });
        // alert("Hello Hello "+id);
    }
</script>