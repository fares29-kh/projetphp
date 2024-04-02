<?php

// categorieController.php

include_once "../cnx.php";

class CategorieController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addCategorie($nomC, $descriptionC, $date_creation, $date_modification) {
        $stmt = $this->conn->prepare("INSERT INTO categorie (nomC, descriptionC, date_creation, date_modification) VALUES (?, ?, ?, ?)");
        $stmt->execute(array($nomC, $descriptionC, $date_creation, $date_modification));
        $count = $stmt->rowCount();

        return $count > 0 ? true : false;
    }
    public function getCateogriebyId($idC)
    {
        $requette = $this->conn->prepare("SELECT * FROM categorie where idC=$idC");
        $requette->execute([$idC]);
        return $requette->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchCategories() {
        $stmt = $this->conn->query("SELECT * FROM categorie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateCategorie($idC, $nomC, $descriptionC, $date_creation, $date_modification) {
        $stmt = $this->conn->prepare("UPDATE categorie SET nomC = ?, descriptionC = ?, date_creation = ?, date_modification = ? WHERE idC = ?");
        $stmt->execute(array($nomC, $descriptionC, $date_creation, $date_modification, $idC));
        return $stmt->rowCount() > 0;
    }
    
    

    public function deleteCategorie($idC) {
        $stmt = $this->conn->prepare("DELETE FROM categorie WHERE idC = ?");
        $stmt->execute([$idC]);
        return $stmt->rowCount() > 0;
    }
    
}

// Instantiate CategorieController with database connection
$categorieController = new CategorieController($conn);




// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract data from POST request
    $nomC = $_POST['nomC'];
    $descriptionC = $_POST['descriptionC'];
    $date_creation = $_POST['date_creation'];
    $date_modification = $_POST['date_modification'];

    // Call addCategorie method and handle response
    $success = $categorieController->addCategorie($nomC, $descriptionC, $date_creation, $date_modification);

    if ($success) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "fail"));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') 
    {
        // Handle GET requests
    
        // Check if the request is for fetching all categories or a specific category by ID
        if (isset($_GET['idC'])) {
            // Request is for fetching a category by its ID
            $idC = $_GET['idC'];
            // Call getById method and handle response
            $category = $categorieController->getCateogriebyId($idC);
            if ($category) {
                echo json_encode($category);
            } else {
                echo json_encode(array("error" => "Category not found"));
            }
        } else {
            // Request is for fetching all categories
            $categories = $categorieController->fetchCategories();
            echo json_encode($categories);
        }
}elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get the PUT request body
    $putData = file_get_contents("php://input");

    // Decode the JSON data
    $jsonData = json_decode($putData, true);

    // Check if 'idC' is present in the JSON data
    if (isset($jsonData['idC'])) {
        $id = $jsonData['idC'];
        $nomC = $jsonData['nomC'];
        $descriptionC = $jsonData['descriptionC'];
        $date_creation = $jsonData['date_creation']; // Assuming this parameter is present
        $date_modification = $jsonData['date_modification'];

        // Call updateCategorie method and handle response
        $success = $categorieController->updateCategorie($id, $nomC, $descriptionC, $date_creation, $date_modification);

        if ($success) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "fail"));
        }
    } else {
        echo json_encode(array("error" => "'idC' parameter is missing"));
    }
}


  elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Extract category ID from URL
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);
    $idC = end($urlParts);

    // Call deleteCategorie method and handle response
    $success = $categorieController->deleteCategorie($idC);

    if ($success) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "fail"));
    }
}
else {
    echo json_encode(array("error" => "Unsupported request method"));
}

?>


