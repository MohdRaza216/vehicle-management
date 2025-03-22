<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>

<body>
    <div class="container mt-5">
        <h2>Customer List</h2>
        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add
            Customer</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $customer['name'] ?></td>
                        <td><?= $customer['email'] ?></td>
                        <td><?= $customer['mobile_number'] ?></td>
                        <td>
                            <button class="btn btn-primary editBtn" data-id="<?= $customer['id'] ?>"
                                data-name="<?= $customer['name'] ?>" data-email="<?= $customer['email'] ?>"
                                data-mobile="<?= $customer['mobile_number'] ?>" data-bs-toggle="modal"
                                data-bs-target="#editCustomerModal">Edit</button>

                            <form action="/vehicle-management/public/customers/delete/<?= $customer['id'] ?>" method="post"
                                style="display:inline;">
                                <button type="button" class="btn btn-danger"
                                    onclick="confirmDelete(<?= $customer['id'] ?>)">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/vehicle-management/public/customers/store" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobile_number">Mobile Number:</label>
                            <input type="text" name="mobile_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCustomerForm" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editCustomerId">
                        <div class="mb-3">
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="editCustomerName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="editCustomerEmail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobile_number">Mobile Number:</label>
                            <input type="text" name="mobile_number" id="editCustomerMobile" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <!-- jQuery (Toastr needs it) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Edit Modal Script -->
    <script>
        $(document).ready(function () {
            $('.editBtn').on('click', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const email = $(this).data('email');
                const mobile = $(this).data('mobile');

                $('#editCustomerId').val(id);
                $('#editCustomerName').val(name);
                $('#editCustomerEmail').val(email);
                $('#editCustomerMobile').val(mobile);

                $('#editCustomerForm').attr('action', '/vehicle-management/public/customers/update/' + id);
            });
        });
    </script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form programmatically
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= base_url('customers/delete/') ?>' + id;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <!-- Flash Messages -->
    <script>
        // Toastr Styling Options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        }

        // Display success message
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success("<?= session()->getFlashdata('success') ?>");
        <?php endif; ?>

        // Display error message
        <?php if (session()->getFlashdata('error')): ?>
            toastr.error("<?= session()->getFlashdata('error') ?>");
        <?php endif; ?>
    </script>
</body>

</html>