<?php
  require(__DIR__ . '/vendor/autoload.php');
  
  $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();

  $host = $_ENV['HOST'];
  $dbname = $_ENV['DATABASE'];
  $user = $_ENV['USER'];
  $password = $_ENV['PASSWORD'];

  $pdo = new PDO("pgsql:host=$host;port=5432;dbname=$dbname;user=$user;password=$password");
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

  $statement = $pdo->prepare($sql);
  $statement->execute();
  $invoices = $statement->fetchAll(PDO::FETCH_OBJ);
?>

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

