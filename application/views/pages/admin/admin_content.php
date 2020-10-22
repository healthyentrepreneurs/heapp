<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="panel panel-white">
            <div class="panel-heading border-light">
                <h4 class="panel-title">Available Courses <span class="text-bold">Moodle and Helper</span></h4>
                <ul class="panel-heading-tabs border-light">
                    <li>
                        <div class="rate">
                            <i class="fa fa-caret-up text-green"></i><span class="value"><?= count($courses) ?></span><span class="percentage">TOTAL</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="panel-body">
                <?php echo $this->load->view('pages/table/course', '', TRUE); ?>
            </div>
        </div>
    </div>
</div>