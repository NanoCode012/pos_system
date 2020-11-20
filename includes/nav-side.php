<body>
<!-- Side Nav -->
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="assets/img/brand/chanchanlogo.png" class="navbar-brand-img" alt="...">
            </a>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <?php $position = $db->cell('SELECT position FROM users WHERE id = ?', $_SESSION['user_id']) ?>
                <ul class="navbar-nav nav">
                    <?php if (in_array($position, array('CEO', 'EXECUTIVE', 'IT'))) { ?>
                    <li class="nav-item nav-tab-margin">
                        <a class="nav-link <?php if ($page === 'dashboard') {
                            echo 'active';
                        } ?>" href="?p=dashboard">
                            <i class="ni ni-chart-bar-32 text-primary"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array($position, array('STAFF', 'MANAGER', 'EXECUTIVE', 'IT'))) { ?>
                    <li class="nav-item nav-tab-margin">
                        <a class="nav-link <?php if ($page === 'stocks') {
                            echo 'active';
                        } ?>" href="?p=stocks">
                            <i class="ni ni-collection" style="color:#a800c2"></i>
                            <span class="nav-link-text">Stocks</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array($position, array('MANAGER', 'EXECUTIVE', 'IT'))) { ?>
                    <li class="nav-item nav-tab-margin">
                        <a class="nav-link <?php if ($page === 'products') {
                            echo 'active';
                        } ?>" href="?p=products">
                            <i class="ni ni-planet text-orange"></i>
                            <span class="nav-link-text">Products</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array($position, array('CEO', 'IT'))) { ?>
                    <li class="nav-item nav-tab-margin">
                        <a class="nav-link <?php if ($page === 'branches') {
                            echo 'active';
                        } ?>" href="?p=branches">
                            <i class="ni ni-pin-3 text-primary"></i>
                            <span class="nav-link-text">Branches</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array($position, array('IT'))) { ?>
                    <li class="nav-item nav-tab-margin">
                        <a class="nav-link <?php if ($page === 'accounts') {
                            echo 'active';
                        } ?>" href="?p=accounts">
                            <i class="ni ni-single-02 text-yellow"></i>
                            <span class="nav-link-text">Accounts</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</nav>