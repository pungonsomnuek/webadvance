<?php
    session_start(); // เริ่มต้น session เพื่อให้สามารถใช้งานตัวแปร $_SESSION ได้
?>

<!DOCTYPE html>
<html lang="en"> <!-- กำหนดว่าเป็น HTML5 และใช้ภาษาอังกฤษ -->
<head>
    <meta charset="UTF-8"> <!-- กำหนดชุดตัวอักษรเป็น UTF-8 (รองรับภาษาไทย) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ให้หน้าเว็บ responsive รองรับมือถือ -->
    <title>หน้าหลัก</title> <!-- ชื่อแท็บของหน้าเว็บ -->

    <!-- Bootstrap CDN (ดึง CSS ของ Bootstrap จาก CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f4f6fa; /* สีพื้นหลังของเว็บ */
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; /* ฟอนต์ที่ใช้ */
        }
        .navbar {
            background-color: #0d47a1; /* สีพื้นหลังของ Navbar */
        }
        .navbar-brand, .nav-link, .navbar-text {
            color: #fff !important; /* เปลี่ยนสีตัวอักษรใน Navbar เป็นสีขาว */
        }
        .container {
            margin-top: 50px; /* เว้นระยะด้านบน */
        }
        .card {
            border-radius: 15px; /* มุมโค้งของ card */
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1); /* เพิ่มเงา card */
        }
        .card-header {
            background-color: #c01565ff; /* สีหัว card */
            color: #fff; /* สีข้อความหัว card */
            font-weight: 600; /* ตัวหนา */
            font-size: 18px; /* ขนาดตัวอักษร */
            border-top-left-radius: 15px; /* มุมซ้ายบนโค้ง */
            border-top-right-radius: 15px; /* มุมขวาบนโค้ง */
        }
        .btn-logout {
            background-color: #12d0d7ff; /* สีปุ่มออกจากระบบ */
            color: #fff; /* สีตัวอักษร */
            border-radius: 8px; /* มุมโค้งของปุ่ม */
            padding: 8px 20px; /* ขนาดปุ่ม */
            text-decoration: none; /* เอาเส้นใต้ลิงก์ออก */
            transition: 0.3s; /* ทำให้เวลา hover มี transition */
        }
        .btn-logout:hover {
            background-color: #82be1cff; /* สีปุ่มเมื่อ hover */
            color: #fff;
        }
    </style>
</head>
<body>


       <!-- Navbar -->
    <nav class="navbar navbar-expand-lg"> <!-- แถบเมนูด้านบน -->
      <div class="container-fluid">
        <div class="d-flex">
            <span class="navbar-text me-3">
                <!-- แสดง username และ role ของผู้ใช้จาก session -->
                <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)
            </span>
            <!-- ปุ่มออกจากระบบ -->
            <a href="logout.php" class="btn-logout">ออกจากระบบ</a>
        </div>
      </div>
    </nav>


        <!-- Main Content -->
    <div class="container"> <!-- ส่วนเนื้อหาหลัก -->
        <div class="card mt-4"> <!-- การ์ดแสดงข้อมูล -->
            <div class="card-header">
                หน้าหลัก <!-- หัวข้อของการ์ด -->
            </div>
            <div class="card-body text-center">
                <h3 class="mb-3">ยินดีต้อนรับสู่หน้าหลัก</h3>
                <!-- แสดงชื่อผู้ใช้จาก session -->
                <p class="lead">ผู้ใช้: <b><?= htmlspecialchars($_SESSION['username']) ?></b> (<?= $_SESSION['role'] ?>)</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
