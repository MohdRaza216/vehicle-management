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
        <h2>Vehicle Management</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
            Add Vehicle
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Vehicle Name</th>
                    <th>Vehicle Model</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($vehicles)): ?>
                    <?php $i = 1;
                    foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= esc($vehicle['name']) ?></td>
                            <td><?= esc($vehicle['model']) ?></td>
                            <td><?= esc($vehicle['price']) ?></td>
                            <td><?= esc($vehicle['status']) ?></td>
                            <td>
                                <button class="btn btn-primary editVehicleBtn" data-id="<?= $vehicle['id'] ?>"
                                    data-name="<?= $vehicle['name'] ?>" data-model="<?= $vehicle['model'] ?>"
                                    data-price="<?= $vehicle['price'] ?>" data-bs-toggle="modal"
                                    data-bs-target="#editVehicleModal">Edit</button>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No vehicles found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Vehicle Modal -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVehicleModalLabel">Add Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addVehicleForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Vehicle Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Vehicle Model</label>
                            <input type="text" class="form-control" id="model" name="model" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Available">Available</option>
                                <option value="Pending">Pending</option>
                                <option value="Booked">Booked</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Vehicle Modal -->
    <div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVehicleModalLabel">Edit Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editVehicleForm">
                    <div class="modal-body">
                        <input type="hidden" id="editVehicleId" name="id">

                        <div class="mb-3">
                            <label for="editVehicleName" class="form-label">Vehicle Name</label>
                            <input type="text" class="form-control" id="editVehicleName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="editVehicleModel" class="form-label">Vehicle Model</label>
                            <input type="text" class="form-control" id="editVehicleModel" name="model" required>
                        </div>

                        <div class="mb-3">
                            <label for="editVehiclePrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="editVehiclePrice" name="price" required>
                        </div>

                        <div class="mb-3">
                            <label for="editVehicleStatus" class="form-label">Status</label>
                            <select class="form-control" id="editVehicleStatus" name="status">
                                <option value="Available">Available</option>
                                <option value="Pending">Pending</option>
                                <option value="Booked">Booked</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateVehicleBtn">Update Vehicle</button>
                    </div>
                </form>
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


    <!-- Flash Messages -->
    <script>
        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "preventDuplicates": true
        };

        $(document).ready(function () {
            $("#addVehicleForm").submit(function (e) {
                e.preventDefault();

                $.ajax({
                    url: "<?= site_url('vehicles/add') ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            toastr.success(response.message, "Success");
                            setTimeout(() => location.reload(), 1500);
                        } else if (response.status === "error") {
                            $(".error-message").remove();
                            $.each(response.errors, function (field, message) {
                                $("#" + field).after('<small class="text-danger error-message">' + message + '</small>');
                            });
                        }
                    },
                    error: function () {
                        toastr.error("Something went wrong. Please try again.", "Error");
                    }
                });
            });

            $('.editVehicleBtn').click(function () {
                var vehicleId = $(this).data('id');

                $.ajax({
                    url: '<?= base_url('vehicles/edit/') ?>' + vehicleId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            var vehicle = response.data;
                            $('#editVehicleId').val(vehicle.id);
                            $('#editVehicleName').val(vehicle.name);
                            $('#editVehicleModel').val(vehicle.model);
                            $('#editVehiclePrice').val(vehicle.price);
                            $('#editVehicleStatus').val(vehicle.status);

                            $('#editVehicleModal').modal('show'); // Open the modal
                        } else {
                            toastr.error('Vehicle not found.');
                        }
                    },
                    error: function () {
                        toastr.error('Something went wrong.');
                    }
                });
            });

            $('#updateVehicleBtn').click(function (e) {
                e.preventDefault();

                let id = $('#editVehicleId').val();
                let name = $('#editVehicleName').val();
                let model = $('#editVehicleModel').val();
                let price = $('#editVehiclePrice').val();
                let status = $('#editVehicleStatus').val();

                $.ajax({
                    url: 'vehicles/update',
                    type: 'POST',
                    data: {
                        id: id,
                        name: name,
                        model: model,
                        price: price,
                        status: status
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            toastr.success(response.message, "Success");

                            let row = $('button[data-id="' + id + '"]').closest('tr');
                            row.find('td:eq(1)').text(name);
                            row.find('td:eq(2)').text(model);
                            row.find('td:eq(3)').text(price);
                            row.find('td:eq(4)').text(status);

                            setTimeout(function () {
                                $('#editVehicleModal').modal('hide');
                            }, 1500);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function () {
                        toastr.error("Something went wrong.");
                    }
                });
            });

        });
    </script>

</body>

</html>