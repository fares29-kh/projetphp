<?php
// categorieController.php
include "../cors.php"; 
include_once "../cnx.php";

class CategorieController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addCategorie($nomC, $descriptionC, $date_creation, $date_modification) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO categorie (nomC, descriptionC, date_creation, date_modification) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nomC, $descriptionC, $date_creation, $date_modification]); 
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
         
            error_log("Error adding category: " . $e->getMessage());
            return false;
        }
    }

    public function getCategorieById($idC) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM categorie WHERE idC = ?");
            $stmt->execute([$idC]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            
            error_log("Error fetching category by ID: " . $e->getMessage());
            return null;
        }
    }

    public function fetchCategories() {
        try {
            $stmt = $this->conn->query("SELECT * FROM categorie");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Error fetching categories: " . $e->getMessage());
            return [];
        }
    }

    // Implement updateCategorie and deleteCategorie methods similarly
}

// Instantiate CategorieController with database connection
$categorieController = new CategorieController($conn);

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are present
    if (isset($_POST['nomC'], $_POST['descriptionC'], $_POST['date_creation'], $_POST['date_modification'])) {
        $nomC = $_POST['nomC'];
        $descriptionC = $_POST['descriptionC'];
        $date_creation = $_POST['date_creation'];
        $date_modification = $_POST['date_modification'];

        // Call addCategorie method and handle response
        $success = $categorieController->addCategorie($nomC, $descriptionC, $date_creation, $date_modification);

        echo json_encode(["status" => $success ? "success" : "fail"]);
    } else {
        echo json_encode(["error" => "Missing required fields"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET requests
    if (isset($_GET['idC'])) {
        $idC = $_GET['idC'];
        $category = $categorieController->getCategorieById($idC);
        echo json_encode($category ? $category : ["error" => "Category not found"]);
    } else {
        $categories = $categorieController->fetchCategories();
        echo json_encode($categories);
    }
} else {
    echo json_encode(["error" => "Unsupported request method"]);
}
?>