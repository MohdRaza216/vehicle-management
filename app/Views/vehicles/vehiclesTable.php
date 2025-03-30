<?php foreach ($vehicles as $vehicle) : ?>
<tr>
    <td><?= esc($vehicle['id']); ?></td>
    <td><?= esc($vehicle['name']); ?></td>
    <td><?= esc($vehicle['model']); ?></td>
    <td><?= esc($vehicle['price']); ?></td>
    <td><?= esc($vehicle['status']); ?></td>
    <td><?= esc($vehicle['created_at']); ?></td>
    <td><?= esc($vehicle['updated_at']); ?></td>
    <td>
        <button class="btn btn-warning editVehicle" data-id="<?= $vehicle['id']; ?>">Edit</button>
        <button class="btn btn-danger deleteVehicle" data-id="<?= $vehicle['id']; ?>">Delete</button>
    </td>
</tr>
<?php endforeach; ?>
