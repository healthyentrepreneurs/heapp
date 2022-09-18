<?php
$ledger_per = $this->session->userdata('ledger_per');
// print_array($cohorts);
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- https://bootsnipp.com/snippets/lV88M -->
<style>
    .select2-container .select2-selection--single {
        height: 34px !important;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }
</style>
<!-- <div class="panel panel-white"> -->
    <div class="panel-heading">
        <h4 class="panel-title">NOTE <span class="text-bold">USERS can Use the same download if they are closely related</span></h4>
    </div>
    <div class="panel-body">
        <?php
        if ($this->session->flashdata('downdstatus')) {
            $status = $this->session->flashdata('downdstatus');
            if ($status == "1") {
        ?>
                <h5 class="help-inline">
                    <font size="3" color="green"><?php echo $this->session->flashdata('downdmsg'); ?></font>
                    <!--                            http://192.168.43.88/moodle/moodleapi/userdownloads/3HE_Health.zip-->
                    <a href="<?= MOODAPI . '/userdownloads/' . $this->session->flashdata('iduser') . 'HE_Health.zip' ?>" download>click here to download</a>
                </h5>
            <?php
            }
            if ($status == "2") {
            ?>
                <h5 class="help-inline">
                    <font size="2" color="red"><?php echo $this->session->flashdata('downdmsg'); ?></font>
                </h5>
        <?php
            }
        }
        ?>
        <!--                Circling Back-->
        <form id="add_cohort" method="post" class="form-horizontal" role="form" action="<?= base_url('downloadable/create_content') ?>">
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    User
                </label>
                <div class="col-sm-9">
                    <select class="form-control select2" placeholder="Select USER" name="cohort_object" id="cohort_object">
                        <option value="">-SELECT USER-</option>
                        <?php
                        foreach ($users as $value) {
                        ?>
                            <option value="<?= $value['id'] . '@' . $value['username'] ?>">
                                <?= $value['username'] . ' | ' . $value['firstname'] . ' ' . $value['lastname'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <h6 class="help-inline">
                        <font size="1" color="red"><?php echo $this->session->flashdata('cohort_object'); ?></font>
                    </h6>
                </div>
            </div>
            <br>
            <div class="form-group">
                <div class="panel-body">
                    <p>
                        <button class="btn btn-success btn-block" type="submit">
                            Download <i class="fa fa-arrow-circle-right"></i>
                        </button>
                    </p>
                </div>
            </div>
        </form>
    </div>
    <!-- <h1>TABLE HERE</h1> -->
<!-- </div> -->
<script>
    $(document).ready(function() {
        // $('select').selectpicker();
        $('.select2').select2();
    });
</script>