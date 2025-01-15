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
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $alias = $_POST['alias'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email OR alias = :alias");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':alias', $alias);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = "El email o alias ya están registrados.";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (email, password, alias) VALUES (:email, :password, :alias)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':alias', $alias);
        $stmt->execute();
        header("Location: login.php");
        exit;
    }
}
?>

<?php include 'html/header.html'; ?>
<main>
    <?php include 'html/register_form.html'; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
</main>
<?php include 'html/footer.html'; ?>
