<?php
  require(__DIR__ . '/vendor/autoload.php');
  
  if (file_exists(__DIR__ . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
  }

  $pdo = new PDO($_ENV['PDO_CONNECTION_STRING']);
  $sql = '
    SELECT 
      invoices.id,
      invoice_date,
      total,
      customers.first_name as first_name,
      customers.last_name as last_name
    FROM invoices
    INNER JOIN customers
    ON invoices.customer_id = customers.id
  ';

  if (isset($_GET['search'])) {
    $sql = $sql . ' WHERE customers.first_name LIKE :first_name';
  }

  $statement = $pdo->prepare($sql);

  if (isset($_GET['search'])) {
    $boundSearchParam = '%' . $_GET['search'] . '%';
    $statement->bindParam(':first_name', $boundSearchParam);
  }

  $statement->execute();
  $invoices = $statement->fetchAll(PDO::FETCH_OBJ);
?>

<form action="index.php" method="GET">
  <input
    type="text"
    name="search"
    placeholder="Search by first name"
    value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>">

  <button type="submit">
    Search
  </button>

  <a href="/">Clear</a>
</form>

<?php if (count($invoices) === 0) : ?>
  <div>
    No results
  </div>
<?php else : ?>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Total</th>
        <th>Customer</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($invoices as $invoice) : ?>
        <tr>
          <td>
            <?php echo $invoice->id ?>
          </td>
          <td>
            <?php echo $invoice->invoice_date ?>
          </td>
          <td>
            <?php echo $invoice->total ?>
          </td>
          <td>
            <?php echo $invoice->first_name . " " . $invoice->last_name ?>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>

