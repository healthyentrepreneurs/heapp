<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-white">
            <div class="panel-heading border-light">
                <h4 class="panel-title">Registered <span class="text-bold">DRIVERS</span></h4>
                <ul class="panel-heading-tabs border-light">
                    <li>
                        <div class="rate">
                            <i class="fa fa-caret-up text-green"></i><span class="value"><?= count($drivers) ?></span><span class="percentage">TOTAL</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="panel-body">
                <?php echo $this->load->view('pages/table/driver', '', TRUE); ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-white">
            <div class="panel-heading">
                <i class="clip-bars"></i>
                <h4 class="panel-title">Registered <span class="text-bold">CONDUCTORS</span></h4>
                <ul class="panel-heading-tabs border-light">
                    <li>
                        <div class="rate">
                            <i class="fa fa-caret-up text-green"></i><span class="value"><?= count($conductors) ?></span><span class="percentage">TOTAL</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="panel-body">
                <?php echo $this->load->view('pages/table/conductor', '', TRUE); ?>
            </div>
        </div>
    </div>
</div>