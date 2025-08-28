<?php
session_start(); // เริ่มต้นการทำงาน session เพื่อเก็บค่าต่าง ๆ ของผู้ใช้ เช่น user_id, username, role
require_once 'config.php'; // เรียกไฟล์ config.php (เชื่อมต่อฐานข้อมูล PDO)

$error = ''; // ตัวแปรเก็บข้อความ error (เริ่มต้นเป็นค่าว่าง)

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // เช็คว่าเป็นการ submit ฟอร์มแบบ POST หรือไม่
    // รับค่าจากฟอร์ม
    $usernameOremail = trim($_POST['username_or_email']); // รับค่า username หรือ email และตัดช่องว่างหัว-ท้ายออก
    $password = $_POST['password']; // รับค่ารหัสผ่านจากฟอร์ม

    // ตรวจสอบข้อมูลจาก DB
    $sql = "SELECT * FROM users WHERE (username = ? OR email = ?)"; // query ดึงข้อมูลผู้ใช้ที่มี username หรือ email ตรงกับที่กรอกมา
    $stmt = $conn->prepare($sql); // เตรียม statement แบบ PDO ป้องกัน SQL Injection
    $stmt->execute([$usernameOremail, $usernameOremail]); // execute โดยส่งค่าไปแทน ? (ทั้ง username และ email ใช้ค่าที่กรอกมา)
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // ดึงผลลัพธ์เป็น array แบบ key => value

    // ตรวจสอบว่าเจอ user และ verify password
    if ($user && password_verify($password, $user['password'])) { 
        // ถ้ารหัสผ่านถูกต้อง -> เก็บข้อมูล user ลงใน session
        $_SESSION['user_id']  = $user['user_id'];   // เก็บ id ของ user
        $_SESSION['username'] = $user['username']; // เก็บชื่อผู้ใช้
        $_SESSION['role']     = $user['role'];     // เก็บสิทธิ์ (role) เช่น admin หรือ user

        // ถ้า role เป็น admin ให้ไปหน้า admin
        if ($user['role'] === 'admin') {
            header("Location: admin/index.php"); // redirect ไปหน้า admin
        } else {
            header("Location: index.php"); // redirect ไปหน้าหลักสำหรับผู้ใช้ทั่วไป
        }
        exit(); // หยุดการทำงานของ script หลัง redirect
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"; // ถ้าไม่เจอ user หรือรหัสผ่านผิด
    }
}
?>

<!DOCTYPE html>
<html lang="th"> <!-- เอกสาร HTML กำหนดภาษาไทย -->
<head>
    <meta charset="UTF-8"> <!-- กำหนด encoding UTF-8 รองรับภาษาไทย -->
    <title>เข้าสู่ระบบ</title> <!-- ชื่อแท็บเบราว์เซอร์ -->

    <!-- Bootstrap CSS สำหรับตกแต่ง UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #0d6efd; /* พื้นหลังสีฟ้า */
            display: flex; /* ใช้ flex จัดให้อยู่ตรงกลาง */
            justify-content: center;
            align-items: center;
            height: 100vh; /* เต็มความสูงหน้าจอ */
        }
        .login-card {
            width: 100%;
            max-width: 420px; /* จำกัดขนาดกว้างสุดของฟอร์ม */
        }
    </style>
</head>
<body>


<div class="card login-card shadow-lg rounded-4"> <!-- กล่อง card สำหรับ login -->
    <div class="card-body p-4">
        
        <!-- ถ้าสมัครสมาชิกเสร็จแล้ว (register=success) -->
        <?php if (isset($_GET['register']) && $_GET['register'] === 'success'): ?>
            <div class="alert alert-success">สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ</div>
        <?php endif; ?>

        <!-- ถ้ามี error จะแสดงข้อความ error -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <h3 class="card-title text-center mb-4">เข้าสู่ระบบ</h3> <!-- หัวข้อ -->

        <!-- ฟอร์ม login -->
        <form method="post">
            <div class="mb-3">
                <label for="username_or_email" class="form-label">ชื่อผู้ใช้หรืออีเมล</label>
                <input type="text" name="username_or_email" id="username_or_email" 
                       class="form-control" placeholder="กรอกชื่อผู้ใช้หรืออีเมล" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" name="password" id="password" 
                       class="form-control" placeholder="กรอกรหัสผ่าน" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button> <!-- ปุ่มล็อกอิน -->
            <a href="register.php" class="btn btn-link d-block text-center mt-2">สมัครสมาชิก</a> <!-- ลิงก์ไปสมัครสมาชิก -->
        </form>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
