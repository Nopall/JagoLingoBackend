<!-- Menu -->
<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner py-1">

            <li class="menu-item {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}">
                <a href="/" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-home-circle"></i>
                    Dashboard
                </a>
            </li>

            <li class="menu-item {{ Request::is('master/course*') || Request::is('master/phase*') || Request::is('master/package*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-book-content"></i>
                    <div>Konten</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::is('master/course*') ? 'active' : '' }}">
                        <a href="{{ route('master.course.list') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-book-open"></i>
                            Course
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('master/phase*') ? 'active' : '' }}">
                        <a href="{{ route('master.phase.list') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-list-ul"></i>
                            Phase
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('master/package*') ? 'active' : '' }}">
                        <a href="{{ route('master.package.list') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-package"></i>
                            Package
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item {{ Request::is('master/subscription*') ? 'active' : '' }}">
                <a href="{{ route('master.subscription.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-crown"></i>
                    Subscription
                </a>
            </li>

            <li class="menu-item {{ Request::is('user*') ? 'active' : '' }}">
                <a href="{{ route('user.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-group"></i>
                    Users
                </a>
            </li>

            <li class="menu-item {{ Request::is('master/setting*') ? 'active' : '' }}">
                <a href="{{ route('master.setting.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-cog"></i>
                    Setting
                </a>
            </li>

        </ul>
    </div>
</aside>
<!-- / Menu -->
