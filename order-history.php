<?php
require 'vendor/autoload.php';
include('ccav/db.php');

// Filters
$status = $_GET['status'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';

// Basic WHERE clause
$where = "WHERE order_id LIKE '%SKC-Eosforyouth%'";

// Status-wise filtering
if ($status && $status !== 'registration') {
    $where .= " AND order_status = '" . $conn->real_escape_string($status) . "'";
}

// Date filtering
if ($startDate && $endDate) {
    $where .= " AND date BETWEEN '" . $conn->real_escape_string($startDate) . " 00:00:00' AND '" . $conn->real_escape_string($endDate) . " 23:59:59'";
}

// Main query (excluding registration)
$query = "SELECT * FROM user_payment $where ORDER BY id DESC";
$result = $conn->query($query);

// Registration data (users who registered but didn't pay)
$regWhere = "WHERE email NOT IN (
    SELECT email FROM user_payment 
    WHERE email IS NOT NULL AND email != ''
)";

// Apply date filter (for 'created_at' field in user_registration)
if ($startDate && $endDate) {
    $start = $conn->real_escape_string($startDate);
    $end = $conn->real_escape_string($endDate);
    $regWhere .= " AND DATE(created_at) BETWEEN '$start' AND '$end'";
}

$regQuery = "SELECT * FROM user_registration $regWhere ORDER BY id DESC";
$regResult = $conn->query($regQuery);

// Counts
$all = $conn->query("SELECT COUNT(*) as c FROM user_payment WHERE order_id LIKE '%SKC-Eosforyouth%'")->fetch_assoc()['c'] ?? 0;
$success = $conn->query("SELECT COUNT(*) as c FROM user_payment WHERE order_status = 'Success' AND order_id LIKE '%SKC-Eosforyouth%'")->fetch_assoc()['c'] ?? 0;
$aborted = $conn->query("SELECT COUNT(*) as c FROM user_payment WHERE order_status = 'Aborted' AND order_id LIKE '%SKC-Eosforyouth%'")->fetch_assoc()['c'] ?? 0;
$failure = $conn->query("SELECT COUNT(*) as c FROM user_payment WHERE order_status = 'failure' AND order_id LIKE '%SKC-Eosforyouth%'")->fetch_assoc()['c'] ?? 0;
$registrations = $regResult->num_rows;



if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Use the same filtered query
    if ($status === 'registration') {
        // Export Registration Data
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="registrations.csv"');
        $output = fopen('php://output', 'w');

        // Header row
        fputcsv($output, ['S.No', 'Name', 'Email', 'Phone', 'Organization', 'Designation', 'Questions', 'Registration Date']);

        $i = 1;

        // Base condition
        $regWhere = "WHERE email NOT IN (
        SELECT email FROM user_payment 
        WHERE email IS NOT NULL AND email != ''
    )";

        // Date filter
        if ($startDate && $endDate) {
            $start = $conn->real_escape_string($startDate);
            $end = $conn->real_escape_string($endDate);
            $regWhere .= " AND DATE(created_at) BETWEEN '$start' AND '$end'";
        }

        // Final query with filters
        $regQuery = "SELECT * FROM user_registration $regWhere ORDER BY id DESC";
        $regResult = $conn->query($regQuery);

        // Output rows
        while ($row = $regResult->fetch_assoc()) {
            fputcsv($output, [
                $i++,
                $row['name'],
                $row['email'],
                $row['contact_number'],
                $row['organization_name'] ?? 'N/A',
                $row['designation'] ?? 'N/A',
                $row['questions'],
                date('d/m/Y', strtotime($row['created_at']))
            ]);
        }

        fclose($output);
        exit;
    } else {
        // Export Payment Data
        $result = $conn->query("SELECT * FROM user_payment $where ORDER BY id DESC");

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="payments-history' . date('d-m-Y') . '.csv"');
        $output = fopen('php://output', 'w');

        // Header row
        fputcsv($output, ['S.No', 'Name', 'Email', 'Phone', 'Organization', 'Designation', 'Questions', 'Order ID', 'Amount', 'Currency', 'Status', 'Payment Mode', 'Date', 'Status Message']);

        $i = 1;
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $i++,
                $row['name'],
                $row['email'],
                $row['phone'],
                $row['organization_name'] ?? 'N/A',
                $row['designation'] ?? 'N/A',
                $row['questions'],
                $row['order_id'],
                $row['amount'],
                $row['currency'],
                $row['order_status'],
                $row['payment_method'] ?: 'NA',
                date('d/m/Y', strtotime($row['date'])),
                $row['status_message'] ?: 'NA'
            ]);
        }

        fclose($output);
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome to CEP Refresher</title>
    <link rel="icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            font-weight: bold;
            border: 2px solid;
            border-radius: 4px;
            margin-right: 5px;
        }

        .btn.success {
            color: green;
            border-color: green;
        }

        .btn.aborted {
            color: orange;
            border-color: orange;
        }

        .btn.failure {
            color: red;
            border-color: red;
        }

        .btn.all {
            color: black;
            border-color: black;
        }

        .btn.registration {
            color: #007bff;
            border-color: #007bff;
        }

        .filter-row {
            margin-bottom: 15px;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
        }

        .form-control {
            padding: 5px;
        }

        .btn-apply {
            background-color: green;
            color: #fff;
        }

        .btn-clear {
            background-color: red;
            color: #fff;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .filter-row {
            margin-bottom: 15px;
            text-align: center;
            /* Center align the filter buttons */
        }

        .filter-row .btn {
            margin: 0 10px;
            /* Adds space between buttons */
        }

        form {
            text-align: center;
            /* Center the form elements */
        }

        form input,
        form button {
            margin: 5px;
            /* Adds space between form elements */
        }

        .form-control {
            padding: 5px;
            margin: 5px;
        }

        .btn-apply,
        .btn-clear {
            padding: 6px 12px;
            font-weight: bold;
            border-radius: 4px;
        }

        .btn-apply {
            background-color: green;
            color: white;
        }

        .btn-clear {
            background-color: red;
            color: white;
        }
        .btn-export {
    background-color: #0872c9;
    color: white;
}
    </style>
</head>

<body>
    <h2 style="text-align: center;">Eos For Youth Sales Program Order History</h2>

    <!-- Filter Buttons -->
    <div class="filter-row">
        <a class="btn all" href="order-history.php">All (<?= $all ?>)</a>
        <a class="btn success" href="order-history.php?status=success">Success (<?= $success ?>)</a>
        <a class="btn aborted" href="order-history.php?status=aborted">Aborted (<?= $aborted ?>)</a>
        <a class="btn failure" href="order-history.php?status=failure">Failure (<?= $failure ?>)</a>
        <!-- <a class="btn registration" href="order-history.php?status=registration">Registrations Only (<?= $registrations ?>)</a> -->
    </div>

    <!-- Date Filter Form -->
    <form method="get" action="order-history.php" style="margin-bottom: 20px; text-align: center;">
        <label>Start Date:</label>
        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
        <label>End Date:</label>
        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
        <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
        <button type="submit" class="btn btn-apply">Apply</button>
        <a href="order-history.php" class="btn btn-clear">Clear</a>
       <button type="submit" name="export" value="csv" class="btn btn-export" style="cursor:pointer;"> <i class="fas fa-file-export"></i> Export</button>
    </form>
    <!-- Payment or Registration Table -->
    <?php if ($status === 'registration'): ?>
        <h3>Registered Users (Without Payment)</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name / Email / Phone</th>
                    <th>Organization / Designation</th>
                    <th>Questions</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                if ($regResult->num_rows > 0): ?>
                    <?php while ($row = $regResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td> <?= $row['name'] ?? 'N/A' ?><br><?= $row['email'] ?? 'N/A' ?><br><?= $row['contact_number'] ?? 'N/A' ?></td>
                            <td><?= $row['organization_name'] ?? 'N/A' ?><br><?= $row['designation'] ?? 'N/A' ?></td>
                            <td><?= nl2br($row['questions']) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                        </tr>
                    <?php $i++; endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No registration data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h3>Payment Data</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Order Id</th>
                    <th>Amount</th>
                    <th>Order Status</th>
                    <th>Date</th>
                    <th>Status Message</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo ucwords($row['name']) . '<br>' . $row['email'] . '<br>' . $row['phone'] ?></td>
                            <td><?php echo $row['order_id'] ?></td>
                            <td><?php echo $row['amount'] . ' ' . $row['currency'] ?></td>
                            <td class="<?php echo $row['order_status'] ?>"><?php echo $row['order_status'] ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['date'])) ?></td>
                            <td><?php echo $row['status_message'] ?? 'NA' ?></td>
                        </tr>
                    <?php $i++; endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No payment data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>

</html>