<?php
require 'database/db.php';

class User
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getUserList($start, $length, $searchValue, $draw, $orderColumn, $orderDirection)
    {
        $query = "SELECT users.id, users.username, users.password, users.email, users.full_name, users.type_id, 
                     users.is_active, 
                     type_customers.type_name AS type_name 
              FROM users
              INNER JOIN type_customers ON users.type_id = type_customers.id
              WHERE users.role_id != 1";

        $params = [];
        $types = '';

        if (!empty($searchValue)) {
            $query .= " AND (users.username LIKE ? OR users.email LIKE ? OR users.full_name LIKE ?)";
            $searchWildcard = "%$searchValue%";
            $params[] = &$searchWildcard;
            $params[] = &$searchWildcard;
            $params[] = &$searchWildcard;
            $types .= 'sss';
        }

        if (!empty($orderColumn) && in_array($orderColumn, ['id', 'username', 'email', 'full_name', 'type_name', 'is_active'])) {
            $query .= " ORDER BY $orderColumn $orderDirection";
        } else {
            $query .= " ORDER BY users.id DESC";
        }

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        if (!empty($types)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $totalRecords = $result->num_rows;

        $query .= " LIMIT ?, ?";
        $params[] = &$start;
        $params[] = &$length;
        $types .= 'ii';

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        if (!empty($types)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ];
    }

    public function getUserDetails($id)
    {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return json_encode([
                'status' => 'success',
                'message' => 'Đã xóa người dùng.'
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Không tìm thấy người dùng để xóa.'
            ]);
        }
    }

    public function disableUser($id)
    {
        $query = "UPDATE users SET is_active = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return json_encode([
                'status' => 'success',
                'message' => 'Đã vô hiệu hóa người dùng.'
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Không thể vô hiệu hóa người dùng hoặc người dùng không tồn tại.'
            ]);
        }
    }

    public function enableUser($id)
    {
        $query = "UPDATE users SET is_active = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return json_encode([
                'status' => 'success',
                'message' => 'Đã kích hoạt người dùng.'
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Không thể kích hoạt người dùng hoặc người dùng không tồn tại.'
            ]);
        }
    }

    public function addCustomer($username, $password, $email, $fullName, $phone, $address, $gender, $loyaltyPoints, $typeId, $role_id) {
        $queryCheck = "SELECT * FROM users WHERE username = ? OR email = ? OR phone_number = ?";
        $stmtCheck = $this->conn->prepare($queryCheck);
        if (!$stmtCheck) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmtCheck->bind_param('sss', $username, $email, $phone);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            return ['status' => 'error', 'message' => 'Tên đăng nhập, Email hoặc Số điện thoại đã tồn tại.'];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (username, password, email, full_name, phone_number, address, gender, loyalty_points, type_id,role_id)
              VALUES (?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param('ssssssiiii', $username, $hashedPassword, $email, $fullName, $phone, $address, $gender, $loyaltyPoints, $typeId,$role_id);

        if (!$stmt->execute()) {
            return ['status' => 'error', 'message' => 'Không thể thêm khách hàng. Lỗi: ' . $stmt->error];
        }

        return ['status' => 'success'];
    }

    public function updateUser($id, $username, $password, $email, $fullName, $phone, $address, $gender, $loyaltyPoints, $typeId) {
        $queryCheck = "SELECT * FROM users WHERE id = ?";
        $stmtCheck = $this->conn->prepare($queryCheck);
        if (!$stmtCheck) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmtCheck->bind_param('i', $id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows == 0) {
            return ['status' => 'error', 'message' => 'Người dùng không tồn tại.'];
        }

        $hashedPassword = $password ? password_hash($password, PASSWORD_BCRYPT) : null;

        $query = "UPDATE users SET username = ?, email = ?, full_name = ?, phone_number = ?, address = ?, gender = ?, loyalty_points = ?, type_id = ?, role_id = ?";

        if ($hashedPassword) {
            $query .= ", password = ?";
        }

        $query .= " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $role_id = 2;
        echo $query;
        if ($hashedPassword) {
            $stmt->bind_param('ssssssiiisi', $username, $email, $fullName, $phone, $address, $gender, $loyaltyPoints, $typeId, $role_id, $hashedPassword, $id);
        } else {
            $stmt->bind_param('ssssssiiii', $username, $email, $fullName, $phone, $address, $gender, $loyaltyPoints, $typeId, $role_id, $id);
        }

        if (!$stmt->execute()) {
            return ['status' => 'error', 'message' => 'Không thể cập nhật thông tin người dùng. Lỗi: ' . $stmt->error];
        }

        return ['status' => 'success'];
    }


}


