<?php $__env->startSection('title'); ?>
    Dashboard
<?php $__env->stopSection(); ?>
<style>
    
    .pay-card {
    padding: 18px;
    border-radius: 18px;
    color: #fff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transition: .3s;
}

.pay-card:hover {
    transform: translateY(-6px);
}

.pay-header span {
    font-size: 13px;
    opacity: .9;
}

.pay-header h3 {
    font-size: 22px;
    margin: 0;
}

#totalDonut,
#cashDonut,
#onlineDonut,
#debitDonut {
    margin-top: 10px;
}

/* Gradients */
.total { background: linear-gradient(135deg,#667eea,#764ba2); }
.cash { background: linear-gradient(135deg,#43cea2,#185a9d); }
.online { background: linear-gradient(135deg,#36d1dc,#5b86e5); }
.debit { background: linear-gradient(135deg,#ff9966,#ff5e62); }

.chart-btns {
    display: flex;
    align-items: center;
     gap: 14px; 
}

.chart-btns .btn {
    min-width: 120px;  /* equal width buttons */
    font-weight: 600;
    border-radius: 4px;
}

</style>
<?php $__env->startSection('content'); ?>
    <!-- BEGIN: Content-->
    <?php echo $__env->make('notifications', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="media align-items-stretch">
                                    <div class="p-2 text-center bg-primary bg-darken-2">
                                        <i class="feather icon-user font-large-2 white"></i>
                                    </div>
                                    <div class="p-2 bg-gradient-x-primary white media-body">
                                        <h5>Total Patient</h5>
                                        <h5 class="text-bold-400 mb-0"><i
                                                class="feather icon-plus"><?php echo e(@$total_patient); ?></i>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="media align-items-stretch">
                                    <div class="p-2 text-center bg-danger bg-darken-2">
                                        <i class="feather icon-box font-large-2 white"></i>
                                    </div>
                                    <div class="p-1 bg-gradient-x-danger white media-body">
                                        <h6>Today Appointment</h6>
                                        <h5 id="totalPatient" class="text-bold-400 mb-0"><i class="feather icon-plus">
                                                <?php echo e(@$today_total_appointments); ?>

                                            </i> </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="media align-items-stretch">
                                    <div class="p-2 text-center bg-success  bg-darken-2">
                                        <i class="feather icon-box font-large-2 white"></i>
                                    </div>
                                    <div class="p-1 bg-gradient-x-success  white media-body">
                                        <h6>New Patient</h6>
                                        <h5 id="newPatient" class="text-bold-400 mb-0"><i class="feather icon-plus">
                                                <?php echo e(@$today_new_patients); ?>

                                            </i> </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="media align-items-stretch">
                                    <div class="p-2 text-center bg-danger bg-darken-2">
                                        <i class="feather icon-box font-large-2 white"></i>
                                    </div>
                                    <div class="p-2 bg-gradient-x-danger white media-body">
                                        <h5>Old Patient</h5>
                                        <h5 id="oldPatient" class="text-bold-400 mb-0"><i class="feather icon-plus">
                                                <?php echo e(@$today_old_patients); ?>

                                            </i> </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="media align-items-stretch">
                                    <div class="p-2 text-center bg-info bg-darken-2">
                                        <i class="feather icon-heart font-large-2 white"></i>
                                    </div>
                                    <div class="p-2 bg-gradient-x-info white media-body">
                                        <h5>Today ECG</h5>
                                        
                                        <h5 id="ecgPatient" class="text-bold-400 mb-0"><i class="feather icon-plus">
                                                <?php echo e(@$today_total_ecg_patients); ?>

                                            </i> </h5>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="media align-items-stretch">
                                    <div class="p-2 text-center bg-warning bg-darken-2">
                                        <i class="fas fa-indian-rupee-sign font-large-2 white"></i>
                                    </div>
                                    <div class="p-2 bg-gradient-x-warning white media-body">
                                        <h5>ECG Payment</h5>
                                        <h3 id="ecgAmount" class="text-bold-400 mb-0">₹ <?php echo e(number_format($today_total_ecg_amount)); ?> </h3>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="pay-card total">
                        <div class="pay-header">
                            <span>Total Payment</span>
                            <h3 id="totalPayment">₹ <?php echo e(number_format($total_payment)); ?></h3>
                        </div>
                        <div id="totalDonut"></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="pay-card cash">
                        <div class="pay-header">
                            <span>Cash</span>
                            <h3 id="cashPayment">₹ <?php echo e(number_format($cash_payment)); ?></h3>
                        </div>
                        <div id="cashDonut"></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="pay-card online">
                        <div class="pay-header">
                            <span>Online</span>
                            <h3 id="onlinePayment">₹ <?php echo e(number_format($online_payment)); ?></h3>
                        </div>
                        <div id="onlineDonut"></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="pay-card debit ">
                        <div class="pay-header">
                            <span>Debit</span>
                            <h3>₹ <?php echo e(number_format($debit_payment)); ?></h3>
                        </div>
                        <div id="debitDonut"></div>
                        
                    </div>
                </div>


            </div>

        </div>

        

        <div class="content-wrapper">  
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- 🔘 BUTTONS (always visible) -->
                            <div class="chart-btns mb-4">
                                <button class="btn btn-md   btn-primary me-1 chart-toggle"
                                    onclick="showChart('weekly', this)">7 Days</button>

                                <button class="btn btn-md   btn-outline-primary me-1 chart-toggle"
                                    onclick="showChart('monthly', this)">Daily</button>

                                

                            </div>

                            <!-- DAILY -->
                            <div class="chart-box" id="chart-weekly">
                                <div id="weeklyPatientChart"></div>
                            </div>

                            <!-- MONTHLY -->
                            <div class="chart-box d-none" id="chart-monthly">
                                <div id="dailyPatientChart"></div>
                            </div>

                            <!-- YEARLY -->
                            <div class="chart-box d-none" id="chart-yearly">
                                <div id="monthlyPatientChart"></div>
                            </div>


                            <div class="chart-box d-none" id="chart-year">
                                <div id="chartYear"></div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

</div>

        

        

        
    </div>

<?php $__env->stopSection(); ?>

<script>
function updateDashboard(data) {

    document.getElementById('totalPatient').innerText = data.total_patient;
    document.getElementById('newPatient').innerText   = data.new_patient;
    document.getElementById('oldPatient').innerText   = data.old_patient;

    document.getElementById('ecgPatient').innerText   = data.ecg_patient;
    document.getElementById('ecgAmount').innerText    =
        '₹ ' + Number(data.ecg_amount).toLocaleString();

    document.getElementById('totalPayment').innerText =
        '₹ ' + Number(data.total_payment).toLocaleString();

    document.getElementById('cashPayment').innerText  =
        '₹ ' + Number(data.cash_payment).toLocaleString();

    document.getElementById('onlinePayment').innerText =
        '₹ ' + Number(data.online_payment).toLocaleString();
}
</script>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
function createPatientChart(chartId, url, mode = 'day') {
    let charts = {
    daily: null,
    monthly: null,
    yearly: null
};

    let keys = [];
    // let chartDates = [];
    let clickTimer = null;

    var options = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false },

            events: {
                dataPointSelection: function (event, chartContext, config) {

                    let key = keys[config.dataPointIndex];
                    if (!key) return;

                    let type =
                        config.seriesIndex === 0 ? 'total' :
                        config.seriesIndex === 1 ? 'new' :
                        config.seriesIndex === 2 ? 'old' :
                        'ecg';

                    if (clickTimer) {
                        clearTimeout(clickTimer);
                        clickTimer = null;

                        // 🔥 DOUBLE CLICK
                        if (mode === 'month') {
                            window.location.href =
                                `/receptionist/dashboard/patient-list?month=${key}&type=${type}`;
                        }
                         else if (mode === 'financial') {
                            window.location.href =
                                `/receptionist/dashboard/patient-list?financial_year=${key}&type=${type}`;
                        }
                         else {
                            window.location.href =
                                `/receptionist/dashboard/patient-list?date=${key}&type=${type}`;
                        }
                        return;
                    }

                    clickTimer = setTimeout(() => {
                        clickTimer = null;

                          // 🔹 SINGLE CLICK
                        if (mode === 'month') {
                            fetch(`/receptionist/dashboard/month-data?month=${key}`)
                                .then(r => r.json())
                                .then(updateDashboard);
                        }
                         else if (mode === 'financial') {
                            fetch(`/receptionist/dashboard/financial-year-data?year=${key}`)
                                .then(r => r.json())
                                .then(updateDashboard);
                        }
                        else {
                            fetch(`/receptionist/dashboard/day-data?date=${key}`)
                                .then(r => r.json())
                                .then(updateDashboard);
                        }
                    }, 250);
                }
            }
        },
        plotOptions: {
            bar: { columnWidth: '55%' }
        },
        dataLabels: { enabled: false },
        xaxis: { categories: [] },
        series: [],
        legend: { position: 'bottom' }
    };

    var chart = new ApexCharts(
        document.querySelector(`#${chartId}`),
        options
    );

    
    
    fetch(url)
        .then(r => r.json())
        .then(data => {

            // keys = mode === 'month' ? data.months : data.dates;
            // keys = data.keys;
             if (mode === 'financial') {
                    keys = data.keys;
                } else if (mode === 'month') {
                    keys = data.months;
                } else {
                    keys = data.dates;
                }

            chart.updateOptions({
                xaxis: { categories: data.labels   },
                series: [
                    { name: 'Total Patients', data: data.total },
                    { name: 'New Patients',   data: data.new },
                    { name: 'Old Patients',   data: data.old },
                    { name: 'ECG Patients',   data: data.ecg }
                ]
            });
        });
        chart.render();
        return chart;


}

document.addEventListener('DOMContentLoaded', function () {

    createPatientChart(
        'weeklyPatientChart',
        '/receptionist/dashboard/weekly-patient-chart'
    );

    createPatientChart(
        'dailyPatientChart',
        '/receptionist/dashboard/chart/daily'
    );

    createPatientChart(
        'monthlyPatientChart',
        '/receptionist/dashboard/chart/monthly','month'
    );

    createPatientChart(
        'chartYear',
        '/receptionist/dashboard/chart/financial-year',
        'financial'
    );

});
</script>

<script>
function showChart(type) {

    // hide all
    document.querySelectorAll('.chart-box').forEach(el => {
        el.classList.add('d-none');
    });

    // show selected
    document.getElementById('chart-' + type).classList.remove('d-none');

    // reset buttons
    document.querySelectorAll('.chart-toggle').forEach(b => {
        b.classList.remove('btn-primary');
        b.classList.add('btn-outline-primary');
    });
     // activate clicked button
    btn.classList.remove('btn-outline-primary');
    btn.classList.add('btn-primary');

    
}
</script>










<?php $__env->startSection('additionalcss'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('additionaljs'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\old_project\kaneria\resources\views/receptionist/home.blade.php ENDPATH**/ ?>