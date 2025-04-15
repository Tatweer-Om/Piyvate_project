<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Occupational Therapy Assessment and Treatment Plan</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      background-color: #f9f9f9;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 0;
    }
    .page {
      width: 850px;
      background: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
      padding: 20px;
      border-radius: 8px;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }
    .header img {
      height: 50px;
    }
    .header-info {
      font-size: 12px;
    }
    .section-header {
      text-align: center;
      font-weight: bold;
      border: 2px solid #000;
      padding: 5px;
      margin-top: 10px;
      margin-bottom: 10px;
    }
    .box-section {
      border: 2px solid #000;
      padding: 10px;
      margin-top: 10px;
    }
    .pain-tool-box {
      border: 2px solid green;
      padding: 5px;
      width: 200px;
      font-size: 11px;
    }
    .input-dotted {
      border: none;
      border-bottom: 1px dotted #000;
      background: transparent;
      outline: none;
      font-size: 12px;
    }
    .input-short { width: 40px; }
    .input-medium { width: 120px; }
    .input-long { width: 300px; }
    .checkbox-group { display: inline-block; margin-right: 10px; }
  </style>
</head>
<body>
  <div class="page">
    <div class="header">
      <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo">
      <div class="header-info">
        HN:<input class="input-dotted input-medium"> PT no:<input class="input-dotted input-medium"><br>
        Name:<input class="input-dotted input-long"> Age:<input class="input-dotted input-short">
        Gender: <input type="checkbox">M <input type="checkbox">F<br>
        Birth Date:<input class="input-dotted input-medium"> Therapist:<input class="input-dotted input-medium">
      </div>
    </div>

    <div class="section-header">Occupational Therapy Assessment and treatment plan for Physical dysfunction</div>

    <div class="box-section">
      Chief complaint <input class="input-dotted input-long"><br>
      History of birth and illness <input class="input-dotted input-long"><br>
      Underlying <input class="input-dotted input-medium"> Operation <input class="input-dotted input-medium"><br>
      Laboratory Radiology result <input class="input-dotted input-long">
    </div>

    <div class="box-section">
      <strong>Physical examination</strong><br>
      Muscle tone <input class="input-dotted input-medium"> Muscle strength <input class="input-dotted input-medium"><br>
      ROM <input class="input-dotted input-long"><br>
      Sensory (light touch / pressure / proprioceptive / tactile localization) <input class="input-dotted input-long"><br>
      Coordination <input class="input-dotted input-medium"> Endurance <input class="input-dotted input-medium"><br>
      ADL Independence <input class="input-dotted input-medium"> Assist <input class="input-dotted input-medium"> Dependence <input class="input-dotted input-medium"><br>
      Hand function and prehension <input class="input-dotted input-long"><br>
      Dominant Hand <input type="checkbox">Right <input type="checkbox">Left &nbsp;&nbsp;
      Affected hand <input type="checkbox">Right <input type="checkbox">Left<br>
      Swallowing function <input type="checkbox">normal <input type="checkbox">risk for aspiration &nbsp;&nbsp;
      Current status <input class="input-dotted input-medium"><br>
      Neck control <input type="checkbox">Good <input type="checkbox">Fair <input type="checkbox">Poor<br>
      Oral phase <input class="input-dotted input-medium"> Pharyngeal phase <input class="input-dotted input-medium"><br>
      Comments <input class="input-dotted input-long">
    </div>

    <div class="box-section">
      <strong>Perception and cognitive function</strong><br>
      Perception <input type="checkbox">intact <input type="checkbox">impaired
      Attention <input type="checkbox">intact <input type="checkbox">impaired<br>
      Memory <input type="checkbox">intact <input type="checkbox">impaired
      Orientation <input type="checkbox">intact <input type="checkbox">impaired<br>
      Executive function <input type="checkbox">intact <input type="checkbox">impaired<br>
      Splint requirement <input class="input-dotted input-long"><br>
      Fall risk assessment <input type="checkbox">Low <input type="checkbox">High
    </div>

    <div class="box-section">
      <div style="display: flex; justify-content: space-between;">
        <div style="flex: 1; margin-right: 10px;">
          <strong>Pain assessment</strong><br>
          Does patient have pain? <input type="checkbox">No <input type="checkbox">Yes<br>
          Score <input class="input-dotted input-short"> pain location <input class="input-dotted input-medium">
          duration <input class="input-dotted input-medium"> characteristic <input class="input-dotted input-medium">
          Frequency <input class="input-dotted input-medium"><br>
          OT diagnosis is <input class="input-dotted input-long"><br>
          OT program <input class="input-dotted input-long"><br>
          Goal of treatment<br>
          Short term goal <input class="input-dotted input-long"><br>
          Long term goal <input class="input-dotted input-long"><br>
          Patient and family education <input class="input-dotted input-long"><br>
          Occupational Therapist's name <input class="input-dotted input-medium"><br>
          Date <input class="input-dotted input-short"> Time <input class="input-dotted input-short">
        </div>
        <div class="pain-tool-box">
          <strong>Pain assessment tool</strong><br>
          <input type="checkbox"> &lt;1 year (NIPS)<br>
          <input type="checkbox"> 1-3 years (FLACC)<br>
          <input type="checkbox"> &gt;3-8 years (FLACC)<br>
          <input type="checkbox"> &gt;8 years (NRS)<br>
          <input type="checkbox"> BPS (impaired cognition / elder)
        </div>
      </div>
    </div>
  </div>
</body>
</html>
