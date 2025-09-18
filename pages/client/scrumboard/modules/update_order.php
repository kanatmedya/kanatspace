<?php
include("../../../../assets/db/db_connect.php");
    
if (isset($_POST['orders'])) {
    $orders = json_decode($_POST['orders'], true);

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("UPDATE projects SET `displayOrder` = ?, `status` = ? WHERE id = ?");
        foreach ($orders as $order) {
            $stmt->bind_param('isi', $order['order'], $order['status'], $order['id']);
            $stmt->execute();
        }
        $conn->commit();
        echo "Order updated successfully.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error updating order: " . $e->getMessage();
    }

    $stmt->close();
    $conn->close();
}
?>
?>
