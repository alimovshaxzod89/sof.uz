<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tez kunda - Minbar.uz</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="apple-touch-icon" sizes="57x57"
          href="https://static.minbar.media/assets/92750451/img//favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60"
          href="https://static.minbar.media/assets/92750451/img//favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72"
          href="https://static.minbar.media/assets/92750451/img//favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76"
          href="https://static.minbar.media/assets/92750451/img//favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114"
          href="https://static.minbar.media/assets/92750451/img//favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120"
          href="https://static.minbar.media/assets/92750451/img//favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144"
          href="https://static.minbar.media/assets/92750451/img//favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152"
          href="https://static.minbar.media/assets/92750451/img//favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180"
          href="https://static.minbar.media/assets/92750451/img//favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
          href="https://static.minbar.media/assets/92750451/img//favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32"
          href="https://static.minbar.media/assets/92750451/img//favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96"
          href="https://static.minbar.media/assets/92750451/img//favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16"
          href="https://static.minbar.media/assets/92750451/img//favicon/favicon-16x16.png">
    <link rel="manifest" href="https://static.minbar.media/assets/92750451/img//favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage"
          content="https://static.minbar.media/assets/92750451/img//favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#414042">

    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>
<body>

<!--  -->
<div class="simpleslide100">
    <div class="simpleslide100-item bg-img1" style="background-image: url('images/bg03.jpg');"></div>
    <div class="simpleslide100-item bg-img1" style="background-image: url('images/bg02.jpg');"></div>
    <div class="simpleslide100-item bg-img1" style="background-image: url('images/bg01.jpg');"></div>
</div>

<div class="size1 overlay1">
    <!--  -->
    <div class="size1 flex-col-c-m p-l-15 p-r-15 p-t-50 p-b-50">
        <h3 class="l1-txt1 txt-center p-b-25">
            <img width="220px" src="https://static.minbar.media/assets/92750451/img/logo-v2.svg">
        </h3>

        <p class="m2-txt1 txt-center p-b-48">
            Tez kunda, bizni kuzatib boring!
        </p>
        <?php
        date_default_timezone_set('Asia/Tashkent');

        $start = date_create_from_format('d-m-Y H:i', "01-03-2019 00:00");
        $time  = time();
        $left  = $start->getTimestamp() - $time - 1;
        $days  = $left / (3600 * 24);

        ?>
        <div class="flex-w flex-c-m cd100 p-b-33">
            <div class="flex-col-c-m size2 bor1 m-l-15 m-r-15 m-b-20 <?= $days < 1 ? 'd-none' : '' ?>">
                <span class="l2-txt1 p-b-9 days"></span>
                <span class="s2-txt1">kun</span>
            </div>

            <div class="flex-col-c-m size2 bor1 m-l-15 m-r-15 m-b-20">
                <span class="l2-txt1 p-b-9 hours"></span>
                <span class="s2-txt1">soat</span>
            </div>

            <div class="flex-col-c-m size2 bor1 m-l-15 m-r-15 m-b-20">
                <span class="l2-txt1 p-b-9 minutes"></span>
                <span class="s2-txt1">daqiqa</span>
            </div>

            <div class="flex-col-c-m size2 bor1 m-l-15 m-r-15 m-b-20">
                <span class="l2-txt1 p-b-9 seconds"></span>
                <span class="s2-txt1">soniya</span>
            </div>
        </div>

    </div>
</div>

<!--===============================================================================================-->
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/countdowntime/countdowntime.js"></script>

<script>
    $('.cd100').countdown100({
        /*Set Endtime here*/
        /*Endtime must be > current time*/
        endtimeYear: 0,
        endtimeMonth: 0,
        endtimeDate: <?=$days?>,
        endtimeHours: <?=$hours = ($left - $days * 3600 * 24) / 3600?>,
        endtimeMinutes: <?=$minutes = ($left - $days * 3600 * 24 - $hours * 3600) / 60?>,
        endtimeSeconds: <?=$seconds = ($left - $days * 3600 * 24 - $hours * 3600 - $minutes * 60)?>,
        timeZone: ""
        // ex:  timeZone: "America/New_York"
        //go to " http://momentjs.com/timezone/ " to get timezone
    });
</script>
<!--===============================================================================================-->
<script src="vendor/tilt/tilt.jquery.min.js"></script>
<script>
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>
<!--===============================================================================================-->
<script src="js/main.js"></script>

</body>
</html>