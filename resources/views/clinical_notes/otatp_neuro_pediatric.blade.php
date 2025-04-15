<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Physical Therapy Assessment and Treatment Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .page {
            width: 850px;
            margin: auto;
            background: #fff;
            border: 2px solid #000;
            /* box-shadow: 0 0 15px rgba(0, 0, 0, 0.3); */
            padding: 15px;
            box-sizing: border-box;
        }

        .section-header {
            text-align: center;
            font-weight: bold;
            border: 2px solid #000;
            padding: 5px;
            margin-bottom: 10px;
        }

        .input-line {
            border: none;
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 80px;
            padding: 2px 4px;
            margin: 0 2px;
            background: transparent;
            font-size: 12px;
        }

        .checkbox-group {
            display: inline-block;
            margin-right: 10px;
        }

        .flex-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .page_whole {
            width: 850px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 15px;
            background: #fff;
        }

        .body-images {
            text-align: center;
            width: 90px;
            margin-left: 20px;
        }

        .stream-section {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .stream-table {
            flex: 1;
            margin-right: 10px;
        }

        .stream-image-box {
            width: 100px;
            height: 100px;
            border: 2px solid #000;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stream-image-box p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td,
        th {
            border: 2px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        input[type="checkbox"] {
            transform: scale(1.1);
        }

        .stream-table {
            border-collapse: collapse;
            width: 100%;
            padding-left: 5px;
        }

        .stream-table th,
        .stream-table td {
            border: 1px solid #070707;
            padding: 2px;
            text-align: center;
        }

        .stream-table th {
            background-color: #ffffff;
        }

        .stream-table input[type="text"],
        .stream-table input[type="number"] {
            width: 100%;
            border: none;
            text-align: center;
            padding: 6px;
            box-sizing: border-box;
            font-size: 1em;
            background: transparent;
        }

        .stream-table input:focus {
            outline: none;
            background-color: #ffffff;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #070707;
            padding: 10px;
            vertical-align: middle;
            text-align: left;
        }

        th {
            background-color: #f9f9f9;
            text-align: center;
        }

        input[type="checkbox"] {
            transform: scale(1.2);
        }

        .total-input {
            background-color: #e0e0e0;
            border: 1px solid #e0e0e0;
            padding: 6px;
            width: 100px;
            font-size: .8rem;
        }

        .totl {
            background-color: #e0e0e0 !important;

        }

        .gmfm-table {
            border-collapse: collapse;
            width: 100%;
            padding-left: 5px;
        }

        .gmfm-table th,
        .gmfm-table td {
            border: 1px solid #070707;
            padding: 2px;
            text-align: center;
        }

        .gmfm-table input[type="number"] {
            width: 100%;
            border: none;
            text-align: center;
            padding: 6px;
            box-sizing: border-box;
            font-size: 1em;
            background: transparent;
        }

        .gmfm-table th[colspan="7"] {
            background-color: #ffffff;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <div class="page_whole">

        <form action="
        ">
        <div class="page">

            <div class="header" style="display: flex; align-items: flex-start; padding: 10px;">
              <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Center Logo" height="50" style="margin-right: 20px;">
              <div style="text-align: justify;">
                <div>
                  HN:<input type="text" class="input-line" /> PT:<input type="text" class="input-line" />
                </div>
                <div>
                  Name:<input type="text" class="input-line" /> Age:<input type="text" class="input-line" />
                </div>
                <div>
                  Gender:<label><input type="checkbox"> M</label><label><input type="checkbox"> F</label>
                </div>
                <div>
                  Birth Date:<input type="text" class="input-line" /> Therapist:<input type="text" class="input-line" />
                </div>
              </div>
            </div>

            <div class="section-header">
                Physical Therapy Assessment and Treatment Plan for <u>Neuro</u> and Pediatric
            </div>

            <p>BP (<input type="text" class="input-line" />) PR (bpm) <input type="text" class="input-line" /> RR (rpm)
                <input type="text" class="input-line" /> T (°C) <input type="text" class="input-line" /> O<sub>2</sub>sat (%)
                <input type="text" class="input-line" /> BW (Kg) <input type="text" class="input-line" />
              </p>

              <p>Chief complaint <input type="text" class="input-line" style="width: 600px;" /></p>
              <p>History of illness <input type="text" class="input-line" style="width: 600px;" /></p>
              <p>Underlying <input type="text" class="input-line" style="width: 150px;" /> Precaution <input type="text"
                  class="input-line" style="width: 300px;" /></p>
              <p>Operation <input type="text" class="input-line" style="width: 300px;" /></p>
              <p>Laboratory Radiology result <input type="text" class="input-line" style="width: 600px;" /></p>

            <div class="flex-row">
                <div style="flex: 1;">
                    <p>Level of consciousness:
                        <span class="checkbox-group"><input type="checkbox"> Alert</span>
                        <span class="checkbox-group"><input type="checkbox"> Drowsy</span>
                        <span class="checkbox-group"><input type="checkbox"> Stupor</span>
                        <span class="checkbox-group"><input type="checkbox"> Semi coma</span>
                    </p>
                    <p>
                        <span class="checkbox-group"><input type="checkbox"> Coma</span>
                        Interpretered:
                        <span class="checkbox-group"><input type="checkbox"> No</span>
                        <span class="checkbox-group"><input type="checkbox"> Yes</span>
                    </p>
                    <p>Observation <input type="text" class="input-line" style="width: 500px;" /></p>
                    <p>Muscle tone <input type="text" class="input-line" style="width: 180px;" /> Muscle strength
                      <input type="text" class="input-line" style="width: 180px;" />
                    </p>
                    <p>Sensation <input type="text" class="input-line" style="width: 120px;" /> ➝ ASIA
                      <input type="text" class="input-line" style="width: 300px;" />
                    </p>
                    <p>Bed mobility <span class="checkbox-group"><input type="checkbox"> Independent</span>
                      <span class="checkbox-group"><input type="checkbox"> Dependent with <input type="text" class="input-line"
                          style="width: 100px;" /></span>
                    </p>
                    <p>Transfer <span class="checkbox-group"><input type="checkbox"> Independent</span>
                      <span class="checkbox-group"><input type="checkbox"> Dependent with <input type="text" class="input-line"
                          style="width: 100px;" /></span>
                    </p>
                </div>
                <div>
                    <img src="{{ asset('images/logo/model.png') }}" alt="Body Diagram"
                        style="width: 200px; height: 300px;"><br>
                </div>
            </div>

            <div class="stream-section">


                <table class="stream-table">
                    <tr>
                        <th>STREAM Score</th>
                        <th>Upper extremity</th>
                        <th>Lower extremity</th>
                        <th>Basic Mobility</th>
                        <th class="totl">Total</th>
                    </tr>
                    <tr>
                        <td>Score</td>
                        <td><input type="number" name="score_upper" /></td>
                        <td><input type="number" name="score_lower" /></td>
                        <td><input type="number" name="score_mobility" /></td>
                        <td class="totl"><input type="number" name="score_total" /></td>
                    </tr>
                    <tr>
                        <td>%</td>
                        <td><input type="number" name="percent_upper" step="0.01" /></td>
                        <td><input type="number" name="percent_lower" step="0.01" /></td>
                        <td><input type="number" name="percent_mobility" step="0.01" /></td>
                        <td class="totl"><input type="number" name="percent_total" step="0.01" /></td>
                    </tr>
                </table>


                <div class="stream-image-box" style="position: relative;">
                    <!-- Horizontal line (X-axis) -->
                    <div
                        style="position: absolute; top: 50%; left: 0; width: 100%; height: 1px; background-color: #000;">
                    </div>

                    <!-- Vertical line (Y-axis) -->
                    <div
                        style="position: absolute; top: 0; left: 50%; width: 1px; height: 100%; background-color: #000;">
                    </div>

                    <!-- Original text at bottom -->
                    <div style="display: flex; justify-content: space-between;">
                        <p>Lt.</p>
                        <p>Rt.</p>
                    </div>
                </div>
            </div>


            <table>
                <tr>
                    <th rowspan="5">Berg Balance Scale</th>
                    <td><label><input type="checkbox" name="sit_to_standing" /> Sit to standing</label></td>
                    <td><label><input type="checkbox" name="eyes_closed" /> Stand with eyes closed score</label></td>
                    <td><label><input type="checkbox" name="turning_360" /> Turning 360 degree</label></td>
                </tr>
                <tr>
                    <td><label><input type="checkbox" name="standing_unsupported" /> Standing unsupported</label></td>
                    <td><label><input type="checkbox" name="feet_together" /> Standing with feet together
                            score</label></td>
                    <td><label><input type="checkbox" name="foot_on_stool" /> Placing alternate foot on stool</label>
                    </td>
                </tr>
                <tr>
                    <td><label><input type="checkbox" name="sitting_unsupported" /> Sitting unsupported</label></td>
                    <td><label><input type="checkbox" name="reaching_forward" /> Reaching forward with outstretched
                            arm</label></td>
                    <td><label><input type="checkbox" name="foot_in_front" /> Standing with foot in front</label></td>
                </tr>
                <tr>
                    <td><label><input type="checkbox" name="standing_to_sitting" /> Standing to sitting</label></td>
                    <td><label><input type="checkbox" name="retrieve_object" /> Retrieving object from floor</label>
                    </td>
                    <td><label><input type="checkbox" name="one_foot" /> Standing on one foot</label></td>
                </tr>
                <tr>
                    <td><label><input type="checkbox" name="transfer" /> Transfer</label></td>
                    <td><label><input type="checkbox" name="look_behind" /> Turning to look behind</label></td>
                    <td class="totl">
                        <input type="number" class="total-input" name="total_score" placeholder="Total" />
                    </td>
                </tr>
            </table>

            <table class="gmfm-table">
                <tr>
                    <th colspan="7">GMFM (for Pediatric)</th>
                </tr>
                <tr>
                    <th>Dimension</th>
                    <td>A. Lying & Rolling</td>
                    <td>B. Sitting</td>
                    <td>C. Crawling & Kneeling</td>
                    <td>D. Standing</td>
                    <td>E. Walking, Running and Jumping</td>
                    <td class="totl">Total Score</td>
                </tr>
                <tr>
                    <td>Score</td>
                    <td><input type="number" name="lying_rolling" /></td>
                    <td><input type="number" name="sitting" /></td>
                    <td><input type="number" name="crawling_kneeling" /></td>
                    <td><input type="number" name="standing" /></td>
                    <td><input type="number" name="walking_running" /></td>
                    <td class="totl"><input type="number" name="total_score" /></td>
                </tr>
            </table>


            <p>
                Gait analysis
                <input type="text" class="input-line"  style="width: 600px;" />
              </p>

              <!-- Others -->
              <p>
                Others
                <input type="text" class="input-line"  style="width: 600px;" />
              </p>

            <p>ADL Feeding <span class="checkbox-group"><input type="checkbox"> Dependent</span>
                <span class="checkbox-group"><input type="checkbox"> Independent</span>
                Bathing/Toileting <span class="checkbox-group"><input type="checkbox"> Dependent</span>
                <span class="checkbox-group"><input type="checkbox"> Independent</span>
            </p>

            <p>Dressing <span class="checkbox-group"><input type="checkbox"> Dependent</span>
                <span class="checkbox-group"><input type="checkbox"> Independent</span>
                Sleeping <span class="checkbox-group"><input type="checkbox"> Dependent</span>
                <span class="checkbox-group"><input type="checkbox"> Independent</span>
                Carrying <span class="checkbox-group"><input type="checkbox"> Dependent</span>
                <span class="checkbox-group"><input type="checkbox"> Independent</span>
            </p>

            <p>Fall risk assessment:
                <span class="checkbox-group"><input type="checkbox"> Low risks</span>
                <span class="checkbox-group"><input type="checkbox"> High risks</span>
            </p>

            <p>Pain assessment: Does patient have pain? <span class="checkbox-group"><input type="checkbox"> No</span>
                <span class="checkbox-group"><input type="checkbox"> Yes</span>
            </p>
            <p>
                Location
                <input type="text" class="input-line" style="width: 500px;" />
              </p>
                          <p>Duration <span class="checkbox-group"><input type="checkbox"> Intermittent</span>
                <span class="checkbox-group"><input type="checkbox"> Constant</span>
                Characteristic of pain:
                <span class="checkbox-group"><input type="checkbox"> Prick</span>
                <span class="checkbox-group"><input type="checkbox"> Sharp</span>
                <span class="checkbox-group"><input type="checkbox"> Dull</span>
                <span class="checkbox-group"><input type="checkbox"> Burning</span>
                <span class="checkbox-group"><input type="checkbox"> Colic</span>
                <span class="checkbox-group"><input type="checkbox"> Others</span>
            </p>

            <p>Frequency:
                <span class="checkbox-group"><input type="checkbox"> Less than daily</span>
                <span class="checkbox-group"><input type="checkbox"> Daily</span>
                <span class="checkbox-group"><input type="checkbox"> All the time</span>
            </p>

            <p>
                Pain re-assessment score
                <input type="text" class="input-line" style="width: 300px;" />
              </p>

            <p>Assessment tool:
                <span class="checkbox-group"><input type="checkbox"> NIPS</span>
                <span class="checkbox-group"><input type="checkbox"> FLACC</span>
                <span class="checkbox-group"><input type="checkbox"> FACES</span>
                <span class="checkbox-group"><input type="checkbox"> NRS</span>
            </p>

            <p>
                PT diagnosis / Impression
                <input type="text" class="input-line" style="width: 500px;" />
              </p>
              <p>
                Goal of treatment: Long term goal
                <input type="text" class="input-line input-line-short" style="width: 500px;" />
              </p>

              <p class="indented">
                Short term goal
                <input type="text" class="input-line input-line-short" style="width: 500px;"/>
              </p>
        </div>
    </form>
    </div>
</body>

</html>
