<?php
$ledger_per = $this->session->userdata('ledger_per');
// print_array($cohorts);
?>
<div class="row" id="add_employeediv">
    <div class="col-sm-12">
        <!-- start: TEXT FIELDS PANEL -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h4 class="panel-title">Add <span class="text-bold">Assign Survey</span></h4>
            </div>
            <div class="panel-body">
                <h5 class="help-inline">
                    <font size="2" color="green"><?php echo $this->session->flashdata('cohort_success'); ?></font>
                </h5>
                <form id="add_cohort" method="post" class="form-horizontal" role="form" action="<?= base_url('survey/addsurveycohort') ?>">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="form-field-1">
                            Surveys
                        </label>
                        <div class="col-sm-9">
                            <select class="form-control search-select" placeholder="Select Survey" name="survay_object" id="survay_object">
                                <option value="">-SELECT SURVEY-</option>
                                <?php
                                foreach ($surveys as $value) {
                                ?>
                                    <option value="<?php echo $value['id']; ?>" <?php echo $value['id'] == printvalues("survey_id", $ledger_per) ? "selected" : "" ?>>
                                        <?= $value['fullname'] ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                            <h6 class="help-inline">
                                <font size="1" color="red"><?php echo $this->session->flashdata('survay_object'); ?></font>
                            </h6>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="form-field-1">
                            Cohorts
                        </label>
                        <div class="col-sm-9">
                            <select id="form-field-select-4" class="form-control search-select" placeholder="Select COHORT" name="cohort_object" id="cohort_object">
                                <option value="">-SELECT COHORT-</option>
                                <?php
                                foreach ($cohorts as $value) {
                                ?>
                                    <option value="<?= $value['id'] . '@@' . $value['name'] . '@@' . $value['idnumber'] ?>" <?php echo $value['id'] == printvalues("cohort_id", $ledger_per) ? "selected" : "" ?>>
                                        <?= $value['name'] ?>
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
                                <button class="btn btn-blue btn-block" type="submit">
                                    Assign<i class="fa fa-arrow-circle-right"></i>
                                </button>
                            </p>
                        </div>
                    </div>
                    <input type="hidden" name="id_n" id="id_n" value="<?=printvalues("id", $ledger_per)?>">
                </form>
            </div>
            <?php echo $this->load->view('pages/table/cohort_survey', '', TRUE); ?>
        </div>
        <!-- end: TEXT FIELDS PANEL -->
    </div>
</div>