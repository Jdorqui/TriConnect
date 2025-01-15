<?php
session_start();

$host = 'localhost';
$db = 'chat_app';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'] ?? null;

if ($receiver_id) {
    $stmt = $conn->prepare("
        SELECT m.mensaje, m.fecha_envio, u1.alias AS sender_alias, u2.alias AS receiver_alias
        FROM mensajes m
        JOIN usuarios u1 ON m.sender_id = u1.id
        JOIN usuarios u2 ON m.receiver_id = u2.id
        WHERE (m.sender_id = :user_id AND m.receiver_id = :receiver_id) 
           OR (m.sender_id = :receiver_id AND m.receiver_id = :user_id)
        ORDER BY m.fecha_envio ASC
    ");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$stmt = $conn->prepare("SELECT id, alias FROM usuarios WHERE id != :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'html/header.html'; ?>
<main>
    <div id="conversations">
        <?php include 'html/chat_list.html'; ?>
    </div>

    <?php if ($receiver_id): ?>
        <div id="chat-window">
            <?php include 'html/chat_window.html'; ?>
        </div>
    <?php endif; ?>
</main>
<?php include 'html/footer.html'; ?>
