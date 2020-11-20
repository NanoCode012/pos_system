<?php
if (isset($_POST['edit'])) {
    $dict = [
        'first_name' => '0',
        'last_name' => '0',
        'username' => '0',
        'position' => '0',
    ];

    foreach ($dict as $key => $value) {
        if (!isset($_POST[$key]) || trim($_POST[$key]) == '') {
            $error = true;
            echo $key . ' error';
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }

    if ($_POST['branch-selected'] != '') {
        $arr = explode(',', $_POST['branch-selected']);

        //Delete all old, set new
        $db->delete('assignments', ['user_id' => $_POST['id']]);
        foreach ($arr as $ar) {
            $db->insert('assignments', [
                'user_id' => $_POST['id'],
                'branch_id' => $ar,
            ]);
        }
    }

    if ($_POST['password'] != '') {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $db->update(
            'users',
            [
                'first_name' => $dict['first_name'],
                'last_name' => $dict['last_name'],
                'username' => $dict['username'],
                'password' => $password_hash,
                'position' => $dict['position'],
            ],
            ['id' => $_POST['id']]
        );
    } else {
        $db->update(
            'users',
            [
                'first_name' => $dict['first_name'],
                'last_name' => $dict['last_name'],
                'username' => $dict['username'],
                'position' => $dict['position'],
            ],
            ['id' => $_POST['id']]
        );
    }
}
elseif (isset($_POST['delete'])){
    $db->delete('users', ['id' => $_POST['id']]);
}
?>
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
                            <select class="form-control branch-filter" style="margin-left:20px" id="filterPosition">
                                <option value="ALL"> ALL </option>
                                <option value="STAFF"> STAFF </option>
                                <option value="MANAGER"> MANAGER </option>
                                <option value="EXECUTIVE"> EXECUTIVE </option>
                                <option value="CEO"> CEO </option>
                                <option value="IT"> IT </option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-5 text-right branch-filter" style="margin-left:10px;">
                            <select class="form-control branch-filter" style="margin-left:20px" id="filterBranch">
                                <option value="ALL"> ALL </option>
                                <?php
                                $rows = $db->run('SELECT * FROM branches');
                                foreach ($rows as $row) { ?>
                                <option value="<?php echo $row[
                                    'name'
                                ]; ?>"> <?php echo $row['name']; ?> </option>
                                <?php }
                                ?>
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
                        <table class="table align-items-center table-flush" data-toggle="table" data-pagination="true"
                            data-page-size="2" data-pagination-parts="pageList" data-sort-name="id"
                            data-sort-order="asc" data-search="true" data-search-selector="#searchInput" id="myTable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col"> </th>
                                    <th scope="col" data-field="name" data-sortable="true">
                                        Employee Name
                                    </th>
                                    <th scope="col" data-field="username" data-sortable="true">
                                        Username
                                    </th>
                                    <th scope="col" data-field="position" data-sortable="true">
                                        Position
                                    </th>
                                    <th scope="col" data-field="branch" data-sortable="true">
                                        Branch Name
                                    </th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php
                                $rows = $db->run(
                                    'SELECT u.id, u.first_name, u.last_name, CONCAT(u.first_name, " ", u.last_name) AS name, u.username, u.position, GROUP_CONCAT(b.name SEPARATOR "|") AS branch_names ' .
                                        'FROM users u, branches b, assignments a WHERE u.id = a.user_id and b.id = a.branch_id GROUP BY u.id;'
                                );
                                foreach ($rows as $row) {
                                    $branches = explode(
                                        '|',
                                        $row['branch_names']
                                    ); ?>
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
                                            <?php foreach (
                                                $branches
                                                as $branch
                                            ) {
                                                echo '<option>' .
                                                    $branch .
                                                    '</option>';
                                            } ?>
                                        </select>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" data-toggle="modal"
                                                    data-target="#editAccountModal<?php echo $row[
                                                        'id'
                                                    ]; ?>">Edit</a>
                                                    <form role="form" action="" method="post">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>" >
                                                        <button name="delete" type="submit" class="dropdown-item">Delete</button>
                                                    </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
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


<!-- Modal edit account -->
<?php foreach ($rows as $row) {
    $branches = $db->run(
        'SELECT b.id, b.name FROM branches b, users u, assignments a WHERE u.id = a.user_id and b.id = a.branch_id and u.id=?',
        $row['id']
    ); ?>
<div class="modal fade" id="editAccountModal<?php echo $row[
    'id'
]; ?>" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <input class="form-control modal-div-input" placeholder="First Name" name="first_name"
                                    type="text" value="<?php echo $row[
                                        'first_name'
                                    ]; ?>">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group input-group-merge input-group-alternative modal-div-input">
                                <input class="form-control modal-div-input" placeholder="Last Name" name="last_name"
                                    type="text" value="<?php echo $row[
                                        'last_name'
                                    ]; ?>">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group input-group-merge input-group-alternative modal-div-input">
                                <input class="form-control modal-div-input" placeholder="Username" name="username"
                                    type="text" value="<?php echo $row[
                                        'username'
                                    ]; ?>">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group input-group-merge input-group-alternative modal-div-input">
                                <input class="form-control modal-div-input" placeholder="New Password" name="password"
                                    type="text">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <select class="form-control create-acc-select modal-div-input" name="position" required>
                                <option value="" disabled> Choose Position </option>
                                <option value="STAFF" <?php if (
                                    $row['position'] == 'STAFF'
                                ) {
                                    echo 'selected';
                                } ?>>
                                    STAFF </option>
                                <option value="MANAGER" <?php if (
                                    $row['position'] == 'MANAGER'
                                ) {
                                    echo 'selected';
                                } ?>>
                                    MANAGER </option>
                                <option value="EXECUTIVE"
                                    <?php if ($row['position'] == 'EXECUTIVE') {
                                        echo 'selected';
                                    } ?>> EXECUTIVE
                                </option>
                                <option value="CEO" <?php if (
                                    $row['position'] == 'CEO'
                                ) {
                                    echo 'selected';
                                } ?>> CEO
                                </option>
                                <option value="IT" <?php if (
                                    $row['position'] == 'IT'
                                ) {
                                    echo 'selected';
                                } ?>> IT
                                </option>
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
                                <input class="form-control modal-div-input" placeholder="Search" type="text"
                                    id="tableSearch<?php echo $row['id']; ?>">
                            </div>
                        </div>
                        <div style="height:10px;"></div>
                        <div class="form-group mb-3">
                            <table id="table<?php echo $row[
                                'id'
                            ]; ?>" class='modalTable' data-toggle="table"
                                data-height="255" data-click-to-select="true" data-search="true"
                                data-search-selector="#tableSearch<?php echo $row[
                                    'id'
                                ]; ?>"
                                data-maintain-meta-data="true" data-pagination="true" data-pagination-parts="pageList"
                                data-page-size="3" data-sort-name="state" data-maintain-meta-data="true">
                                <thead>
                                    <tr>
                                        <th data-field="state" data-checkbox="true"></th>
                                        <th data-field="id">Branch ID</th>
                                        <th data-field="name">Branch Name</th>
                                    </tr>
                                </thead>
                                <tbody data-service='<?php echo json_encode(
                                    $branches
                                ); ?>'>
                                    <?php
                                    $all_branches = $db->run(
                                        'SELECT b.id, b.name FROM branches b'
                                    );
                                    foreach ($all_branches as $branch) { ?>
                                    <tr>
                                        <td></td>
                                        <td><?php echo $branch['id']; ?></td>
                                        <td><?php echo $branch['name']; ?></td>
                                    </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <input type="text" name="id" value="<?php echo $row[
                                'id'
                            ]; ?>" hidden>
                            <input type="text" name="branch-selected" value="" hidden>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button name="edit" type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
} ?>

<script>

$(function() {


    $(document).ready(function() {
        //Select branches in table
        $('.modalTable').map(function() {
            let data = $(this).find('tbody').data('service');
            let arr = [];
            $.each(data, (i, $element) => {
                arr.push($element['id'].toString());
            });
            // Need to toggle pagination to set checked or it won't see more than first page
            $(this).bootstrapTable('togglePagination').bootstrapTable('checkBy', {
                field: 'id',
                values: arr
            }).bootstrapTable('togglePagination');
        });
        
        
        jQuery.fn.setSelection = function() {
            console.log('i am called');
            let $selector = $(this).parents('.modal-body').find(
                'input[name="branch-selected"]');
            let arr = [];
            $.each($(this).bootstrapTable('getSelections'), (i, $element) => {
                arr.push($element['id']);
            });
            $selector.val(arr);
        };
        //Save selections to an input
        $('.modalTable').on('check.bs.table check-all.bs.table uncheck.bs.table uncheck-all.bs.table', $.fn.setSelection);
    });

    var $table = $('#myTable');
    var $selectorPosition = $('#filterPosition');
    var $selectorBranch = $('#filterBranch');

    $.fn.branchFilter = function(row, filter) {
        let match = false;
        filter = filter['branch'].toUpperCase();
        var $options = $(row['branch']).children('option');
        $options.each((index, val) => {
            if ($(val).val().toUpperCase() === filter) {
                match = true;
                return false; //breaks out of each loop
            }
        });
        return match;
    };

    $.fn.combinedFilter = function(row, filter) {
        let match = false;
        match = $.fn.branchFilter(row, filter);
        if (match) {
            filter = filter['position'].toUpperCase();
            match = (row['position'] == filter);
        }
        return match;
    };

    $selectorPosition.add($selectorBranch).on('change', ()=> {
        var $position = $selectorPosition.children('option:selected').val();
        var $branch = $selectorBranch.children('option:selected').val();
        if ($position == 'ALL' && $branch == 'ALL') {
            $table.bootstrapTable('refreshOptions', {
                filterOptions: {
                    filterAlgorithm: 'and'
                }
            });
            $table.bootstrapTable('filterBy', {});
        } else if ($position != 'ALL' && $branch == 'ALL') {
            $table.bootstrapTable('refreshOptions', {
                filterOptions: {
                    filterAlgorithm: 'and'
                }
            });
            $table.bootstrapTable('filterBy', {
                position: $position
            });
        } else if ($position == 'ALL' && $branch != 'ALL') {
            $table.bootstrapTable('refreshOptions', {
                filterOptions: {
                    filterAlgorithm: $.fn.branchFilter
                }
            });
            $table.bootstrapTable('filterBy', {
                branch: $branch
            });
        } else {
            $table.bootstrapTable('refreshOptions', {
                filterOptions: {
                    filterAlgorithm: $.fn.combinedFilter
                }
            });
            $table.bootstrapTable('filterBy', {
                branch: $branch,
                position: $position
            });
        }
    });
});
</script>