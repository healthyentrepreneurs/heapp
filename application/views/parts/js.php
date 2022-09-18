<script src="<?=base_url()?>/assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="<?=base_url()?>/assets/js/plugins/bootstrap-switch.js"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="<?=base_url()?>/assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
<!--  Plugin for the DatePicker, full documentation here: https://github.com/uxsolutions/bootstrap-datepicker -->
<script src="<?=base_url()?>/assets/js/plugins/moment.min.js"></script>
<script src="<?=base_url()?>/assets/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- Control Center for Paper Kit: parallax effects, scripts for the example pages etc -->
<script src="<?=base_url()?>/assets/js/paper-kit.js?v=2.2.0" type="text/javascript"></script>
<!--  Google Maps Plugin    -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.0/knockout-min.js"></script>
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/survey.ko.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ace.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.10/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
<!-- Uncomment to enable Select2 <script src="https://unpkg.com/jquery"></script> <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->
<link href="<?= base_url() ?>assets/surveyjs/1.9.48/survey-creator.min.css" type="text/css" rel="stylesheet" />
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/survey-creator.min.js"></script>
<link href="<?= base_url() ?>assets/surveyjs/1.9.48/survey.analytics.datatables.min.css" type="text/css" rel="stylesheet" />
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/survey.analytics.datatables.min.js"></script>
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/survey.core.min.js"></script>
<script src="<?= base_url() ?>assets/surveyjs/1.9.48/surveyjs-widgets.min.js"></script>
<script>
    $(document).ready(function() {

        if ($("#datetimepicker").length != 0) {
            $('#datetimepicker').datetimepicker({
                icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-chevron-up",
                    down: "fa fa-chevron-down",
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-screenshot',
                    clear: 'fa fa-trash',
                    close: 'fa fa-remove'
                }
            });
        }

        function scrollToDownload() {

            if ($('.section-download').length != 0) {
                $("html, body").animate({
                    scrollTop: $('.section-download').offset().top
                }, 1000);
            }
        }
    });
</script>