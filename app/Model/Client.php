<?php 

function insert_client($conn, $data){
    $sql = "INSERT INTO clients (client_name, contact_info, salesman, platform) VALUES(?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function get_all_clients($conn){
    $sql = "SELECT * FROM clients ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);

    if($stmt->rowCount() > 0){
        $clients = $stmt->fetchAll();
    }else $clients = 0;

    return $clients;
}

function get_client_by_id($conn, $id){
    $sql = "SELECT * FROM clients WHERE id =? ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $client = $stmt->fetch();
    }else $client = 0;

    return $client;
}

function update_client($conn, $data){
    $sql = "UPDATE clients SET client_name=?, contact_info=?, salesman=?, platform=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function delete_client($conn, $id){
    $sql = "DELETE FROM clients WHERE id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
}

function count_clients($conn){
    $sql = "SELECT id FROM clients";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);

    return $stmt->rowCount();
}

function search_clients($conn, $search_term){
    $sql = "SELECT * FROM clients WHERE client_name LIKE ? OR contact_info LIKE ? OR salesman LIKE ? OR platform LIKE ? ORDER BY id DESC";
    $search = "%$search_term%";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$search, $search, $search, $search]);

    if($stmt->rowCount() > 0){
        $clients = $stmt->fetchAll();
    }else $clients = 0;

    return $clients;
}

function get_clients_by_platform($conn, $platform){
    $sql = "SELECT * FROM clients WHERE platform = ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$platform]);

    if($stmt->rowCount() > 0){
        $clients = $stmt->fetchAll();
    }else $clients = 0;

    return $clients;
}

function get_clients_by_salesman($conn, $salesman){
    $sql = "SELECT * FROM clients WHERE salesman = ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$salesman]);

    if($stmt->rowCount() > 0){
        $clients = $stmt->fetchAll();
    }else $clients = 0;

    return $clients;
}

function count_clients_by_platform($conn, $platform){
    $sql = "SELECT id FROM clients WHERE platform = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$platform]);

    return $stmt->rowCount();
}

function count_clients_by_salesman($conn, $salesman){
    $sql = "SELECT id FROM clients WHERE salesman = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$salesman]);

    return $stmt->rowCount();
}
