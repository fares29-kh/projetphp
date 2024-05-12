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


$produitController = new ProduitController($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $image = $_POST['image'];
    $categorie = $_POST['categorie'];
    $date_creation = $_POST['date_creation'];
    $date_modification = $_POST['date_modification'];
    $success = $produitController->addProduit($nom, $description, $prix, $image, $categorie, $date_creation, $date_modification);

    if ($success) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "fail"));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    

    if (isset($_GET['idP'])) {

        $idP = $_GET['idP'];

        $product = $produitController->getProduitById($idP);
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(array("error" => "Product not found"));
        }
    } else {
   
        $products = $produitController->fetchProduits();
        echo json_encode($products);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $putData = file_get_contents("php://input");


    $jsonData = json_decode($putData, true);


    if (isset($jsonData['idP'])) {
        $idP = $jsonData['idP'];
        $nom = $jsonData['nom'];
        $description = $jsonData['description'];
        $prix = $jsonData['prix'];
        $image = $jsonData['image'];
        $categorie = $jsonData['categorie'];
        $date_creation = $jsonData['date_creation'];
        $date_modification = $jsonData['date_modification'];


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

    $urlParts = explode('/', $_SERVER['REQUEST_URI']);
    $idP = end($urlParts);

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
