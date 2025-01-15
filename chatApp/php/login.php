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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['alias'] = $user['alias'];
        header("Location: mensajes.php");
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<?php include 'html/header.html'; ?>
<main>
    <?php include 'html/login_form.html'; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
</main>
<?php include 'html/footer.html'; ?>
