<!-- Main content -->
<div class="main-content" id="panel">
<!-- Topnav -->
<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Search form -->
      <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
        <div class="form-group mb-0">
          <div class="input-group input-group-alternative input-group-merge">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input class="form-control" placeholder="Search" type="text" id="searchInput">
          </div>
        </div>
        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
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
          <h6 class="h2 text-white d-inline-block mb-0">Branches</h6>
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
          <h3 class="mb-0">Branches Table</h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
          <table class="table align-items-center table-flush" data-toggle="table" data-pagination="true" data-page-size="2" data-pagination-parts="pageList" data-sort-name="name"
  data-sort-order="desc" data-search="true" data-search-selector="#searchInput" id="myTable">
            <thead class="thead-light">
              <tr>
                <th scope="col" data-sortable="true">Branch ID</th>
                <th scope="col" data-sortable="true">Branch Name</th>
                <th scope="col" data-sortable="true">Branch Address</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody class="list">
            <?php 
            $rows = $db->run('SELECT b.id, b.name, b.address FROM branches b, users u, assignments a WHERE u.id = a.user_id and b.id = a.branch_id and u.id=?', $_SESSION['user_id']);
            foreach ($rows as $row) {
                echo '<tr>';
                echo   '<td>' . $row['id'] .'</td>
                        <td>' . $row['name'] .'</td>
                        <td>' . $row['address'] .'</td>
                        <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" data-toggle="modal" data-target="#createBranchModal>Edit branch</a>
                            <a class="dropdown-item">Delete branch</a>
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

<!-- Modal create new branch -->
<div class="modal fade" id="createBranchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New Branch</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form role="form" action="" method="post">
            <fieldset>
                <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative modal-div-input">
                        <input class="form-control modal-div-input" placeholder="Product Name" name="name" type="text">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <select class="form-control create-acc-select modal-div-input" name="category" required>
                        <option value="" disabled selected> Choose Product Category </option>
                        <option value="ELECTRONICS"> ELECTRONICS </option>
                        <option value="DRINKS"> DRINKS </option>
                        <option value="SNACKS"> SNACKS </option>
                        <option value="MEDICAL"> MEDICAL </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative modal-div-input">
                        <input class="form-control modal-div-input" placeholder="Sale Price" name="sell_price" type="number">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative modal-div-input">
                        <input class="form-control modal-div-input" placeholder="Buy Price" name="buy_price" type="number">
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button name="create" type="submit" class="btn btn-primary">Create</button>
                </div>
            </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>