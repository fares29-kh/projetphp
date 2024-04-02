<?php

// produitController.php

include_once "../cnx.php";

class ProduitController {
    private $conn;  

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addProduit($nom, $description, $prix, $image, $categorie, $date_creation, $date_modification) {
        $stmt = $this->conn->prepare("INSERT INTO produits (nom, description, prix, image, categorie, date_creation, date_modification) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(array($nom, $description, $prix, $image, $categorie, $date_creation, $date_modification));
        $count = $stmt->rowCount();

        return $count > 0 ? true : false;
    }
//djcdnk
    public function getProduitById($idP)
    {
        $stmt = $this->conn->prepare("SELECT * FROM produits WHERE idP = ?");
        $stmt->execute([$idP]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchProduits() {
        $stmt = $this->conn->query("SELECT * FROM produits");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProduit($idP, $nom, $description, $prix, $image, $categorie, $date_creation, $date_modification) {
        $stmt = $this->conn->prepare("UPDATE produits SET nom = ?, description = ?, prix = ?, image = ?, categorie = ?, date_creation = ?, date_modification = ? WHERE idP = ?");
        $stmt->execute(array($nom, $description, $prix, $image, $categorie, $date_creation, $date_modification, $idP));
        return $stmt->rowCount() > 0;
    }

    public function deleteProduit($idP) {
        $stmt = $this->conn->prepare("DELETE FROM produits WHERE idP = ?");
        $stmt->execute([$idP]);
        return $stmt->rowCount() > 0;
    }
}

// Instantiate ProduitController with database connection
$produitController = new ProduitController($conn);

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract data from POST request
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $image = $_POST['image'];
    $categorie = $_POST['categorie'];
    $date_creation = $_POST['date_creation'];
    $date_modification = $_POST['date_modification'];

    // Call addProduit method and handle response
    $success = $produitController->addProduit($nom, $description, $prix, $image, $categorie, $date_creation, $date_modification);

    if ($success) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "fail"));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET requests
    
    // Check if the request is for fetching all products or a specific product by ID
    if (isset($_GET['idP'])) {
        // Request is for fetching a product by its ID
        $idP = $_GET['idP'];
        // Call getProduitById method and handle response
        $product = $produitController->getProduitById($idP);
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(array("error" => "Product not found"));
        }
    } else {
        // Request is for fetching all products
        $products = $produitController->fetchProduits();
        echo json_encode($products);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get the PUT request body
    $putData = file_get_contents("php://input");

    // Decode the JSON data
    $jsonData = json_decode($putData, true);

    // Check if 'idP' is present in the JSON data
    if (isset($jsonData['idP'])) {
        $idP = $jsonData['idP'];
        $nom = $jsonData['nom'];
        $description = $jsonData['description'];
        $prix = $jsonData['prix'];
        $image = $jsonData['image'];
        $categorie = $jsonData['categorie'];
        $date_creation = $jsonData['date_creation'];
        $date_modification = $jsonData['date_modification'];

        // Call updateProduit method and handle response
        $success = $produitController->updateProduit($idP, $nom, $description, $prix, $image, $categorie, $date_creation, $date_modification);

        if ($success) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "fail"));
        }
    } else {
        echo json_encode(array("error" => "'idP' parameter is missing"));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Extract product ID from URL
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);
    $idP = end($urlParts);

    // Call deleteProduit method and handle response
    $success = $produitController->deleteProduit($idP);

    if ($success) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "fail"));
    }
} else {
    echo json_encode(array("error" => "Unsupported request method"));
}

?>
