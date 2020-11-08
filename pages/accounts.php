  <div class="main-content" id="panel">
    <!-- Topnav -->
    <?php include 'includes/nav-top.php'; ?>
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
                    <option value="ALL"> ALL </option>
                    <option value="STAFF"> STAFF </option>
                    <option value="MANAGER"> MANAGER </option>
                    <option value="EXECUTIVE"> EXECUTIVE </option>
                    <option value="CEO"> CEO </option>
                    <option value="IT"> IT </option>
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
              <table class="table align-items-center table-flush" data-toggle="table" data-pagination="true" data-page-size="2" data-pagination-parts="pageList" data-sort-name="id"
  data-sort-order="asc" data-search="true" data-search-selector="#searchInput" id="myTable">
                <thead class="thead-light">
                  <tr>
                    <th scope="col"> </th>
                    <th scope="col" data-field="name" data-sort="employee_name" data-sortable="true">
                        Employee Name
                    </th>
                    <th scope="col" data-field="username" data-sort="username" data-sortable="true">
                        Username
                    </th>
                    <th scope="col" data-field="position" data-sort="position">
                        Position
                    </th>
                    <th scope="col" data-field="branch=name" data-sort="branch_name">
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
                    $branches = explode('|', $row['branch_names']); ?>
                    <tr>
                        <td>
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a href="#" class="avatar rounded-circle mr-3">
                                    <img alt="Image placeholder" src="assets/img/theme/team-4.jpg">
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['position']; ?></td>
                        <td>
                            <select class="form-control">
                            <?php 
                            foreach($branches as $branch) echo '<option>' . $branch .'</option>'; 
                            ?>
                            </select>
                        </td>
                        <td class="text-right">
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#editAccountModal">Edit account</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


<!-- Modal edit account -->
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form role="form" action="" method="post">
            <fieldset>
                <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative modal-div-input">
                        <input class="form-control modal-div-input" placeholder="First Name" name="name" type="text">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative modal-div-input">
                        <input class="form-control modal-div-input" placeholder="Last Name" name="name" type="text">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative modal-div-input">
                        <input class="form-control modal-div-input" placeholder="Username" name="name" type="text">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative modal-div-input">
                        <input class="form-control modal-div-input" placeholder="Password" name="name" type="text">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <select class="form-control create-acc-select modal-div-input" name="category" required>
                        <option value="" disabled selected> Choose Position </option>
                        <option value="ELECTRONICS"> STAFF </option>
                        <option value="DRINKS"> MANAGER </option>
                        <option value="SNACKS"> EXECUTIVE </option>
                        <option value="MEDICAL"> CEO </option>
                        <option value="MEDICAL"> IT </option>
                    </select>
                </div>
                <div style="margin-top:30px; margin-bottom:10px;">
                    <span>Select branches</span>
                </div>
                <div class="form-group mb-0">
                  <div class="input-group input-group-alternative input-group-merge modal-div-input">
                    <div class="input-group-prepend">
                      <span class="input-group-text modal-div-input"><i class="fas fa-search"></i></span>
                    </div>
                    <input class="form-control modal-div-input" placeholder="Search" type="text" id="tableSearch">
                  </div>
                </div>
                <div style="height:10px;"></div>
                <div class="form-group mb-3">
                    <table
                      id="table"
                      data-toggle="table"
                      data-height="200"
                      data-click-to-select="true"
                      data-search="true"
                      data-search-selector="#tableSearch" 
                      data-maintain-meta-data="true">
                      <thead>
                        <tr>
                          <th data-field="state" data-checkbox="true"></th>
                          <th data-field="id">Branch ID</th>
                          <th data-field="name">Branch Name</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td></td>
                              <td>dhe</td>
                              <td>dhd</td>
                          </tr>
                          <tr>
                              <td></td>
                              <td>hghg</td>
                              <td>dhd</td>
                          </tr>
                          <tr>
                              <td></td>
                              <td>tjv</td>
                              <td>reyf</td>
                          </tr>
                      </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
                  <button name="create" type="submit" class="btn btn-primary">Create</button>
                </div>
            </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
