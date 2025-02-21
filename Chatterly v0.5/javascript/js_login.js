async function login()
{
    const usuario = document.getElementById("usuario-login").value;
    const password = document.getElementById("password-login").value;

    let data = await loginUsuario_Api();
    if(data.status === 'success')
    {
        await fetch(`../php/login.php`, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}`
        });
        
        window.location.href = "../php/chatterly.php";
    }
}