<?php
require_once 'config.php'; // เรียกไฟล์ config.php เพื่อเชื่อมต่อฐานข้อมูล (PDO)

$errors = []; // สร้าง array สำหรับเก็บข้อความ error

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // ตรวจสอบว่ามีการส่งฟอร์มแบบ POST มาหรือไม่
    // รับค่าจากฟอร์ม และ trim() เพื่อตัดช่องว่างหัว-ท้ายออก
    $username = trim($_POST['username']);     
    $fullname = trim($_POST['fullname']);     
    $email = trim($_POST['email']);           
    $password = $_POST['password'];           
    $confirmpassword = $_POST['confirmpassword']; 

    // ✅ ตรวจสอบข้อมูลที่กรอกเข้ามา
    if (empty($username) || empty($fullname) || empty($email) || empty($password) || empty($confirmpassword)) {
        $errors[] = "กรุณากรอกข้อมูลให้ครบถ้วน"; // ถ้ามีช่องว่างเปล่า
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "กรุณากรอกอีเมลให้ถูกต้อง"; // ถ้า email ไม่ถูกต้อง
    } elseif ($password !== $confirmpassword) {
        $errors[] = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน"; // ถ้า password ไม่ตรงกัน
    } else {
        // ✅ ตรวจสอบ username หรือ email ซ้ำในฐานข้อมูล
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql); // เตรียม statement
        $stmt->execute([$username, $email]); // execute โดยแทนค่า ? ด้วย $username และ $email

        if ($stmt->rowCount() > 0) { // ถ้ามีข้อมูลใน DB ที่ซ้ำ
            $errors[] = "ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้ไปแล้ว";
        }
    }

    // ✅ ถ้าไม่มี error -> บันทึกลง DB
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่านด้วย bcrypt
        $sql = "INSERT INTO users(username, full_name, email, password, role) VALUES (?, ?, ?, ?, 'member')";
        $stmt = $conn->prepare($sql); // เตรียม statement
        $stmt->execute([$username, $fullname, $email, $hashedPassword]); // execute และบันทึกลง DB

        // ✅ สมัครเสร็จ -> redirect ไปหน้า login พร้อมส่งค่า register=success
        header("Location: login.php?register=success");
        exit(); // หยุดการทำงานของ script
    }
}
?>

<!DOCTYPE html>
<html lang="th"> <!-- กำหนดว่าเว็บนี้ใช้ภาษาไทย -->
<head>
    <meta charset="UTF-8"> <!-- เข้ารหัสเป็น UTF-8 (รองรับภาษาไทย) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ทำให้ responsive รองรับมือถือ -->
    <title>สมัครสมาชิก</title> <!-- ชื่อแท็บ -->

    <!-- Bootstrap CSS สำหรับตกแต่ง UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(to right, #ff80ab, #ff4081); /* พื้นหลัง gradient ชมพู */
            font-family: 'Segoe UI', sans-serif; /* ฟอนต์ */
            display: flex; /* ใช้ flexbox */
            justify-content: center; /* จัดกลางแนวนอน */
            align-items: center; /* จัดกลางแนวตั้ง */
            height: 100vh; /* สูงเต็มหน้าจอ */
        }
        .register-card {
            max-width: 480px; /* ความกว้างสูงสุด */
            width: 100%; 
            padding: 25px;
            border-radius: 15px; /* มุมโค้ง */
            background: #fff; /* พื้นหลังขาว */
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2); /* เงา */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* เอฟเฟกต์ hover */
        }
        .register-card:hover {
            transform: translateY(-8px); /* ยกการ์ดขึ้นเล็กน้อยเวลา hover */
            box-shadow: 0px 10px 25px rgba(0,0,0,0.3); /* เพิ่มเงา */
        }
        .register-card h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #e91e63; /* สีชมพูเข้ม */
        }
        .toggle-password {
            cursor: pointer; /* ทำให้เป็น pointer เวลา hover */
            position: absolute;
            right: 15px;
            top: 38px;
            color: #888;
        }
    </style>
</head>
<body>


<div class="register-card">
    <h2>สมัครสมาชิก</h2>

    <!-- ถ้ามี error จะแสดงใน alert -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- ฟอร์มสมัครสมาชิก -->
    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">ชื่อผู้ใช้</label>
            <input type="text" name="username" id="username" 
                   class="form-control" placeholder="กรุณากรอกชื่อผู้ใช้"
                   value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="fullname" class="form-label">ชื่อ-สกุล</label>
            <input type="text" name="fullname" id="fullname" 
                   class="form-control" placeholder="กรุณากรอกชื่อ-สกุล"
                   value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" name="email" id="email" 
                   class="form-control" placeholder="กรุณากรอกอีเมล" required>
        </div>
        <!-- (ยังมีช่อง password, confirm password ต่อจากนี้) -->
    </form>
</div>
