<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="content-width=device-width, initial-scale=1.0">
    <title>Physical Therapy Assessment and Treatment Plan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .page { width: 850px; margin: auto; border: 1px solid #000; padding: 15px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .section-header { text-align: center; font-weight: bold; border: 1px solid #000; padding: 5px; margin-top: 10px; }
        .row { margin-bottom: 5px; }
        .input-line { border: none; border-bottom: 1px dotted #000; display: inline-block;  }
        .input-short { width: 50px; }
        .checkbox-group { display: inline-block; margin-right: 10px; }
        .signature-line { margin-top: 20px; }
        .anatomy-img { float: right; width: 120px; height: auto; }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50">
        <div>
            HN:<input type="text" class="input-line"> PT:<input type="text" class="input-line"><br>
            Name:<input type="text" class="input-line"> Age:<input type="text" class="input-short input-line">
            Gender:<label><input type="checkbox">M</label><label><input type="checkbox">F</label><br>
            Birth Date:<input type="date" class="input-line"> Therapist:<input type="text" class="input-line">
        </div>
    </div>

    <div class="section-header">PHYSICAL THERAPY ASSESSMENT AND TREATMENT PLAN FOR ORTHOPEDIC</div>

    <div class="row">
        BP(mmHg)<input type="text" class="input-line"> PR(bpm)<input type="text" class="input-line"> RR(rpm)<input type="text" class="input-line">
        T(°C)<input type="text" class="input-line"> O2sat(%)<input type="text" class="input-line"> BW(kg)<input type="text" class="input-line">
    </div>

    <div class="row">Chief complaint:<input type="text" class="input-line" style="width: 500px;"></div>
    <div class="row">History of illness:<input type="text" class="input-line" style="width: 800px;"></div>
    <div class="row"><input type="text" class="input-line" style="width: 800px;"></div>

    <div class="row"><input type="text" class="input-line" style="width: 800px;"></div>

    <div class="row"><input type="text" class="input-line" style="width: 800px;"></div>

    <div class="row"><input type="text" class="input-line" style="width: 800px;"></div>

    <div class="row"><input type="text" class="input-line" style="width: 800px;"></div>

    <div class="row">Precaution:<input type="text" class="input-line" style="width: 740px;"></div>
    <div class="row">Operation:<input type="text" class="input-line" style="width: 745px;"></div>
    <div class="row">Laboratory/Radiology Results:<input type="text" class="input-line" style="width: 640px;"></div>
    <div class="row">Observation:<input type="text" class="input-line" style="width: 732px;"></div>
    <div class="row">Palpation:<input type="text" class="input-line" style="width: 745px;"></div>

    <img src="{{ asset('images/logo/model3.png') }}" class="anatomy-img" style="height:200px; width:250px;">

    <div class="row">
        AROM:<input type="checkbox">Normal<input type="checkbox">Limit at<input type="text" class="input-line"><br>
        PROM:<input type="checkbox">Normal<input type="checkbox">Limit at<input type="text" class="input-line"><br>
        Sensory:<input type="checkbox">Intact<input type="checkbox">Impaired<input type="checkbox">Loss at<input type="text" class="input-line">
    </div>

    <div class="row">Others:<input type="text" class="input-line" style="width: 360px;"></div>
    <div class="row"><input type="text" class="input-line" style="width: 400px;"></div>
    <div class="row"><input type="text" class="input-line" style="width: 400px;"></div>
    <div class="row"><input type="text" class="input-line" style="width: 400px;"></div>


    <div class="row">
        Fall risk assessment Total Score=<input type="text" class="input-short input-line">
        <input type="checkbox">Low risks<input type="checkbox">High risks
    </div>

    <div class="row">Pain assessment Does patient have pain? <input type="checkbox">No<input type="checkbox">Yes Location<input type="text" class="input-line"></div>

    <div class="row">Duration:<input type="checkbox">Intermittent<input type="checkbox">Constant
        Characteristic of pain:<input type="checkbox">Prick<input type="checkbox">Sharp<input type="checkbox">Dull
        <input type="checkbox">Burning<input type="checkbox">Collic<input type="checkbox">Others<input type="text" class="input-line">
    </div>

    <div class="row">
        Frequency:<input type="checkbox">Less than daily<input type="checkbox">Daily<input type="checkbox">All the time
        Pain re-assessment score<input type="text" class="input-line">
    </div>

    <div class="row">
        Assessment tool:<input type="checkbox">NIPS<input type="checkbox">FLACC<input type="checkbox">FACES<input type="checkbox">NRS<input type="text" class="input-line">
    </div>

    <div class="row">PT diagnosis/Impression:<input type="text" class="input-line" style="width: 664px;"></div>

    <div class="row">Goal of treatment: Long term goal<input type="text" class="input-line" style="width: 620px;"></div>
    <div class="row">Short term goal<input type="text" class="input-line" style="width: 716px;"></div>
    <div class="row">Plan of treatment and procedure<input type="text" class="input-line" style="width: 630px;"></div>
    <div class="row"><input type="text" class="input-line" style="width: 800px;"></div>
    <div class="row"><input type="text" class="input-line" style="width: 800px;"></div>
    <div class="row"><input type="text" class="input-line" style="width: 800px;"></div>
    <div class="row"><input type="text" class="input-line" style="width: 800px;"></div>


    <div class="row">Instruction:<input type="text" class="input-line"></div>

    <div class="row"><input type="checkbox">Patient and/or family was given and understood about instruction and plan of treatment</div>
    <div class="row"><input type="checkbox">Need reviewed</div>

    <div class="signature-line">
        PT's signature:<input type="text" class="input-line"> Date:<input type="text" class="input-line"> Time:<input type="text" class="input-line">
    </div>
</div>
</body>
</html>
