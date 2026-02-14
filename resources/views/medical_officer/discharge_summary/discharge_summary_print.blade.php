<!DOCTYPE html>
<html lang="gu">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Discharge Summary</title>
      <meta name="author" content="RECEPTION"/>
      <style type="text/css">
         @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati:wght@100..900&display=swap');
         * {margin:0; padding:0; text-indent:0; }
         h2 { color: black; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 12pt; }
         .s2 { color: black; font-family: 'Noto Sans Gujarati',"Nirmala UI", sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 9pt; }
         .s3 { color: black; font-family: 'Noto Sans Gujarati',"Nirmala UI", sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 11pt; }
         h3 { color: black; font-family: 'Noto Sans Gujarati',"Nirmala UI", sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 11pt; }
         .s4 { color: black; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: underline; font-size: 11pt; }
         .s6 { color: black; font-family: 'Noto Sans Gujarati',"Nirmala UI", sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: underline; font-size: 12pt; }
         .s7 { color: black; font-family: 'Noto Sans Gujarati',"Nirmala UI", sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 10pt; }
         h4 { color: black; font-family: 'Noto Sans Gujarati',"Nirmala UI", sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 10pt; }
         .s8 { color: black; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 12pt; }
         p { color: black; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 10pt; margin:0pt; }
         .s10 { color: black; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: underline; font-size: 10pt; }
         .s11 { color: black; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 9pt; }
         .s12 { color: black; font-family: 'Noto Sans Gujarati',"Agency FB", sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 10pt; }
         .s13 { color: black; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 8pt; }
         h1 { color: black; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal; font-weight: bold; font-size: 13px !important; text-decoration: none; font-size: 13pt; }
         table, tbody {vertical-align: top; overflow: visible; }
      </style>
   </head>
   <body style="padding: 10px 20px 10px 20px; font-size: 10pt;">
      <span>
         <div style="display: flex; justify-content: center; align-items: center; height: 100vh; margin-left:35%; margin-bottom:15px; margin-top:20px; padding:10px 10px 20px 10px;">
            <img width="182" height="101" src="data:image/jpg;base64,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"/>
         </div>
      </span>
      <h2 style="text-indent: 0pt;text-align: center; line-height: 1.5;">Dr. Maulik V. Kaneria&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dwarkadhish Market 1ST Floor</h2>
      <h2 style="text-indent: 0pt;text-align: center; line-height: 1.5;">Consulting physician MD Medicine&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Above green cross lab</h2>
      <h2 style="text-indent: 0pt;text-align: center; line-height: 1.5;">Ex. Physician Civil Hospital Jamnagar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sardarbag Junagadh 362001
         </br>
         <u style="position:relative; margin-left:15px;">Ex. Physician Civil Hospital Junagadh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Appointment No - 9913340805 – 0285 2634951/2635951</u>
         <u>Emergency No – 9904240805/9512040805/9512440805</u>
      </h2>
      <div class="textbox" style="border:0.5pt solid #000000;display:block;min-height:23.6pt;width:524.6pt; position:relative; left:30px;">
         <p class="s8" style="text-indent: 0pt;text-align: center; line-height: 1.5;">DISHARGE SUMMARY</p>
      </div>
      <div style="padding:10px 25px 10px 20px">
         <h2 style="padding-top: 13pt;padding-left: 8pt;text-indent: 0pt;text-align: left;">Patient name:   {{$patient->fullname}}</h2>
         <h2 style="padding-left: 8pt;text-indent: 0pt;text-align: left;">Age:  {{$patient->age}} Y    Sex: {{$patient->gender}}           Address: 
            <u>{{$patient->address}}</u> Indoor No:     22154
         </h2>
         <h2 style="padding-left: 8pt;text-indent: 0pt;line-height: 15pt;text-align: left;">Diagnosis:     {{$diagnosisDetails}}</h2>
         <h2 style="padding-left: 8pt;text-indent: 0pt;text-align: left;">Date of Admit:   {{$patient->admit_datetime}} Date of Discharge: {{$todayDateTime}}</h2>
         <p style="padding-top: 1pt;text-indent: 0pt;text-align: left;">
            <br/>
         </p>
         <p class="s2" style="padding-left: 8pt;text-indent: 0pt;text-align: left;">ડિસ્ચાર્જ સમરી એ ધટનાઓ અને સારવાર નું સંક્ષિપ્તમાં વર્ણન છે.</p>
         <p class="s2" style="padding-top: 4pt;padding-left: 8pt;text-indent: 0pt;line-height: 138%;text-align: left;">ડિસ્ચાર્જ સમરીમાં ટાઈપિંગ ભૂલ ( Typographical Error ) અથવા માનવગત ભૂલ ( Human Error ) હોય શકે છે. કોઈપણ ગેરસમજ માટે તમો હોસ્પિટલ ના સ્ટાફ તથા ડોક્ટર નો સંપર્ક કરી શકો છો.</p>
         <p class="s2" style="padding-left: 8pt;text-indent: 0pt;text-align: left;">અમોને દવાઓ અને રજા આપ્યા બાદ રાખવી પડતી ટકેદારીઓ વિષે અમારી ભાષામાં સંપૂર્ણ માહિતી આપવામાં આવેલ છે.
            <span class="s3">.</span>
         </p>
         <p style="text-indent: 0pt;text-align: left;">
            <br/>
         </p>
         <h3 style="padding-left: 8pt;text-indent: 0pt;line-height: 138%;text-align: left;">In cash of any Emergency please contact casualty medical officer at KANERIA HOSPITALAND ICU Contact 
            <span class="s4">– 9904240805/9512040805/9512440805</span>
         </h3>
         <h3 style="padding-left: 8pt;text-indent: 0pt;line-height: 133%;text-align: left;">
            <u>પરત બતાવા માટે </u>
            <span class="s4">9913340805 – 0285 2634951/2635951 </span>
            <u>આ ફોન નંબર પર સંપર્ક કરી અપોઇન્ટમેન્ટ લઈ પછીજ</u>
            <u>બતાવવા આવવું </u>
            <span class="s6">  પૂછપરછ માટે નંબર - 8849350633</span>
         </h3>
         <h3 style="padding-left: 8pt;text-indent: 0pt;text-align: left;">
            <u>(** ઇમર્જન્સિ સંજોગો માં અપોઇન્ટમેન્ટ</u> કેન્સલ/પોસ્પોન - મુલતવી પણ કરવામાં આવી શકે)
         </h3>
         <p style="text-indent: 0pt;text-align: left;">
            <br/>
         </p>
         <table style="border-collapse:collapse;margin-left:5.5pt" cellspacing="0">
            <tr style="height:15pt">
               <td style="width:177pt">
                  <p class="s7" style="padding-left: 2pt;text-indent: 0pt;line-height: 10pt;text-align: left;">ALL REPORTS GIVEN TO</p>
               </td>
               <td style="width:182pt">
                  <p class="s7" style="padding-left: 41pt;text-indent: 0pt;line-height: 10pt;text-align: left;">CONSULATANT INCHARGE</p>
               </td>
               <td style="width:125pt">
                  <p class="s7" style="padding-left: 19pt;text-indent: 0pt;line-height: 10pt;text-align: center;">Medical Officer</p>
               </td>
            </tr>
            <tr style="height:19pt">
               <td style="width:177pt">
                  <p class="s7" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">PATIENT/RELATIVE AT FINAL</p>
               </td>
               <td style="width:182pt">
                  <p style="text-indent: 0pt;text-align: left;">
                     <br/>
                  </p>
               </td>
               <td style="width:125pt">
                  <p style="text-indent: 0pt;text-align: left;">
                     <br/>
                  </p>
               </td>
            </tr>
            <tr style="height:19pt">
               <td style="width:177pt">
                  <p class="s7" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">DISHCHARGE---</p>
               </td>
               <td style="width:182pt">
                  <p class="s7" style="padding-top: 1pt;padding-left: 52pt;text-indent: 0pt;text-align: left;">Dr. Maulik Kaneria</p>
               </td>
               <td style="width:125pt">
                  <p class="s7" style="padding-top: 1pt;padding-left: 16pt;text-indent: 0pt;text-align: center;">Dr. SAMIR CHHUCHAR</p>
               </td>
            </tr>
            <tr style="height:19pt">
               <td style="width:177pt">
                  <p class="s7" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">NAME OF RELATIVE:</p>
               </td>
               <td style="width:182pt">
                  <p style="text-indent: 0pt;text-align: left;">
                     <br/>
                  </p>
               </td>
               <td style="width:125pt">
                  <p style="text-indent: 0pt;text-align: left;">
                     <br/>
                  </p>
               </td>
            </tr>
            <tr style="height:15pt">
               <td style="width:177pt">
                  <p class="s7" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;line-height: 12pt;text-align: left;"> {{$patient->relative_name}}</p>
               </td>
               <td style="width:182pt">
                  <p class="s7" style="padding-top: 1pt;padding-left: 54pt;text-indent: 0pt;line-height: 12pt;text-align: left;">(MD Physician)</p>
               </td>
               <td style="width:125pt">
                  <p style="text-indent: 0pt;text-align: left;">
                     <br/>
                  </p>
               </td>
            </tr>
         </table>
         <h4 style="padding-top: 5pt;text-indent: 0pt;text-align: center;">G-47959</h4>
         <h4 style="padding-top: 5pt;padding-left: 8pt;text-indent: 0pt;line-height: 138%;text-align: left;">SING OF RELATIVE: 
            </br>MO NO -  {{$patient->relative_num}} 
         </h4>
         <p style="text-indent: 0pt;text-align: left;">
      </div>
      <span>
         <div style="display: flex; justify-content: center; align-items: center; height: 100vh; margin-left:35%; margin-top:20px; padding:10px 10px 5px 10px;">
            <img width="182" height="101" src="data:image/jpg;base64,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"/>
         </div>
      </span>
      <h2 style="text-indent: 0pt;text-align: center; line-height: 1.5;">Dr. Maulik V. Kaneria&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dwarkadhish Market 1ST Floor</h2>
      <h2 style="text-indent: 0pt;text-align: center; line-height: 1.5;">Consulting physician MD Medicine&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Above green cross lab</h2>
      <h2 style="text-indent: 0pt;text-align: center; line-height: 1.5;">Ex. Physician Civil Hospital Jamnagar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sardarbag Junagadh 362001
         </br>
         <u style="position:relative; margin-left:15px;">Ex. Physician Civil Hospital Junagadh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Appointment No - 9913340805 – 0285 2634951/2635951</u>
         <u>Emergency No – 9904240805/9512040805/9512440805</u>
      </h2>
      <div class="textbox" style="border:0.5pt solid #000000;display:block;min-height:auto;width:524.6pt; position:relative; left:30px;">
         <p class="s8" style="text-indent: 0pt;text-align: center; line-height: 1.5;">DISHARGE SUMMARY</p>
      </div>
      <div style="padding:0 25px 10px 20px">
         <p style="padding-left: 7pt;text-indent: 0pt;text-align: left;"/>
         <p style="padding-left: 8pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Patient name:  {{$patient->fullname}}</p>
         <p style="padding-left: 8pt;text-indent: 0pt;text-align: left;">Age:  {{$patient->age}} Y    Sex: {{$patient->gender}}          Address: 
            <u>{{$patient->address}}</u> Indoor No:     22154
         </p>
         <p style="padding-left: 8pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Diagnosis:    {{$diagnosisDetails}}</p>
         <p style="padding-left: 8pt;text-indent: 0pt;text-align: justify;">Date of Admit:  {{$patient->admit_datetime}} Date of Discharge: {{$todayDateTime}} </br> Complains &amp; investigation:</p>
         <p style="padding-left: 8pt;text-indent: 0pt;text-align: left;">{{$patient->complaints}}</p>
         <p style="padding-top: 2pt;text-indent: 0pt;text-align: left;">
            <br/>
         </p>
         <table style="width: 100%; border-collapse: collapse; font-size: 12px; font-weight: bold; padding:0 0 0 10px; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal;">
            <tr>
                <td colspan="2" style="text-align: center; padding: 8px; background-color: #f2f2f2;">Indoor Vitals</td>
                <td style="width: 1px; background-color: #000;"></td>
                <td colspan="2" style="text-align: center; padding: 8px; background-color: #f2f2f2; padding-left: 10px;">Discharge Vitals</td>
            </tr>
            <tr>
                <td style="padding: 3px; text-align: left;">B.P (HHHG):</td>
                <td style="padding: 3px; text-align: left;">{{ $firstOpd->bp ?? 'N/A' }}</td>
                <td style="width: 1px; background-color: #000;"></td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">B.P (HHHG):</td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">{{ $lastOpd->bp ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; text-align: left;">Pulse Rate (BPM):</td>
                <td style="padding: 3px; text-align: left;">{{ $firstOpd->pulse ?? 'N/A' }}</td>
                <td style="width: 1px; background-color: #000;"></td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">Pulse Rate (BPM):</td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">{{ $lastOpd->pulse ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; text-align: left;">SPO2 (%):</td>
                <td style="padding: 3px; text-align: left;">{{ $firstOpd->spo2 ?? 'N/A' }}</td>
                <td style="width: 1px; background-color: #000;"></td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">SPO2 (%):</td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">{{ $lastOpd->spo2 ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; text-align: left;">Temperature (°F):</td>
                <td style="padding: 3px; text-align: left;">{{ $firstOpd->temp ?? 'N/A' }}°F</td>
                <td style="width: 1px; background-color: #000;"></td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">Temperature (°F):</td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">{{ $lastOpd->temp ?? 'N/A' }}°F</td>
            </tr>
            <tr>
                <td style="padding: 3px; text-align: left;">Respiration (BPM):</td>
                <td style="padding: 3px; text-align: left;">{{ $firstOpd->rs ?? 'N/A' }}</td>
                <td style="width: 1px; background-color: #000;"></td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">Respiration (BPM):</td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">{{ $lastOpd->rs ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; text-align: left;">CVS:</td>
                <td style="padding: 3px; text-align: left;">{{ $firstOpd->cvs ?? 'N/A' }}</td>
                <td style="width: 1px; background-color: #000;"></td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">CVS:</td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">{{ $lastOpd->cvs ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; text-align: left;">RS:</td>
                <td style="padding: 3px; text-align: left;">{{ $firstOpd->rs ?? 'N/A' }}</td>
                <td style="width: 1px; background-color: #000;"></td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">RS:</td>
                <td style="padding: 3px 3px 3px 10px; text-align: left;">{{ $lastOpd->rs ?? 'N/A' }}</td>
            </tr>
        </table>
        
         <div style="text-align: right; font-family: 'Noto Sans Gujarati',Calibri, sans-serif; font-style: normal; padding-top:5px;">
            <strong>Follow-up Details and General Condition on Discharge:</strong>
            <br>
            After 5 days CBC, CRP, SGPT, X-RAY 
            <br>
            NOW PATIENT BEING DISCHARGED VITALLY STABLE 
            <br>
            CONDITION AND FEELING BETTER
         </div>
         <hr>
         @if(count($indoorMedicine) > 0 || count($dischargeMedicine) > 0)
         <table style="width: 100%; border-collapse: collapse; font-size: 12px; font-weight: bold; padding: 0 0 10% 10px; font-family: 'Noto Sans Gujarati', Calibri, sans-serif;">
             <tr>
                 @if(count($indoorMedicine) > 0)
                     <td colspan="{{ count($indoorMedicine) > 10 ? '3' : '1' }}" style="text-align: center; padding: 8px; background-color: #f2f2f2; width: 50%;">
                         Indoor Treatment Given at Hospital
                     </td>
                 @endif
                 @if(count($indoorMedicine) > 0 && count($dischargeMedicine) > 0)
                     <td style="width: 1px; background-color: #000;"></td>
                 @endif
                 @if(count($dischargeMedicine) > 0)
                     <td colspan="3" style="text-align: center; padding: 8px; background-color: #f2f2f2; width: 50%; padding-left: 10px;">
                         Discharge Medicines
                     </td>
                 @endif
             </tr>
         
             @php
                 $maxRows = max(
                     count($indoorMedicine) > 10 ? ceil(count($indoorMedicine) / 3) : count($indoorMedicine),
                     ceil(count($dischargeMedicine) / 3)
                 );
             @endphp
         
             @for ($i = 0; $i < $maxRows; $i++)
             <tr>
                 @if(count($indoorMedicine) > 0)
                     <td style="padding: 1px; text-align: left; width: auto;">
                         {{ $indoorMedicine[$i]->name ?? '' }}
                     </td>
                     @if(count($indoorMedicine) > 10)
                         <td style="padding: 1px; text-align: left; width: auto;">
                             {{ $indoorMedicine[$i + $maxRows]->name ?? '' }}
                         </td>
                         <td style="padding: 1px; text-align: left; width: auto;">
                             {{ $indoorMedicine[$i + 2 * $maxRows]->name ?? '' }}
                         </td>
                     @endif
                 @endif
                 @if(count($indoorMedicine) > 0 && count($dischargeMedicine) > 0)
                     <td style="width: 1px; background-color: #000;"></td>
                 @endif
                 @if(count($dischargeMedicine) > 0)
                     <td style="padding: 1px 1px 1px 10px; text-align: left; width: auto;">
                         {{ $dischargeMedicine[$i] ?? '' }}
                     </td>
                     <td style="padding: 1px 1px 1px 10px; text-align: left; width: auto;">
                         {{ $dischargeMedicine[$i + $maxRows] ?? '' }}
                     </td>
                     <td style="padding: 1px 1px 1px 10px; text-align: left; width: auto;">
                         {{ $dischargeMedicine[$i + 2 * $maxRows] ?? '' }}
                     </td>
                 @endif
             </tr>
             @endfor
         </table>
         @endif
         
         <p style="text-indent: 0pt;text-align: left;">
      </div>
      <span>
         <div style="display: flex; justify-content: center; align-items: center; height: 100vh; margin-left:35%; margin-bottom:15px; margin-top:20px; padding:10px 10px 20px 10px;">
            <img width="182" height="101" src="data:image/jpg;base64,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"/>
         </div>
      </span>
      <div style="padding:10px 25px 10px 20px">
         <h1 style="padding-left: 8pt;text-indent: 0pt;line-height: 16pt;text-align: left;">Patient name:   {{$patient->fullname}}</h1>
         <h1 style="padding-left: 8pt;text-indent: 0pt;text-align: left;">Age:  {{$patient->age}} Y    Sex: {{$patient->gender}}           Address: 
            <u>{{$patient->address}}</u> Indoor No:     22154
         </h1>
         <h1 style="padding-left: 8pt;text-indent: 0pt;text-align: left;">Date of Admit:   {{$patient->admit_datetime}} Date of Discharge: {{$todayDateTime}}</h1>
         <p style="text-indent: 0pt;text-align: left;">
            <br/>
         </p>
         <h1 style="padding-left: 8pt;text-indent: 0pt;line-height: 114%;text-align: justify;">This is to inform that we are not provided indoor case papers records and tariff chart any one unless and until orders by court. However, we have already given you relevant history and recodes regarding patient care during his hospital stay in our discharge/death summary. Moreover an authorized person can come and inspect treatment papers in our p-remises with prior permission of authority.</h1>
         <p style="text-indent: 0pt;text-align: left;">
            <br/>
         </p>
         <h1 style="padding-left: 8pt;text-indent: 0pt;text-align: justify;">MRD INCHARGE</h1>
         <p style="text-indent: 0pt;text-align: left;">
            <br/>
         </p>
         <h2 style="padding-left: 8pt;text-indent: 0pt;text-align: left;">KANERIA HOSPITAL AND ICU 9979586689/9512040508</h2>
      </div>
   </body>
</html>