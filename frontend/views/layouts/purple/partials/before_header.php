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
                <div class="block"><span>USD</span> <br> <span id="rate_usd" class="course"></span></div>
                <div class="block"><span class="micro">АҚШ доллари</span> <span class="micro" id="diff_usd"></span>
                </div>
            </div>

            <div class="euro">
                <div class="block"><span>EUR</span> <br> <span id="rate_eur" class="course"></span></div>
                <div class="block"><span class="micro">EВРО</span><span class="micro" id="diff_eur"></span></div>
            </div>

            <div class="rub">
                <div class="block"><span>RUB</span> <br> <span id="rate_rub" class="course"></span></div>
                <div class="block"><span class="micro">Россия рубли</span><span class="micro" id="diff_rub"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="running_info">
    <marquee behavior="scroll" direction="left" id="birja-content">Sayt test rejimida ishlayapti!!!</marquee>
</div>

<script type="text/javascript">
    function loadRates() {
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
    }
    loadRates()

    function loadBirja() {
        var xhr2 = new XMLHttpRequest();
        var url2 = '<?= Url::to(['/site/birja']) ?>'
        xhr2.open('GET', url2)
        xhr2.responseType = 'json'
        xhr2.send()

        xhr2.onload = function (e) {
            var status = xhr2.status
            if (status === 200) {

                var birjaData = xhr2.response

                var birjaStr = ''
                for (const item of birjaData) {
                    birjaStr += `${item.name} <span class="">${item.summa}</span> `
                }

                document.getElementById('birja-content').innerHTML = birjaStr
            }
        }
    }

    loadBirja()

    // var birjaData = [{"name":"АРМАТУРА (12)","summa":"8 843.86","difference":"87.67","tovarNum":0,"percent":"0.99"},
    //     {"name":"АРМАТУРА (14)","summa":"8 748.52","difference":"13.27","tovarNum":0,"percent":"0.15"},{"name":"БЕНЗИН (80)","summa":"8 423.66","difference":"186.06","tovarNum":0,"percent":"2.21"},{"name":"БЕНЗИН (91)","summa":"10 062.39","difference":"-22.44","tovarNum":0,"percent":"-0.22"},{"name":"ДИЗЕЛЬ ЁҚИЛҒИСИ","summa":"11 924.85","difference":"-147.05","tovarNum":0,"percent":"-1.23"},{"name":"САНОАТ МОЙИ I-20 A","summa":"11 931.10","difference":"-303.13","tovarNum":0,"percent":"-2.54"},{"name":"ОМУХТА ЕМ","summa":"1 771.62","difference":"0.00","tovarNum":0,"percent":"0.00"},{"name":"ПАХТА ЁҒИ","summa":"18 608.41","difference":"133.25","tovarNum":0,"percent":"0.72"},{"name":"МОТОР МОЙИ M-20 A","summa":"11 829.71","difference":"0.00","tovarNum":0,"percent":"0.00"},{"name":"ПОЛИПРОПИЛЕН FR-170H","summa":"19 462.47","difference":"-171.77","tovarNum":0,"percent":"-0.88"},{"name":"ПОЛИПРОПИЛЕН Y130","summa":"19 878.46","difference":"109.94","tovarNum":0,"percent":"0.55"},{"name":"ПОЛИЭТИЛЕН B-Y 460","summa":"17 232.20","difference":"776.94","tovarNum":0,"percent":"4.51"},{"name":"ПОЛИЭТИЛЕН F-0120","summa":"21 584.45","difference":"-259.69","tovarNum":0,"percent":"-1.20"},{"name":"ПОЛИЭТИЛЕН I-0760","summa":"15 261.84","difference":"-64.01","tovarNum":0,"percent":"-0.42"},{"name":"ШАКАР (III тоифа)","summa":"8 675.65","difference":"0.00","tovarNum":0,"percent":"0.00"},{"name":"СУЮЛТИРИЛГАН ГАЗ","summa":"6 453.23","difference":"605.26","tovarNum":0,"percent":"9.38"},{"name":"СУЛЬФАТ АММОНИЙ","summa":"2 440.66","difference":"57.45","tovarNum":0,"percent":"2.35"},{"name":"КЎМИР ( SSSSh - 13 )","summa":"348.39","difference":"-6.94","tovarNum":0,"percent":"-1.99"},{"name":"ЦЕМЕНТ ССПЦ M 400 D 20","summa":"532.75","difference":"0.01","tovarNum":0,"percent":"0.00"},{"name":"ЦЕМЕНТ ПЦ M 400 D 20","summa":"595.83","difference":"-3.77","tovarNum":0,"percent":"-0.63"},{"name":"ЦЕМЕНТ ССПЦ M 400 D-0","summa":"691.01","difference":"0.52","tovarNum":0,"percent":"0.08"},{"name":"ШЕЛУХА","summa":"2 951.59","difference":"-21.71","tovarNum":0,"percent":"-0.74"},{"name":"ШИФЕР (донаси)","summa":"36 000.00","difference":"0.00","tovarNum":0,"percent":"0.00"},{"name":"ШРОТ","summa":"4 258.28","difference":"114.63","tovarNum":0,"percent":"2.69"}]
    //
    // var birjaStr = ''
    // for (const item of birjaData) {
    //     birjaStr += `${item.name} <span class="">${item.summa}</span> `
    // }
</script>
