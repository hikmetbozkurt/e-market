<?php
require "./db.php";

$products = $db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC); 

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['addProduct_id'])) {
    $product_id = $_GET['addProduct_id'];
    
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1;
    } else {
        $_SESSION['cart'][$product_id]++;
    }

    $showCart = true; // Show the cart after adding
}

if (isset($_GET['removeItem'])) {
    $productId = $_GET['removeItem'];

    if ($_SESSION['cart'][$productId] > 1) {
        $_SESSION['cart'][$productId]--;
    } else {
        unset($_SESSION['cart'][$productId]);
    }

    $showCart = true; 
}

$showCart = $showCart ?? isset($_GET['show_cart']); 

function grandTotal($product) {
    $total = 0;

    if(isset($_SESSION['cart'][$product['id']])){
        $total = $_SESSION['cart'][$product['id']] * $product['price'];
    }

    return $total;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">

    <title>e-Market</title>
</head>
<body>
    
    <h1>e-Market</h1>


<div class="product-container">
        <?php foreach ($products as $product): ?>

            <div class="product">
            <img src="img/<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
            <p><strong><?php echo $product['title']; ?></strong></p>
            <p><strong><?php echo $product['price']; ?> TL</strong></p>


             <a href="?addProduct_id=<?= $product['id'] ?>">
            <span class="btn">Add</span>
            </a>


    
    </div>
        <?php endforeach; ?>
</div>

    <div class="cart-container">
        <form method="get">
            <?php if (!$showCart): ?>
                <button type="submit" name="show_cart">Show Cart &#x1F53B; </button>
            <?php elseif ($showCart): ?>
                <button type="submit" name="hide_cart">Hide Cart &#x1F53A; </button>
            <?php endif; ?>
        </form>
        <?php if ($showCart): ?>
            <h2>Shopping Cart</h2>
            <div class="cart-items">
                <?php
                if (!empty($_SESSION['cart'])) {
                    //var_dump($_SESSION['cart']);
                    echo '<table>';
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    foreach ($products as $product) {
                        if ($product['id'] == $product_id) {
                            echo '<tr>';
                            echo '<td><img src="img/' . $product['image'] . '" alt="' . $product['title'] . '"></td>';
                            echo '<td><strong>' . $product['title'] . '</strong></td>';
                            echo '<td><strong>' . $product['price'] . ' TL</strong></td>';;
                            echo '<td>' . "X" . '</td>';
                            echo '<td>' . $quantity . '</td>';
                            
                            echo '<td><strong>Total: '. grandTotal($product). ' TL</strong></td>';
                            echo '<td><a href="?removeItem=' . $product_id .'"> &#x274C; </a></td>';

                            echo '</tr>';
                        }
                    }
                }
                echo '</table>';
                } else {
                    echo '<p>Cart is empty.</p>';
                }
                ?>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>