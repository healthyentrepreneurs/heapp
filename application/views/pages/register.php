<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">
                    <form role="form" class="form-horizontal" action="<?= base_url('auth/register') ?>" method="POST">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="firstname">
                                Phone Number
                            </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="E.g 0700716751" id="phonenumber" name="phonenumber" class="form-control" value="<?=set_value('phonenumber')?>">
                                <div class="control-group error">
                                    <h6 class="help-inline">
                                        <font size="1" color="red"><?php echo $this->session->flashdata('phonenumber'); ?></font>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="firstname">
                                First Name
                            </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="First Name" id="firstname" name="firstname" class="form-control">
                                <div class="control-group error">
                                    <h6 class="help-inline">
                                        <font size="1" color="red"><?php echo $this->session->flashdata('firstname'); ?></font>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="secondname">
                                Second Name
                            </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="Second Name" id="secondname" name="secondname" class="form-control">
                                <div class="control-group error">
                                    <h6 class="help-inline">
                                        <font size="1" color="red"><?php echo $this->session->flashdata('secondname'); ?></font>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="thirdname">
                                Third Name
                            </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="Text Field" id="thirdname" name="thirdname" class="form-control">
                                <div class="control-group error">
                                    <h6 class="help-inline">
                                        <font size="1" color="red"><?php echo $this->session->flashdata('thirdname'); ?></font>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- <label for="form-field-select-4">
                                Dropdown Multiple Select
                            </label> -->
                            <label class="col-sm-2 control-label" for="category">
                                Driver Category
                            </label>
                            <div class="col-sm-9">
                                <select id="category" name="category" class="form-control search-select">
                                    <option value="">-- Select Category --</option>
                                    <option value="1">CONDUCTOR</option>
                                    <option value="2">DRIVER</option>
                                </select>
                                <div class="control-group error">
                                    <h6 class="help-inline">
                                        <font size="1" color="red"><?php echo $this->session->flashdata('category'); ?></font>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="form-field-2">
                                Password
                            </label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="" id="password" name="password" class="form-control">
                                <div class="control-group error">
                                    <h6 class="help-inline">
                                        <font size="1" color="red"><?php echo $this->session->flashdata('password'); ?></font>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="repassword">
                                Retype Password
                            </label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="" id="repassword" name="repassword" class="form-control">
                                <div class="control-group error">
                                    <h6 class="help-inline">
                                        <font size="1" color="red"><?php echo $this->session->flashdata('repassword'); ?></font>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2 col-sm-offset-9">
                                <button class="btn btn-blue next-step btn-block">
                                    Register <i class="fa fa-arrow-circle-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="panel-body">
        
    </div> -->
</div>