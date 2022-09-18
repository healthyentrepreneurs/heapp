<table class="table table-striped table-hover" id="list_surveycohorts">
    <thead>
        <tr>
            <th>Cohort Name</th>
            <th>Cohort Number</th>
            <th>Survey Name</th>
            <th>Survey Description</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($survey_cohort as $key => $value) {
        ?>
            <tr id="row<?= $value['id'] ?>">
                <td><?= $value['cohort_name'] ?></td>
                <td><?= $value['idnumber'] ?></td>
                <td><?= $value['name'] ?></td>
                <td><?= $value['surveydesc'] ?></td>
                <td class="center">
                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                        <a href="<?= base_url('survey/edit_cosurv?id=' . $value['id']) ?>" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Edit"><i class="fa fa-share"></i></a>
                        <a href="#" onclick="deletecohsurv(<?= $value['id'] ?>);" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Delete"><i class="fa fa-minus"></i></a>
                    </div>
                    <div class="visible-xs visible-sm hidden-md hidden-lg">
                        <div class="btn-group">
                            <a class="btn btn-green dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                <i class="fa fa-cog"></i> <span class="caret"></span>
                            </a>
                            <ul role="menu" class="dropdown-menu pull-right dropdown-dark">
                                <li>
                                    <a role="menuitem" tabindex="-1" href="">
                                        <i class="fa fa-share"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a role="menuitem" tabindex="-1" href="">
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
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#list_surveycohorts').DataTable({
            // "pageLength": 4
            //            "paging": false,
            //            "ordering": false,
            //            "info": false
        });
        var idval = $("#id_n").val();
        var rowid="#row"+idval;
        $(rowid).children('td, th').css('background-color','lime');
        // alert(rowid);
    });

    function deletecohsurv(id) {
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
            }) => call_del(id));

    }

    function call_del(id) {
        var url_deletecohosurvey = "<?php echo base_url("survey/delete_cosurv"); ?>";
        $.ajax({
            type: 'POST',
            url: url_deletecohosurvey,
            data: {
                coho_survid: id
            },
            success: function(result) {
                // console.log(result);
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