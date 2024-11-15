<?php
session_start();
include('../connection.php');

// ตรวจสอบการเข้าสู่ระบบและระดับผู้ใช้
if (!isset($_SESSION['userid']) || $_SESSION['userlevel'] != 'a') {
    header("Location: logout.php");
    exit();
}

$userid = $_SESSION['userid'];

// ใช้ prepared statements เพื่อป้องกัน SQL Injection
$stmt = $conn->prepare("SELECT firstname, lastname, img_path FROM mable WHERE id = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();
$uploadedImage = !empty($user['img_path']) ? '../imgs/' . htmlspecialchars($user['img_path']) : '../imgs/default.jpg';

// ดึงข้อมูลผู้ใช้งาน
$user_query = "SELECT id, firstname, lastname FROM mable WHERE userlevel = 'm'";
$user_result = mysqli_query($conn, $user_query);

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_ids = $_POST['user_ids']; // รับเป็น array
    $job_title = mysqli_real_escape_string($conn, $_POST['job_title']);
    $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $due_time = mysqli_real_escape_string($conn, $_POST['due_time']);

    // ตรวจสอบการอัปโหลดไฟล์
    $file_name = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file = $_FILES['file'];
        $upload_directory = '../upload/';
        $file_name = basename($file['name']);


        // สร้างโฟลเดอร์ถ้าไม่อยู่
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0777, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $upload_directory . $file_name)) {
            die('Failed to move uploaded file.');
        }
    }


    // แทรกข้อมูลลงในฐานข้อมูล
    foreach ($user_ids as $user_id) {
        $user_id = mysqli_real_escape_string($conn, $user_id);
        $insert_query = "INSERT INTO assignments (admin_id, user_id, job_title, job_description, due_date, due_time, file_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iisssss", $userid, $user_id, $job_title, $job_description, $due_date, $due_time, $file_name);
        if (!$stmt->execute()) {
            die('Error: ' . $stmt->error);
        }
    }

    header("Location: ./admin_view_assignments.php");
    exit();
}

// ดึงข้อมูลงานที่เคยสั่งทั้งหมด
$assignments_query = "SELECT * FROM assignments WHERE admin_id = ?";
$stmt = $conn->prepare($assignments_query);
$stmt->bind_param("i", $userid);
$stmt->execute();
$assignments_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สั่งงานใหม่</title>
    <link href="../css/sidebar.css" rel="stylesheet">
    <link href="../css/navbar.css" rel="stylesheet">
    <link href="https://www.ppkhosp.go.th/images/logoppk.png" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            margin-top: 20px;
            overflow-x: auto;
        }

        #main {
            transition: margin-left .5s;
            padding: 16px;
            margin-left: 0;
        }

        .form-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 70vh;
            flex-direction: column;
            vertical-align: middle;
        }

        .form-box {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            background-color: transparent;
            border: none;
            border-bottom: 2px solid #727272;
            border-radius: 0;
            color: #000;
            font-size: 16px;
        }

        .form-control:focus {
            border-bottom: 2px solid #727272;
            outline: none;
            box-shadow: none;
        }

        .form-control option {
            background-color: transparent;
            color: #000;
        }

        .form-control option:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .btn {
            font-size: 18px;
            padding: 10px 20px;
            margin-top: 30px;
            border-radius: 30px;
            background-color: #1dc02b;
            color: #fff;
        }

        .btn:hover {
            background: #0a840a;
            color: #fff;
        }

        /* ปุ่มพื้นฐาน */
        .btn-worker {
            background-color: #28a745;
            /* สีเขียวสด */
            border: 2px solid #218838;
            /* สีขอบเข้ม */
            color: #fff;
            /* สีตัวอักษร */
            font-weight: bold;
            /* ตัวอักษรหนา */
            padding: 10px 20px;
            /* ขยายพื้นที่ในปุ่ม */
            border-radius: 8px;
            /* มุมโค้งมน */
            cursor: pointer;
            /* เปลี่ยนเคอร์เซอร์เป็นรูปมือ */
            transition: all 0.3s ease;
            /* เพิ่มเอฟเฟกต์ */
        }

        /* เมื่อเอาเมาส์วางบนปุ่ม */
        .btn-worker:hover {
            background-color: #218838;
            /* สีพื้นหลังเข้มขึ้น */
            border-color: #1e7e34;
            /* สีขอบเข้มขึ้น */
            transform: scale(1.05);
            /* ขยายปุ่มเล็กน้อย */
        }

        /* เมื่อกดปุ่ม */
        .btn-worker:active {
            background-color: #1e7e34;
            /* สีพื้นหลังเข้มที่สุด */
            border-color: #19692c;
            /* สีขอบเข้มขึ้น */
            transform: scale(0.95);
            /* ลดขนาดเล็กน้อย */
        }

        /* ปุ่มขนาดเล็ก */
        .btn-worker.small {
            font-size: 14px;
            padding: 5px 10px;
        }

        /* ปุ่มขนาดใหญ่ */
        .btn-worker.large {
            font-size: 18px;
            padding: 15px 30px;
        }

        /* จัดให้อยู่คนละบรรทัด */
        .mb-3 .form-label,
        .mb-3 .btn-worker {
            display: block;
            width: 25%;
        }

        /* ปรับข้อความให้ดูมีระยะห่าง */
        #selected-users {
            margin-top: 10px;
            /* เพิ่มระยะห่างระหว่างข้อความ */
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="navbar navbar-expand-lg navbar-dark">
        <button class="openbtn" id="menuButton" onclick="toggleNav()">☰</button>
        <div class="container-fluid">
            <span class="navbar-brand">สั่งงานใหม่</span>
        </div>
    </div>

    <div id="mySidebar" class="sidebar">
        <div class="user-info">
            <div class="circle-image">
                <img src="<?php echo $uploadedImage; ?>" alt="Uploaded Image">
            </div>
            <h1><?php echo htmlspecialchars($user['firstname']) . " " . htmlspecialchars($user['lastname']); ?></h1>
        </div>
        <a href="admin_page.php"><i class="fa-regular fa-clipboard"></i> แดชบอร์ด</a>
        <a href="emp.php"><i class="fa-solid fa-users"></i> รายชื่อพนักงานทั้งหมด</a>
        <a href="view_all_jobs.php"><i class="fa-solid fa-briefcase"></i> งานทั้งหมด</a>
        <a href="admin_assign.php"><i class="fa-solid fa-tasks"></i> สั่งงาน</a>
        <a href="admin_view_assignments.php"><i class="fa-solid fa-eye"></i> ดูงานที่สั่งแล้ว</a>
        <a href="review_assignment.php"><i class="fa-solid fa-check-circle"></i> ตรวจสอบงานที่ตอบกลับ</a>
        <a href="group_review.php"><i class="fa-solid fa-user-edit"></i>ตรวจสอบงานกลุ่มที่สั่ง</a>
        <a href="edit_profile_admin.php"><i class="fa-solid fa-user-edit"></i> แก้ไขข้อมูลส่วนตัว</a>
        <a href="../logout.php"><i class="fa-solid fa-sign-out-alt"></i> ออกจากระบบ</a>
    </div>
    <div id="main">
        <div class="form-container">
            <div class="form-box">
                <form action="admin_assign.php" method="POST" enctype="multipart/form-data">
                    <!-- เลือกผู้ใช้งาน -->
                    <div class="mb-3">
                        <label for="user_ids" class="form-label">เลือกผู้ใช้งาน</label>
                        <button type="button" class="btn btn-worker small" data-bs-toggle="modal" data-bs-target="#userModal">
                            เลือกพนักงาน
                        </button>
                        <div id="selected-users" class="mt-2 text-muted">ยังไม่ได้เลือกผู้ใช้งาน</div>
                    </div>

                    <!-- Modal สำหรับเลือกผู้ใช้งาน -->
                    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="userModalLabel">เลือกผู้ใช้งาน</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-check">
                                        <?php while ($user = mysqli_fetch_assoc($user_result)) { ?>
                                            <div>
                                                <input type="checkbox" class="form-check-input user-checkbox"
                                                    id="user_<?php echo $user['id']; ?>"
                                                    value="<?php echo $user['id']; ?>">
                                                <label class="form-check-label" for="user_<?php echo $user['id']; ?>">
                                                    <?php echo htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']); ?>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                    <button type="button" class="btn btn-primary" id="save-users-btn">บันทึก</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Input สำหรับเก็บค่าผู้ใช้งานที่เลือก -->
                    <input type="hidden" id="user_ids" name="user_ids">

                    <!-- ฟิลด์อื่น ๆ -->
                    <div class="mb-3">
                        <label for="job_title" class="form-label">ชื่องาน</label>
                        <input type="text" class="form-control" id="job_title" name="job_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="job_description" class="form-label">รายละเอียดงาน</label>
                        <textarea class="form-control" id="job_description" name="job_description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">กำหนดส่งวันที่</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="due_time" class="form-label">กำหนดส่งเวลา</label>
                        <input type="time" class="form-control" id="due_time" name="due_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">ไฟล์แนบ (เฉพาะ PDF)</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf">
                    </div>
                    <button type="submit" class="btn btn-primary">สั่งงาน</button>
                    <a href="group_assign.php" class="btn btn-secondary">สั่งงานกลุ่ม</a>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saveUsersBtn = document.getElementById('save-users-btn');
            const selectedUsersContainer = document.getElementById('selected-users');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const hiddenInput = document.getElementById('user_ids');

            saveUsersBtn.addEventListener('click', function() {
                const selectedUsers = [];
                const selectedUserNames = [];

                // เก็บค่าจาก checkbox ที่ถูกเลือก
                userCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selectedUsers.push(checkbox.value);
                        selectedUserNames.push(checkbox.nextElementSibling.textContent);
                    }
                });

                // แสดงรายชื่อที่เลือก
                selectedUsersContainer.innerHTML = selectedUserNames.length ?
                    `<strong>เลือก:</strong> ${selectedUserNames.join(', ')}` :
                    '<strong>ยังไม่ได้เลือกผู้ใช้งาน</strong>';


                // บันทึกค่าใน hidden input
                hiddenInput.value = JSON.stringify(selectedUsers);

                // ปิด Modal
                const userModal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
                userModal.hide();
            });
        });
    </script>

    <script src="../js/sidebar.js"></script>
</body>

</html>