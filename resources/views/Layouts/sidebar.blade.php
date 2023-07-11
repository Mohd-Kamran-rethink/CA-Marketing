<style>
    .os-scrollbar-horizontal {
        display: none
    }
</style>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <a href="{{ url('/dashboard') }}" class="brand-link">
            <img src="https://imgs.search.brave.com/jjizMxNTRgX8Jd1PNu7XXsh0-_jVVpSJF-bVeHWJZ_c/rs:fit:860:900:1/g:ce/aHR0cHM6Ly93d3cu/a2luZHBuZy5jb20v/cGljYy9tLzc4LTc4/NjIwN191c2VyLWF2/YXRhci1wbmctdXNl/ci1hdmF0YXItaWNv/bi1wbmctdHJhbnNw/YXJlbnQucG5n"
                alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">CA Marketing</span>
        </a>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item  ">
                    <a href="{{ url('/dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ url('/bank-accounts') }}" class="nav-link {{ Request::is('bank-accounts') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-building"></i>
                        <p>
                            Banks
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ url('/sources') }}" class="nav-link {{ Request::is('sources') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder "></i>
                        <p>
                            Sources
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ url('/agents') }}" class="nav-link {{ Request::is('agents') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Agents
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ url('/accounts') }}" class="nav-link {{ Request::is('accounts') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-fa fa-th"></i>
                        <p>
                            Accounts
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ url('/campaigns') }}" class="nav-link {{ Request::is('campaigns') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-tasks"></i>
                        <p>
                            Campaigns
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('transfers') }}"
                        class="nav-link {{ Request::is('transfers') || Request::is('transfers/add') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-credit-card"></i>
                        <p>
                            Transfer
                        </p>
                    </a>
                </li>
                
                
            </ul>
        </nav>
    </div>
</aside>
