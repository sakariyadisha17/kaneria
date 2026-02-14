<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="navigation-header">
                <!-- <span>Main Menu</span> -->
                <!-- <i class="feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="General"></i> -->
            </li>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'Admin')): ?>
            <li class="nav-item">
                <a href="<?php echo e(url('admin/dashboard')); ?>">
                    <i class="fa-solid fa-house-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Dashboard">Dashboard</span>
                </a>
            </li>
            <li class="nav-item has-sub <?php if(Request::is('master', 'master/*') || Request::is('doctor/*') || Request::is('times') || Request::is('charges')): ?> menu-collapsed-open active <?php endif; ?>">
                <a href="#">
                    <i class="feather icon-command"></i> <!-- master Icon -->
                    <span class="menu-title" style="color:#fff;" data-i18n="master">Masters</span>
                </a>
                <ul class="menu-content">
                    <li class="<?php if(Request::is('admin/doctors')): ?> active <?php endif; ?>">
                        <a class="menu-item" href="<?php echo e(URL('admin/doctors')); ?>" data-i18n="Add doctor">
                            <i class="feather icon-list"></i>Doctor List
                        </a>
                    </li>
                    
                    <li class="<?php if(Request::is('admin/times')): ?> active <?php endif; ?>">
                        <a class="menu-item" href="<?php echo e(URL('admin/times')); ?>" data-i18n="master times">
                            <i class="feather icon-plus"></i>Time
                        </a>
                    </li>
                    <li class="<?php if(Request::is('admin/charges')): ?> active <?php endif; ?>">
                        <a class="menu-item" href="<?php echo e(URL('admin/charges')); ?>" data-i18n="master charges">
                            <i class="feather icon-check-circle"></i>Charge
                        </a>
                    </li>
                    <li class="<?php if(Request::is('admin/services')): ?> active <?php endif; ?>">
                        <a class="menu-item" href="<?php echo e(URL('admin/services')); ?>" data-i18n="master services">
                            <i class="fa fa icon-target"></i>Service
                        </a>
                    </li>

                    <li class="<?php if(Request::is('admin/medicine')): ?> active <?php endif; ?>">
                        <a class="menu-item" href="<?php echo e(URL('admin/medicine')); ?>" data-i18n="master medicine">
                            <i class="fa-solid fa-sheet-plastic"></i>Medicine
                        </a>
                    </li>

                    <li class="<?php if(Request::is('admin/diagnosis')): ?> active <?php endif; ?>">
                        <a class="menu-item" href="<?php echo e(URL('admin/diagnosis')); ?>" data-i18n="master diagnosis">
                            <i class="feather icon-activity"></i>Diagnosis
                        </a>
                    </li>

                    <li class="<?php if(Request::is('admin/rooms')): ?> active <?php endif; ?>">
                        <a class="menu-item" href="<?php echo e(URL('admin/rooms')); ?>" data-i18n="master rooms">
                            <i class="feather icon-plus-circle"></i>Room
                        </a>
                    </li>

                    <li class="<?php if(Request::is('admin/beds')): ?> active <?php endif; ?>">
                        <a class="menu-item" href="<?php echo e(URL('admin/beds')); ?>" data-i18n="master beds">
                            <i class="fa fa icon-drawer"></i>Bed
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item">
                <a href="<?php echo e(url('admin/patients')); ?>">
                    <i class="fa-solid fa-wheelchair"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="patients">Patient List</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('admin/old_patient')); ?>">
                    <i class="feather icon-file-plus"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="message">Old Patients</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('admin/survey')); ?>">
                    <i class="fa fa-superpowers"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="message">Patient Survey</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('admin/discharge')); ?>">
                    <i class="fa fa-ravelry"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="message">Patient Discharge List</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('admin/users')); ?>">
                    <i class="fa-solid fa-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="users">Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('profile')); ?>">
                    <i class="fa-solid fa-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Profile">Profile</span>
                </a>
            </li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'Doctor')): ?>            
            
            <li class="nav-item">
                <a href="<?php echo e(url('doctor/dashboard')); ?>">
                    <i class="fa-solid fa-house-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Dashboard">Dashboard</span>
                </a>
            </li> 
            <li class="nav-item">
                <a href="<?php echo e(url('doctor/appointments')); ?>">
                    <i class="fa-solid fa-list-ol"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Appointment">Appointment List</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('doctor/vitals')); ?>">
                    <i class="fa-solid fa-bed-pulse"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="vitals">Patient Vitals</span>
                </a>
            </li>  

            <li class="nav-item">
                <a href="<?php echo e(url('doctor/patients')); ?>">
                    <i class="fa-solid fa-bed"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Patient">Admit Patient</span>
                </a>
            </li>     
            
            <li class="nav-item">
                <a href="<?php echo e(url('doctor/patient_dose')); ?>">
                    <i class="fa-solid fa-sheet-plastic"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Patient">Indoor sheet</span>
                </a>
            </li> 
            <li class="nav-item">
                <a href="<?php echo e(url('profile')); ?>">
                    <i class="fa-solid fa-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Profile">Profile</span>
                </a>
            </li>         
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'Medical Officer')): ?>            
            
            <li class="nav-item">
                <a href="<?php echo e(url('medical_officer/dashboard')); ?>">
                    <i class="fa-solid fa-house-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Dashboard">Dashboard</span>
                </a>
            </li>   
            <li class="nav-item">
                <a href="<?php echo e(url('medical_officer/appointments')); ?>">
                    <i class="fa-solid fa-list-ol"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Appointment">Appointment List</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('medical_officer/vitals')); ?>">
                    <i class="fa-solid fa-bed-pulse"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="vitals">Patient Vitals</span>
                </a>
            </li>  
            <li class="nav-item">
                <a href="<?php echo e(url('medical_officer/patients')); ?>">
                    <i class="fa-solid fa-bed"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Patient">Admit Patient List</span>
                </a>
            </li> 
           
            <li class="nav-item">
                <a href="<?php echo e(url('medical_officer/patient_dose')); ?>">
                    <i class="fa-solid fa-sheet-plastic"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Patient">Indoor sheet</span>
                </a>
            </li> 
            <li class="nav-item">
                <a href="<?php echo e(url('medical_officer/to_do_list')); ?>">
                    <i class="fa-solid fa-list-check"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Patient">To do list</span>
                </a>
            </li> 
            <li class="nav-item">
                <a href="<?php echo e(url('medical_officer/discharge_summary')); ?>">
                    <i class="fa-solid fa-eject"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="discharge"> Dischage Summary</span>
                </a>
            </li>
           
            <li class="nav-item">
                <a href="<?php echo e(url('profile')); ?>">
                    <i class="fa-solid fa-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Profile">Profile</span>
                </a>
            </li>   

            
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'Nursing')): ?>           
            
            <li class="nav-item">
                <a href="<?php echo e(url('nursing/dashboard')); ?>">
                    <i class="fa-solid fa-house-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Dashboard">Dashboard</span>
                </a>
            </li>    
            <li class="nav-item">
                <a href="<?php echo e(url('nursing/patients')); ?>">
                    <i class="fa-solid fa-bed"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Patient">Admit Patient</span>
                </a>
            </li>    
            <li class="nav-item">
                <a href="<?php echo e(url('nursing/patient_dose')); ?>">
                    <i class="fa-solid fa-syringe"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="dose">Patient Dose</span>
                </a>
            </li> 
            <li class="nav-item">
                <a href="<?php echo e(url('nursing/treatment_sheet')); ?>">
                    <i class="fa-solid fa-sheet-plastic"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="treatment">Treatment sheet</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('nursing/to_do_list')); ?>">
                    <i class="fa-solid fa-list-check"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Patient">To do list</span>
                </a>
            </li> 
            <li class="nav-item">
                <a href="<?php echo e(url('nursing/vitals_chart')); ?>">
                    <i class="fa-solid fa-bed-pulse"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="treatment">Vital chart</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?php echo e(url('nursing/room_shifting')); ?>">
                    <i class="fa-solid fa-wheelchair"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="treatment">Room shifting</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('nursing/discharge')); ?>">
                    <i class="fa-solid fa-eject"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="discharge">Patient Dischage</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('nursing/patient_bill')); ?>">
                    <i class="fa-solid fa-file-invoice"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="bill">Patient Bill</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('profile')); ?>">
                    <i class="fa-solid fa-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Profile">Profile</span>
                </a>
            </li>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'Receptionist')): ?>

            <li class="nav-item">
                <a href="<?php echo e(url('receptionist/booking')); ?>">
                    <i class="fa-solid fa-book"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Dashboard">Book For</span>
                </a>
            </li>
           
            <li class="nav-item">
                <a href="<?php echo e(url('receptionist/dashboard')); ?>">
                    <i class="fa-solid fa-house-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Dashboard">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?php echo e(url('receptionist/patient')); ?>">
                    <i class="fa-solid fa-wheelchair"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Booking">Booking Appointment</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('receptionist/appointments')); ?>">

                    <i class="fa-solid fa-list-ol"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Appointment">Appointment List</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('receptionist/vitals')); ?>">
                    <i class="fa-solid fa-bed-pulse"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="vitals">Patient Vitals</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('receptionist/patients')); ?>">
                    <i class="fa fa-search"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="vitals">Patient List</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('receptionist/reports')); ?>">
                    <i class="feather icon-file"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Reports">Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(url('profile')); ?>">
                    <i class="fa-solid fa-user"></i>
                    <span class="menu-title"style="color:#fff;" data-i18n="Profile">Profile</span>
                </a>
            </li>
            
           
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
<?php /**PATH D:\wamp64\www\old_project\kaneria\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>