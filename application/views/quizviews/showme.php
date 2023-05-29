<!DOCTYPE html>
<html>

<head>
    <title>Page Title</title>
    <script type="text/javascript" charset="utf-8" src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="<?= base_url() ?>quizofflinedir/css/all">
    <link rel="stylesheet" href="<?= base_url() ?>quizofflinedir/css/yui-moodlesimple-min.css">
</head>

<body>

    <?php
    $quizdata = json_decode($quizdata, true);
    $firstHtml = $quizdata['questions'][0]['html'];
    $decodedHtml = base64_decode($firstHtml);
    echo $decodedHtml;
    ?>
    <script type="text/javascript" src="<?= base_url() ?>quizofflinedir/js/require.min.js"></script>
    <!-- <script type="text/javascript" src="<?= base_url() ?>quizofflinedir/js/javascript-static.js"></script> -->
    <script type="text/javascript" src="<?= base_url() ?>quizofflinedir/js/yui-moodlesimple-min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>quizofflinedir/js/polyfill.js"></script>
    <script>
    </script>
</body>

</html>