# Re-execute code after kernel reset to restore output.
catalog_card = '''
<div class="card text-center p-3 h-100 d-flex flex-column">
    <a href="product.php?id=<?= $row['product_id'] ?>" class="text-decoration-none text-dark">
        <img src="<?= htmlspecialchars($row['image_path']) ?>" class="img-fluid mb-2" alt="<?= htmlspecialchars($row['name']) ?>">
        <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
        <p class="card-text">Compat: <?= htmlspecialchars($row['compatibility']) ?></p>
        <p class="fw-bold">$<?= number_format($row['price'], 2) ?></p>
    </a>
    <div class="mt-auto">
        <div class="d-flex justify-content-center gap-2">
            <a href="cart.php?add=<?= $row['product_id'] ?>" class="btn btn-sm btn-success">Add to Cart</a>
            <a href="wishlist.php?add=<?= $row['product_id'] ?>" class="btn btn-sm btn-outline-secondary">♡ Wishlist</a>
        </div>
    </div>
</div>
'''

index_card = '''
<div class="card text-center p-3 h-100 d-flex flex-column">
    <a href="product.php?id=<?= $row['product_id'] ?>" class="text-decoration-none text-dark">
        <img src="<?= htmlspecialchars($row['image_path']) ?>" class="img-fluid mb-2" alt="<?= htmlspecialchars($row['name']) ?>">
        <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
        <p class="fw-bold">$<?= number_format($row['price'], 2) ?></p>
    </a>
    <div class="mt-auto">
        <div class="d-flex justify-content-center gap-2">
            <a href="cart.php?add=<?= $row['product_id'] ?>" class="btn btn-sm btn-success">Add to Cart</a>
            <a href="wishlist.php?add=<?= $row['product_id'] ?>" class="btn btn-sm btn-outline-secondary">♡ Wishlist</a>
        </div>
    </div>
</div>
'''

wishlist_card = '''
<div class="card h-100 text-center p-3 d-flex flex-column">
    <a href="product.php?id=<?= $item['product_id'] ?>" class="text-decoration-none text-dark">
        <img src="<?= htmlspecialchars($item['image_path']) ?>" class="img-fluid mb-2" alt="<?= htmlspecialchars($item['name']) ?>">
        <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
        <p class="fw-bold">$<?= number_format($item['price'], 2) ?></p>
    </a>
    <div class="mt-auto">
        <div class="d-flex justify-content-center gap-2">
            <a href="product.php?id=<?= $item['product_id'] ?>" class="btn btn-outline-primary btn-sm">View</a>
            <a href="wishlist.php?remove=<?= $item['product_id'] ?>" class="btn btn-outline-danger btn-sm">Remove</a>
        </div>
    </div>
</div>
'''

import pandas as pd
import ace_tools as tools

df = pd.DataFrame({
    "File": ["catalog.php", "index.php", "wishlist.php"],
    "Card HTML": [catalog_card, index_card, wishlist_card]
})

tools.display_dataframe_to_user(name="Product Card Code Blocks", dataframe=df)
