<?php
if (isset($_POST['edit'])) {
    $db->run(
        'Call `Set stock`(?,?)', $_POST['stock-id'], $_POST['quantity']
    );
} 
?>
<!-- Main Content -->
<div class="main-content" id="panel">
    <!-- Topnav -->
    <?php include 'includes/nav-top.php'; ?>
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Stocks</h6>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <div class="col-lg-6 col-i5 text-right branch-filter" style="margin-left:10px;">
                <select class="form-control branch-filter" style="margin-left:20px" id="filterBranch">
                    <option value="ALL"> ALL </option>
                    <?php
                    $rows = $db->run(
                        'SELECT b.name FROM branches b, users u, assignments a ' .
                            'WHERE u.id = a.user_id and b.id = a.branch_id and u.id=?',
                        $_SESSION['user_id']
                    );
                    foreach ($rows as $row) { ?>
                        <option value="<?php echo $row['name']; ?>"> <?php echo $row['name']; ?> </option>
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
              <h3 class="mb-0">Stocks Table</h3>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush" data-toggle="table" data-pagination="true" data-page-size="2" 
              data-pagination-parts="pageList" data-sort-name="stock-id" data-sort-order="asc" data-search="true" data-search-selector="#searchInput"
              id="myTable">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" data-field="stock-id" data-sortable="true">Stock ID</th>
                    <th scope="col" data-field="product-id" data-sortable="true">Product ID</th>
                    <th scope="col" data-field="product-name" data-sortable="true">Product Name</th>
                    <th scope="col" data-field="branch" data-sortable="true">Branch Name</th>
                    <th scope="col" data-field="quantity" data-sortable="true">Quantity</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <?php 
                    $rows = $db->run(
                            'SELECT s.id AS stock_id, p.id AS product_id, p.name AS product_name, b.name AS branch_name, s.quantity ' .
                            'FROM branches b, users u, assignments a, products p, stocks s ' .
                            'WHERE u.id = a.user_id and b.id = a.branch_id and u.id=? and s.product_id = p.id and s.branch_id = b.id',
                        $_SESSION['user_id']
                    ); 
                    foreach($rows as $row) { ?>
                    <tr>
                        <td><?php echo $row['stock_id']; ?></td>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['branch_name']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td class="text-right">
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" data-toggle="modal" data-target="#modifyStockModal<?php echo $row['stock_id']; ?>">Edit Stock</a>
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

<?php foreach($rows as $row) { ?>
<!-- Modal modify stock -->
<div class="modal fade" id="modifyStockModal<?php echo $row['stock_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modify Stock Quantity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form role="form" action="" method="post">
            <fieldset>
                <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative modal-div-input">
                        <input class="form-control modal-div-input" placeholder="Quantity" name="quantity" type="number" value="<?php echo $row['quantity']; ?>">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <input type="hidden" name="stock-id" value="<?php echo $row['stock_id']; ?>" >
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button name="edit" type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<script>
$(function() {
    var $table = $('#myTable');
    var $selectorBranch = $('#filterBranch');

    $selectorBranch.change(function () {
        var $branch = $(this).children('option:selected').val();
        if ($branch != 'ALL') $table.bootstrapTable('filterBy', { branch: $branch });
        else $table.bootstrapTable('filterBy', {});
    });
});
</script>