<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

    <h2>Customer List</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile Number</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach($customers as $customer): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($customer['name']) ?></td>
                    <td><?= esc($customer['email']) ?></td>
                    <td><?= esc($customer['mobile_number']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
