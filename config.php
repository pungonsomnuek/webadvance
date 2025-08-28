<?php 
// connect database ด้วย PDO

$host = "localhost";       // กำหนด host ของฐานข้อมูล (localhost = เครื่องเดียวกับ server PHP)
$username = "root";        // กำหนดชื่อผู้ใช้สำหรับเชื่อมต่อฐานข้อมูล (ปกติค่า default ของ XAMPP/MAMP คือ root)
$password = "";            // กำหนดรหัสผ่านของผู้ใช้ (ค่า default ของ root มักจะว่าง "")
$database = "online_shop"; // กำหนดชื่อฐานข้อมูลที่จะใช้งาน

$dns = "mysql:host=$host;dbname=$database"; // สร้าง DSN (Data Source Name) เพื่อบอก PDO ว่าจะเชื่อมต่อ MySQL ที่ host ไหนและ database ไหน

try { 
    // $conn = new PDO("mysql:host=$host;dbname=$database", $username ,$password);
    // บรรทัดด้านบนเป็นวิธีเขียนอีกแบบ (ถูกคอมเมนต์ไว้)

    $conn = new PDO($dns, $username ,$password); // สร้างตัวแปร $conn เป็น object ของ PDO เพื่อเชื่อมต่อฐานข้อมูล

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    // ตั้งค่า attribute ของ PDO ให้เวลามี error โยนออกมาเป็น Exception (ทำให้จับ error ได้ง่าย)

    // echo "PDO Connected successfully"; 
    // ถ้าต้องการทดสอบว่าสำเร็จก็สามารถ echo ออกมาได้ (ตอนนี้คอมเมนต์ไว้)

} catch(PDOException $e) { // ถ้ามีข้อผิดพลาด (PDOException)
    echo "PDO Connection failed: " . $e->getMessage(); 
    // แสดงข้อความ error ว่าเชื่อมต่อไม่สำเร็จ พร้อมสาเหตุของ error
}

?>
