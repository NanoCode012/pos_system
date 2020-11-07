  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Search form -->
          <form action="" class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
            <div class="form-group mb-0">
              <div class="input-group input-group-alternative input-group-merge">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input class="form-control" placeholder="Search" type="text" id="searchInput">
              </div>
            </div>
            <!-- <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button> -->
          </form>
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center  ml-md-auto ">
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
            </li>
            <li class="nav-item d-sm-none">
              <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                <i class="ni ni-zoom-split-in"></i>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="assets/img/theme/team-4.jpg">
                  </span>
                  <div class="media-body  ml-2  d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">John Snow</span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu  dropdown-menu-right ">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-single-02"></i>
                  <span>My profile</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-settings-gear-65"></i>
                  <span>Settings</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-user-run"></i>
                  <span>Logout</span>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inlin e-block mb-0">Accounts</h6>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <div class="col-lg-6 col-i5 text-right branch-filter" style="margin-left:10px;">
                <select class="form-control branch-filter" style="margin-left:20px">
                    <option> Choose Position </option>
                    <option> STAFF </option>
                    <option> MANAGER </option>
                    <option> EXECUTIVE </option>
                    <option> CEO </option>
                    <option> IT </option>
                </select>
              </div>
              <div class="col-lg-6 col-5 text-right branch-filter" style="margin-left:10px;">
                <select class="form-control branch-filter" style="margin-left:20px">
                    <option> Choose Branch </option>
                    <option> Branch A </option>
                    <option> Branch B </option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">Accounts Table</h3>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush" data-toggle="table" data-pagination="true" data-page-size="2" data-pagination-parts="pageList" data-sort-name="name"
  data-sort-order="desc" data-search="true" data-search-selector="#searchInput" id="myTable">
                <thead class="thead-light">
                  <tr>
                    <th scope="col"> </th>
                    <th scope="col" data-sort="employee_name" data-sortable="true">
                        Employee Name
                    </th>
                    <th scope="col" data-sort="username" data-sortable="true">
                        Username
                    </th>
                    <th scope="col" data-sort="position">
                        Position
                    </th>
                    <th scope="col" data-sort="branch_name">
                        Branch Name
                    </th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                <?php 
                $rows = $db->run(
                    'SELECT u.id, CONCAT(u.first_name, " ", u.last_name) AS name, u.username, u.position, GROUP_CONCAT(b.name SEPARATOR "|") AS branch_names ' .
                    'FROM users u, branches b, assignments a WHERE u.id = a.user_id and b.id = a.branch_id GROUP BY u.id;'
                );
                foreach ($rows as $row) {
                    $branches = explode('|', $row['branch_names']);
                    echo '<tr>';
                    echo   '<td>
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <a href="#" class="avatar rounded-circle mr-3">
                                        <img alt="Image placeholder" src="assets/img/theme/team-4.jpg">
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <th scope="row">' . $row['name'] .'</th>
                            <td>' . $row['username'] .'</td>
                            <td>' . $row['position'] .'</td>
                            <td>
                                <select class="form-control">';
                                foreach($branches as $branch) echo '<option>' . $branch .'</option>';
                    echo        '</select>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="#">Edit account</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>