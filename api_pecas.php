<?php
// Configuração de cabeçalhos para aceitar JSON
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");

// Conexão com o Banco de Dados MySQL criado na Etapa 1
$host = "localhost";
$db_name = "auto_pecas_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exception) {
    echo json_encode(array("status" => "erro", "mensagem" => "Erro de conexão: " . $exception->getMessage()));
    exit();
}

// Verifica o método da requisição (GET para listar, POST para cadastrar)
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // Rota GET: Consulta todas as peças no estoque
    $query = "SELECT id_produto, nome, codigo_original, fabricante, veiculo_aplicacao, localizacao, quantidade_estoque FROM produto";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(array(
        "status" => "sucesso",
        "total" => count($produtos),
        "dados" => $produtos
    ));

} elseif ($method == 'POST') {
    // Rota POST: Recebe os dados em JSON e cadastra uma nova peça
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->nome) && !empty($data->codigo_original) && !empty($data->quantidade_estoque)) {
        
        $query = "INSERT INTO produto (nome, codigo_original, fabricante, veiculo_aplicacao, localizacao, quantidade_estoque) VALUES (:nome, :codigo_original, :fabricante, :veiculo_aplicacao, :localizacao, :quantidade_estoque)";
        
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(':nome', $data->nome);
        $stmt->bindParam(':codigo_original', $data->codigo_original);
        $stmt->bindParam(':fabricante', $data->fabricante);
        $stmt->bindParam(':veiculo_aplicacao', $data->veiculo_aplicacao);
        $stmt->bindParam(':localizacao', $data->localizacao);
        $stmt->bindParam(':quantidade_estoque', $data->quantidade_estoque);

        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("status" => "sucesso", "mensagem" => "Peça cadastrada com sucesso no estoque!"));
        } else {
            http_response_code(503);
            echo json_encode(array("status" => "erro", "mensagem" => "Não foi possível cadastrar a peça."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("status" => "erro", "mensagem" => "Dados incompletos. Preencha os campos obrigatórios."));
    }
}
?>