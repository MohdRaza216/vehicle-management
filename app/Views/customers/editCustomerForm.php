<form action="<?= base_url('customers/update/'.$customer['id']) ?>" method="post">
    <div class="mb-3">
        <label for="name">Name:</label>
        <input type="text" name="name" class="form-control" value="<?= esc($customer['name']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="email">Email:</label>
        <input type="email" name="email" class="form-control" value="<?= esc($customer['email']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="mobile_number">Mobile Number:</label>
        <input type="text" name="mobile_number" class="form-control" value="<?= esc($customer['mobile_number']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
