<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">
                    <form role="form" class="form-horizontal" action="<?= base_url("auth/login") ?>" method="POST">
                        <?php
                        echo '<font size="2" color="green">' . $this->session->flashdata('success') . '</font>';
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="firstname">
                                Phone Number
                            </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="E.g johnen" id="username" name="username" class="form-control">
                                <div class="control-group error">
                                    <h6 class="help-inline">
                                        <font size="1" color="red"><?php echo $this->session->flashdata('username'); ?></font>
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
                            <div class="col-sm-2 col-sm-offset-9">
                                <button class="btn btn-green next-step btn-block">
                                    Login <i class="fa fa-arrow-circle-right"></i>
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