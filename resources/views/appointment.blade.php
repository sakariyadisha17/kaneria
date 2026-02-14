
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Custom CSS-->
    <style>
        body {
            background: linear-gradient(135deg, #74b9ff, #81ecec);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .form-container {
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .form-container:hover {
            box-shadow: 0px 12px 20px rgba(0, 0, 0, 0.3);
        }
        .form-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2d3436;
            text-align: center;
            margin-bottom: 25px;
        }
        .form-label {
            color: #2d3436;
            font-weight: 600;
        }
        .btn-custom {
            border-radius: 5px;
            padding: 12px 20px;
            font-weight: bold;
            transition: background 0.3s ease, color 0.3s ease;
        }
        .btn-submit {
            background-color: #0984e3;
            color: #fff;
        }
        .btn-submit:hover {
            background-color: #74b9ff;
            color: #2d3436;
        }
        .btn-cancel {
            background-color: #d63031;
            color: #fff;
        }
        .btn-cancel:hover {
            background-color: #ff7675;
            color: #2d3436;
        }

       
        #patient-list {
            width: 19%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
        }
        .list-group-item {
            cursor: pointer;
        }




    </style>
</head>
<body>
    @include('notifications')
    
    <div class="form-container">
        <div class="form-title">Book Appointment</div>
        <form id="booking-form">
            @csrf
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="fullname" placeholder="Enter your full name" autocomplete="off" required>
                    <div id="patient-list" class="list-group" style="position:absolute; z-index:1000;"></div>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" maxlength="10" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="4" placeholder="Enter your address" required></textarea>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="date" class="form-label">Select Date</label>
                    <input type="date" id="select_date" name="date" class="form-control"  required>
                </div>
                <div class="col-md-6">
                    <label for="time" class="form-label">Select Time</label>
                    <select class="form-control" id="time" name="time_id" required>
                        <option value="">Select time</option>
                    </select>
                </div>
            </div>
            <div class="justify-content-between">
                <button type="button" class="btn btn-custom btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-custom btn-submit">Submit</button>
            </div>
        </form>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
        $('#name').on('keyup', function() {
            let query = $(this).val();
            if(query.length > 1) {
                $.ajax({
                    url: "{{ route('patients.search') }}",
                    type: 'GET',
                    data: { query: query },
                    success: function(data) {
                        console.log(data);
                        $('#patient-list').empty();
                        data.forEach(function(patient) {
                            $('#patient-list').append(`
                                <a href="#" class="list-group-item list-group-item-action patient-item" 
                                data-id="${patient.id}" 
                                data-phone="${patient.phone}" 
                                data-address="${patient.address}">
                                    ${patient.fullname}
                                </a>
                            `);
                        });
                    }
                });
            } else {
                $('#patient-list').empty();
            }
        });

        // Click on patient to auto-fill
        $(document).on('click', '.patient-item', function(e) {
            e.preventDefault();
            let name = $(this).text().trim();
            let phone = $(this).data('phone');
            let address = $(this).data('address');

            $('#name').val(name).focus();;
            $('#phone').val(phone);
            $('#address').val(address);

            $('#patient-list').empty();
        });
    });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("#select_date", {dateFormat: "Y-m-d"});
    </script>
    <script>
         $(document).ready(function () {
            $('#select_date').on('change', function () {
                const date = $(this).val();
                if (date) {
                    $.ajax({
                        url: "{{ url('/available-times') }}",
                        type: 'GET',
                        data: { date },
                        success: function (data) {
                            const timeSelect = $('#time');
                            timeSelect.empty(); // Clear the dropdown

                            // Populate the dropdown with available times
                            data.forEach(item => {
                                timeSelect.append(`<option value="${item.id}">${item.time}</option>`);
                            });
                        },
                        error: function () {
                            alert('Error fetching available times.');
                        }
                    });
                }
            });

            $('#booking-form').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission
                console.log('Form submit prevented successfully!');

                let formData = $(this).serialize();
                console.log('Form data:', formData);

                $.ajax({
                    url: "{{ url('/save_appointment') }}",
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        console.log('Server response:', response);

                        // Show success notification
                        Swal.fire({
                            title: 'Success!',
                            text: 'Booking saved successfully.',
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false,
                        });

                        // Reset the form
                        $('#booking-form')[0].reset();
                        console.log('Form reset successfully!');
                    },
                    error: function (xhr) {
                        console.error('Error response:', xhr.responseJSON);

                        // Show error notification
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to save booking. Please try again.',
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false,
                        });
                    }
                });
            });

         });
    </script>
   
</body>
</html>
