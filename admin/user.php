<?php
session_start(); // เริ่ม session
require_once 'config.php'; // เชื่อมต่อฐานข้อมูล
if (!isset($_GET['id'])) { // ตรวจสอบว่ามีการส่ง ID ของสินค้าเข้ามาหรือไม่
    header("Location: index.php");
    exit;
}
$product_id = $_GET['id'];
$stmt = $conn->prepare("SELECT p.*, c.category_name
FROM products p
LEFT JOIN categories c ON p.category_id = c.category_id
WHERE p.product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo "<h3>ไม่พบสินค้าที่คุณต้องการ</h3>";
    exit;
}
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<?php
session_start();
require '../config.php'; // เชื่อมต่อฐานข้อมูล
require_once 'auth_admin.php';
// ลบสมำชกิ
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    // ป้องกันลบตัวเอง
    if ($user_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'member'");
        $stmt->execute([$user_id]);
    }
    header("Location: users.php");
    exit;
}
// ดึงข้อมูลสมาชิก
$stmt = $conn->prepare("SELECT * FROM users WHERE role = 'member' ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดกำรสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">
    <h2>จัดกำรสมาชิก</h2>
    <a href="index.php" class="btn btn-secondary mb-3">← กลับหน้าผู้ดูแล</a>
    <?php if (count($users) === 0): ?>
        <div class="alert alert-warning">ยังไม่มีสมาชิกในระบบ</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ชื่อผู้ใช้</th> ้
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล</th>
                    <th>วันที่สมัคร</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['created_at'] ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-warning">แก้ไข
                            </a>
                            <a href="users.php?delete=<?= $user['user_id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('คุณต้องการลบสมาชิกนี้หรือไม่?')">ลบ</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายละเอียดสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">
    <a href="index.php" class="btn btn-secondary mb-3">← กลับหน้ารายการสินค้า</a>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h3>
            <h6 class="text-muted">หมวดหมู่: <?= htmlspecialchars($product['category_name']) ?></h6>
            <p class="card-text mt-3"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <p><strong>ราคา:</strong> <?= number_format($product['price'], 2) ?> บาท</p>
            <p><strong>คงเหลือ:</strong> <?= $product['stock'] ?> ชิ้น</p>
            <?php if ($isLoggedIn): ?>
                <form action="cart.php" method="post" class="mt-3">
                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                    <label for="quantity">จำนวน:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?=
                        $product['stock'] ?>" required>
                    <button type="submit" class="btn btn-success">เพิ่มในตะกร้า</button>
                </form>
            <?php else: ?>
                <div class="alert alert-info mt-3">กรุณาเข้าสู่ระบบเพื่อสั่งซื้อสินค้า</div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>