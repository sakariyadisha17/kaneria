
<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>કાનેરિયા હોસ્પિટલ - દર્દી સર્વે ફોર્મ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .section-title {
            background: #007bff;
            color: white;
            padding: 8px;
            border-radius: 5px;
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4">🏥 કનેરિયા હોસ્પિટલ - દર્દી સર્વે ફોર્મ</h2>
        @include('notifications')
        <form action="{{ route('survey.store') }}" method="POST">
            @csrf            
            <!-- 📌 દર્દી ની માહિતી -->
            <div class="section-title">📌 દર્દી ની માહિતી</div>

                <div class="col-md-6">
                    <label class="form-label">📞 મોબાઇલ નંબર :</label>
                    <input type="text" class="form-control" name="mobile" required>
                </div><br>

            <!-- 📌 ડોક્ટર અને સારવાર વિષે -->
            <div class="section-title">📌 ડોક્ટર અને સારવાર </div>
            
                <div class="mb-3">
                    <label class="form-label">👨‍⚕️ ડોક્ટરની સેવા વિષે તમારું મંતવ્ય :</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="doctor_rating" value="ખૂબ સારું">
                        <label class="form-check-label">ખૂબ સારું</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="doctor_rating" value="સારું">
                        <label class="form-check-label">સારું</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="doctor_rating" value="સરેરાશ">
                        <label class="form-check-label">સરેરાશ</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="doctor_rating" value="ખરાબ">
                        <label class="form-check-label">ખરાબ</label>
                    </div>
                </div>

            
            <!-- 📌 હોસ્પિટલ સ્ટાફ અને સેવા -->
            <div class="section-title">📌 હોસ્પિટલ નર્સિંગ સ્ટાફ</div>

                <div class="mb-3">
                    <label class="form-label">👩‍⚕️ સ્ટાફના વ્યવહાર વિષે તમારું મંતવ્ય :</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="staff_rating" value="ખૂબ સારું">
                        <label class="form-check-label">ખૂબ સારું</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="staff_rating" value="સારું">
                        <label class="form-check-label">સારું</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="staff_rating" value="સરેરાશ">
                        <label class="form-check-label">સરેરાશ</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="staff_rating" value="ખરાબ">
                        <label class="form-check-label">ખરાબ</label>
                    </div>
                </div>
           
           
            <div class="section-title">📌 રિસેપ્શન અને બિલિંગ સ્ટાફ </div>
            
                <div class="mb-3">
                    <label class="form-label">👩‍⚕️ સ્ટાફના વ્યવહાર વિષે તમારું મંતવ્ય :</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recep_rating" value="ખૂબ સારું">
                        <label class="form-check-label">ખૂબ સારું</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recep_rating" value="સારું">
                        <label class="form-check-label">સારું</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recep_rating" value="સરેરાશ">
                        <label class="form-check-label">સરેરાશ</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recep_rating" value="ખરાબ">
                        <label class="form-check-label">ખરાબ</label>
                    </div>
                </div><br>


            <!-- 📌 દવાઓ અને લેબોરેટરી સેવા -->
            <div class="section-title"> 📌 મેડિકલ સ્ટોર અને લેબોરેટરી સેવા</div>

                <div class="mb-3">
                    <label class="form-label">💊 મેડિકલ સ્ટોર ના સ્ટાફ વિશે તમારુ મંતવ્ય :</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="medical_store_staff" value="ખૂબ સારો">
                        <label class="form-check-label">ખૂબ સારો</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="medical_store_staff" value="સારો">
                        <label class="form-check-label">સારો</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="medical_store_staff" value="સરેરાશ">
                        <label class="form-check-label">સરેરાશ</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="medical_store_staff" value="ખરાબ">
                        <label class="form-check-label">ખરાબ</label>
                    </div>
                </div><br>

                <div class="mb-3">
                    <label class="form-label">🔬 લેબોરેટરી પરીક્ષણ :</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lab_services" value="ફાસ્ટ">
                        <label class="form-check-label">ફાસ્ટ</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lab_services" value="સરેરાશ">
                        <label class="form-check-label">સરેરાશ</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lab_services" value="ધીમું">
                        <label class="form-check-label">ધીમું </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lab_services" value="રીપોર્ટ કરાવેલ નથી">
                        <label class="form-check-label">રીપોર્ટ કરાવેલ નથી </label>
                    </div>
                </div><br>
                 


            

            <!-- 📌 સંપૂર્ણ અનુભવ અને સૂચનાઓ -->
            <div class="section-title">📌 સંપૂર્ણ અનુભવ અને સૂચનાઓ</div>

            <div class="mb-3">
                <label class="form-label">⭐ સમગ્ર હોસ્પિટલ વિશે તમારું મંતવ્ય અને સૂચનાઓ :</label>
                <textarea class="form-control" name="suggestions" rows="4"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">📩 ફોર્મ સબમિટ કરો</button>
            </div>


        </div>

        </form>
    </div>
</div>

</body>
</html>
