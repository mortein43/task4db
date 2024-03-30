<?php
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">';
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "task4database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Помилка підключення до бази даних: " . $conn->connect_error);
}

$names = ["Ім'я 1", "Ім'я 2", "Ім'я 3", "Ім'я 4", "Ім'я 5", "Ім'я 6", "Ім'я 7", "Ім'я 8", "Ім'я 9", "Ім'я 10"];

for ($i = 0; $i < 10; $i++) {
    $name = $conn->real_escape_string($names[$i]);
    $check_query = "SELECT COUNT(*) as count FROM `user` WHERE `name` = '$name'";
    $result = $conn->query($check_query);
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        // Якщо запис не існує, виконуємо вставку
        $sql = "INSERT INTO `user` (`name`) VALUES ('$name')";
    }
}

$categories = ["Категорія 1", "Категорія 2", "Категорія 3"];

for ($i = 0; $i < 3; $i++) {
    $name = $conn->real_escape_string($categories[$i]);
    $check_query = "SELECT COUNT(*) as count FROM `category` WHERE `name` = '$name'";
    $result = $conn->query($check_query);
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $sql = "INSERT INTO `category` (`name`) VALUES ('$name')";

    }
}

$products = [
    ["Продукт 1", 10, 1], // name, price, idCategory
    ["Продукт 2", 20, 2],
    ["Продукт 3", 15, 1],
    ["Продукт 4", 5, 3],
    ["Продукт 5", 12, 2],
    ["Продукт 6", 8, 1],
    ["Продукт 7", 17, 3]
];

// Виконання 7 запитів вставки
for ($i = 0; $i < count($products); $i++) {
    $name = $conn->real_escape_string($products[$i][0]);
    $price = $products[$i][1];
    $idCategory = $products[$i][2];

    // Перевірка наявності записів з вказаним idCategory
    $check_query = "SELECT COUNT(*) as count FROM `product` WHERE `name` = '$name'";
    $result = $conn->query($check_query);
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        // Якщо записів з вказаним idCategory не знайдено, вставляємо новий запис
        $sql = "INSERT INTO `product` (`name`, `price`, `idCategory`) VALUES ('$name', '$price', '$idCategory')";
    }
}

/*$cartItems = [
    [1, 1], // userId, productId
    [1, 2],
    [2, 3],
    [2, 4],
    [3, 5],
    [3, 6],
    [4, 7],
    [4, 6],
    [5, 3],
    [5, 5]
];

// Виконання 10 запитів вставки
for ($i = 0; $i < count($cartItems); $i++) {
    $userId = $cartItems[$i][0];
    $productId = $cartItems[$i][1];

    // Вставка нового запису без перевірки наявності
    $sql = "INSERT INTO `cart` (`userId`, `productId`) VALUES ('$userId', '$productId')";

    if ($conn->query($sql) === TRUE) {
        echo "Запис у таблиці cart успішно вставлено<br>";
    } else {
        echo "Помилка вставки у таблицю cart запису: " . $conn->error . "<br>";
    }
}*/
echo "<h4>Завдання 5:</h4>";
echo "<p class='text-primary'>а) усі користувачі</p>";
$sql = "SELECT * FROM `user` ORDER BY id";
$result = $conn->query($sql);

// Перевірка результату запиту
echo "<table class='table table-striped col-3'>";
echo "<thead class='thead-dark'><tr><th>ID</th><th>Name</th></tr></thead><tbody>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"]. "</td><td>" . $row["name"]. "</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>У таблиці user немає записів</td></tr>";
}

echo "</table>";

echo "<p class='text-primary'>b) усі записи в корзині (виводити усю інформацію про
користувача, усю інформацію про продукт, усію інформацію про категорію)</p>";
$sql = "SELECT cart.*, user.*, product.name AS productName, category.name AS categoryName
        FROM cart
        INNER JOIN user ON cart.userId = user.id
        INNER JOIN product ON cart.productId = product.id
        INNER JOIN category ON product.idCategory = category.id";
$result = $conn->query($sql);

// Виведення результату
if ($result->num_rows > 0) {
    echo "<table class='table table-striped col-4'>";
    echo "<thead class='thead-dark'><tr><th>User</th><th>Product</th><th>Category</th></tr></thead><tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["productName"] . "</td>";
        echo "<td>" . $row["categoryName"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "У корзині немає записів";
}
echo "<p class='text-primary'>c) усі записи в корзині (Вивести ім’я користувача, назву
категорії та назву продукту)</p>";
$sql = "SELECT user.name AS userName, product.name AS productName, category.name AS categoryName
        FROM cart
        INNER JOIN user ON cart.userId = user.id
        INNER JOIN product ON cart.productId = product.id
        INNER JOIN category ON product.idCategory = category.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-striped col-4'>";
    echo "<thead class='thead-dark'><tr><th>User Name</th><th>Product Name</th><th>Category Name</th></tr></thead><tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["userName"] . "</td>";
        echo "<td>" . $row["productName"] . "</td>";
        echo "<td>" . $row["categoryName"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "У корзині немає записів";
}

echo "<p class='text-primary'>d) усіх продуктів вибраних в корзині одним користувачем
(Виводити усю інформацію про користувача, продукт
та категорію)</p>";

$user_id = 2; // Встановіть потрібний ID користувача тут

// Запит до бази даних
$sql = "SELECT user.name AS userName, product.name AS productName, category.name AS categoryName
        FROM cart
        INNER JOIN user ON cart.userId = user.id
        INNER JOIN product ON cart.productId = product.id
        INNER JOIN category ON product.idCategory = category.id
        WHERE user.id = $user_id"; // Фільтр за ID користувача

$result = $conn->query($sql);

// Виведення результатів
if ($result->num_rows > 0) {
    echo "<table class='table table-striped col-5'>";
    echo "<thead class='thead-dark'><tr><th>User Name</th><th>Product Name</th><th>Category Name</th></tr></thead><tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["userName"] . "</td>";
        echo "<td>" . $row["productName"] . "</td>";
        echo "<td>" . $row["categoryName"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "Користувач не має продуктів у корзині";
}

echo "<p class='text-primary'>e) назв категорій, продукти яких добавлені в корзину одним користувачем</p>";

$user_id = 2; // Встановіть потрібний ID користувача тут

// Запит до бази даних
$sql = "SELECT DISTINCT category.name AS categoryName
        FROM cart
        INNER JOIN user ON cart.userId = user.id
        INNER JOIN product ON cart.productId = product.id
        INNER JOIN category ON product.idCategory = category.id
        WHERE user.id = $user_id"; // Фільтр за ID користувача

$result = $conn->query($sql);

// Виведення результатів
if ($result->num_rows > 0) {
    echo "<table class='table table-striped col-2'>";
    echo "<thead class='thead-dark'><tr><th>Category Name</th></tr></thead><tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["categoryName"] . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "Користувач не має продуктів у корзині";
}

echo "<p class='text-primary'>f) інформацію про всіх користувачів, які купили один продукт</p>";

$product_name = "Продукт 3"; // Встановіть назву продукту тут

// Запит до бази даних
$sql = "SELECT user.id AS userId, user.name AS userName, COUNT(cart.userId) AS purchases
        FROM cart
        INNER JOIN user ON cart.userId = user.id
        INNER JOIN product ON cart.productId = product.id
        WHERE product.name = '$product_name'
        GROUP BY user.id, user.name";

$result = $conn->query($sql);

// Виведення результатів
if ($result->num_rows > 0) {
    echo "<h5>Інформація про користувачів, які купили продукт '$product_name':</h5>";
    echo "<table class='table table-striped col-5'>";
    echo "<thead class='thead-dark'><tr><th>User ID</th><th>User Name</th><th>Purchases</th></tr></thead><tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["userId"] . "</td>";
        echo "<td>" . $row["userName"] . "</td>";
        echo "<td>" . $row["purchases"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "Немає користувачів, які купили продукт '$product_name'";
}

echo "<p class='text-primary'>g) інформацію про про категорії, продуктів якої немає в користувача в корзині</p>";

$user_id = 1; // Встановіть ID користувача тут

// Запит до бази даних
$sql = "SELECT category.name AS categoryName, product.name AS productName
        FROM category
        CROSS JOIN product
        LEFT JOIN (
            SELECT DISTINCT cart.productId AS productId
            FROM cart
            WHERE cart.userId = $user_id
        ) AS userCart ON product.id = userCart.productId
        WHERE userCart.productId IS NULL";

$result = $conn->query($sql);

// Виведення результатів
if ($result->num_rows > 0) {
    echo "<h4>Інформація про категорії та продукти, яких немає в користувача в корзині:</h4>";
    echo "<table class='table table-striped col-5'>";
    echo "<thead class='thead-dark'><tr><th>Category Name</th><th>Product Name</th></tr></thead><tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["categoryName"] . "</td>";
        echo "<td>" . $row["productName"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "У користувача у корзині містяться всі категорії та продукти";
}

$conn->close();