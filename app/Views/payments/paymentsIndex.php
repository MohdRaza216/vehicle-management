<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/toastr.min.css">

</head>

<body>

    <div class="container mt-4">
        <h2>Payments</h2>

        <button class="btn btn-primary mb-3" id="addPaymentBtn">Add Payment</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Customer Name</th>
                    <th>Vehicle Name</th>
                    <th>Amount Paid</th>
                    <th>Pending Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="paymentsTableBody">

            </tbody>
        </table>
    </div>

    <!-- Add/Edit Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Add Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPaymentForm">
                        <input type="hidden" id="paymentId" name="payment_id">

                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select class="form-select" id="customer_id" name="customer_id">
                                <option value="">Select Customer</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['id'] ?>"><?= $customer['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-danger" id="customerError"></div>
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_id" class="form-label">Vehicle</label>
                            <select class="form-select" id="vehicle_id" name="vehicle_id">
                                <option value="">Select Vehicle</option>
                                <?php foreach ($vehicles as $vehicle): ?>
                                    <option value="<?= $vehicle['id'] ?>" data-price="<?= $vehicle['price'] ?>">
                                        <?= $vehicle['name'] ?> - <?= $vehicle['model'] ?> (<?= $vehicle['price'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-danger" id="vehicleError"></div>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount Paid</label>
                            <input type="number" class="form-control" id="amount" name="amount">
                            <div class="text-danger" id="amountError"></div>
                        </div>

                        <div class="mb-3">
                            <label for="pending_amount" class="form-label">Pending Amount</label>
                            <input type="text" class="form-control" id="pending_amount" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="savePaymentBtn" class="btn btn-success">Save Payment</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script src="<?= base_url() ?>/assets/js/toastr.min.js"></script>

    <script>
        $(document).ready(function () {
            loadPayments();

            function loadPayments() {
                $.ajax({
                    url: "<?= site_url('payments/fetch') ?>",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        $("#paymentsTableBody").html(response.html);
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            }



            $("#addPaymentBtn").click(function () {
                $("#paymentModalLabel").text("Add Payment");
                $("#addPaymentForm")[0].reset();
                $("#paymentId").val('');
                $("#paymentModal").modal("show");
            });

            $("#vehicle_id").change(function () {
                let price = $(this).find(":selected").data("price");
                let paid = $("#amount").val();
                let pending = price - (paid ? parseFloat(paid) : 0);
                $("#pending_amount").val(pending);
            });

            $('#savePaymentBtn').click(function (e) {
                e.preventDefault();

                let paymentId = $("#paymentId").val();
                let url = paymentId ? "<?= site_url('payments/update') ?>/" + paymentId : "<?= site_url('payments/store') ?>";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: $('#addPaymentForm').serialize(),
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            toastr.success(response.message);
                            $('#paymentModal').modal('hide');
                            $('#addPaymentForm')[0].reset();
                            loadPayments();
                        } else if (response.status === "error") {
                            $.each(response.errors, function (key, value) {
                                toastr.error(value);
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                        toastr.error("Something went wrong!");
                    }
                });
            });



            $(document).on("click", ".editPayment", function () {
                let id = $(this).data("id");

                $.ajax({
                    url: "<?= site_url('payments/edit') ?>/" + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $("#paymentId").val(data.id);
                        $("#customer_id").val(data.customer_id);
                        $("#vehicle_id").val(data.vehicle_id);
                        $("#amount").val(data.amount);
                        $("#pending_amount").val(data.pending_amount);
                        $("#paymentModalLabel").text("Edit Payment");
                        $("#paymentModal").modal("show");
                    }
                });
            });

            $(document).on("click", ".deletePayment", function () {
                let id = $(this).data("id");

                if (confirm("Are you sure you want to delete this payment?")) {
                    $.ajax({
                        url: "<?= site_url('payments/delete') ?>/" + id,
                        type: "POST",
                        success: function (response) {
                            toastr.success(response.message);
                            loadPayments();
                        }
                    });
                }
            });
        });

    </script>
</body>

</html>