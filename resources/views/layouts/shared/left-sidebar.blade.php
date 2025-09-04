<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="{{ route('any', 'index') }}" class="logo logo-light">
        HRM-EMPLOYEE-SYSTEM
        {{-- <span class="logo-lg">
            <img src="/images/logo.png" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="/images/logo-sm.png" alt="small logo">
        </span> --}}
    </a>

    <!-- Brand Logo Dark -->
    <a href="{{ route('any', 'index') }}" class="logo logo-dark">
         HRM-EMPLOYEE-SYSTEM
        {{-- <span class="logo-lg">
            <img src="/images/logo-dark.png" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="/images/logo-sm.png" alt="small logo">
        </span> --}}
    </a>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title">Main</li>

            <li class="side-nav-item">
                {{-- <a href="{{ route('any', 'index') }}" class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <span class="badge bg-success float-end">9+</span>
                    <span> Dashboard </span>
                </a> --}}
                  <a href="{{ route('customer.index') }}" class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <span>ข้อมูลลูกค้า </span>
                </a>
                
            </li>

            <li>
                 <a href="{{ route('employees.index') }}" class="side-nav-link">
                    <i class="ri-compasses-2-line"></i>
                    <span>ข้อมูลผู้สมัคร </span>
                </a>
            </li>


             <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarReports" aria-expanded="false" aria-controls="sidebarReports"
                    class="side-nav-link">
                    <i class="ri-file-chart-line"></i>
                    <span>รายงาน</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarReports">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('reports.customer-employee') }}">รายงานจำนวนพนักงาน</a>
                        </li>
                        <li>
                            <a href="{{ route('reports.employee-report') }}">รายงานข้อมูลพนักงาน</a>
                        </li>
                        <li>
                            <a href="{{ route('reports.labor-demand') }}">สถิติข้อมูลพนักงาน</a>
                        </li>
                    </ul>
                </div>
            </li>
            
            
             <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false" aria-controls="sidebarPages"
                    class="side-nav-link">
                    <i class="ri-pages-line"></i>
                    <span>ตั้งค่าระบบ </span>
                    <span class="menu-arrow"></span>
                </a>
                
                <div class="collapse" id="sidebarPages">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('global-sets') }}">สถานะข้อมูล</a>
                        </li>
                       
                    </ul>
                </div>
            </li>

             @if(auth()->check() && (auth()->user()->hasRole('SuperAdmin') || (method_exists(auth()->user(), 'can') && auth()->user()->can('view user'))))
             
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarManagement" aria-expanded="false" aria-controls="sidebarManagement" class="side-nav-link">
                                <i class="ri-settings-3-line"></i>
                                <span>การจัดการ</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarManagement">
                                <ul class="side-nav-second-level">
                                    @if(auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin'))
                                    <li><a href="{{ route('profiles.index') }}">โปรไฟล์ (Profile)</a></li>
                                    @endif
                                    @can('view user')
                                    <li><a href="{{ route('users.index') }}">ผู้ใช้งาน</a></li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
            @endif
            

           

        </ul>


        <div class="clearfix"></div>
    </div>
</div>
