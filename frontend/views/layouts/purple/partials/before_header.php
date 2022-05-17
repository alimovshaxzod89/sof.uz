<?php

use yii\helpers\Url;

?>
<div class="cureency">
    <div class="head_currency">
        <span class="h5">Валюта курси:</span> <span class="bank">Ўз.Р.Марказий банки</span>
    </div>
    <div class="info">
        <div class="foot_currency">

            <div class="usd">
                <div class="block"><span>USD</span> <br> <span id="rate_usd"></span></div>
                <div class="block"><span class="micro">АҚШ доллари</span> <span class="micro" id="diff_usd"></span>
                </div>
            </div>

            <div class="euro">
                <div class="block"><span>EUR</span> <br> <span id="rate_eur"></span></div>
                <div class="block"><span class="micro">EВРО</span><span class="micro" id="diff_eur"></span></div>
            </div>

            <div class="rub">
                <div class="block"><span>RUB</span> <br> <span id="rate_rub"></span></div>
                <div class="block"><span class="micro">Россия рубли</span><span class="micro" id="diff_rub"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="running_info">
    <marquee behavior="scroll" direction="left">Sayt test rejimida ishlayapti!!!</marquee>
</div>

<script type="text/javascript">
    var xhr = new XMLHttpRequest();
    var url = '<?= Url::to(['/site/exchange-rate']) ?>'
    xhr.open('GET', url)
    xhr.responseType = 'json'
    xhr.send()

    xhr.onload = function (e) {
        var status = xhr.status
        if (status === 200) {

            var rates = xhr.response

            document.getElementById('rate_usd').innerHTML = rates.USD.Rate
            document.getElementById('rate_eur').innerHTML = rates.EUR.Rate
            document.getElementById('rate_rub').innerHTML = rates.RUB.Rate
            document.getElementById('diff_usd').innerHTML = rates.USD.Diff
            document.getElementById('diff_eur').innerHTML = rates.EUR.Diff
            document.getElementById('diff_rub').innerHTML = rates.RUB.Diff
        }
    }

</script>
